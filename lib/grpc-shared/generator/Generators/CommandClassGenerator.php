<?php

declare(strict_types=1);

namespace Generator\Generators;

use CuyZ\Valinor\Mapper\Source\Source;
use Generator\Generators\Command\ClassPropertiesGenerator;
use Generator\Generators\Command\JsonSerializationGenerator;
use Generator\Generators\Command\PropertyType;
use Generator\Generators\Message\MessageClassParser;
use Generator\PHP\ClassDeclarationFactory;
use Generator\PHP\ClassTransformer;
use Google\Protobuf\Internal\Message;
use Internal\CQRS\Attribute\CommandHandler;
use Internal\CQRS\Attribute\CommandReturn;
use Internal\CQRS\CommandInterface;
use Internal\Shared\gRPC\AbstractMapper;
use Internal\Shared\gRPC\Attribute\Mapper;
use Internal\Shared\gRPC\CommandMapper;
use Internal\Shared\gRPC\MapperInterface;
use Internal\Shared\gRPC\RequestContext;
use Nette\PhpGenerator\Literal;
use Psr\Container\ContainerInterface;
use Spiral\Files\FilesInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CommandClassGenerator implements GeneratorInterface
{
    /** @var array<class-string, array{class: \Generator\PHP\ClassDeclaration}> */
    private array $commands = [];

    public function __construct(
        private readonly FilesInterface $files,
        private readonly OutputInterface $output,
    ) {
    }

    public function run(array $files, string $targetPath, string $commandNamespace): void
    {
        $classFactory = new ClassDeclarationFactory($this->files);

        $commandNamespacePrefix = $commandNamespace . '\\Command\\';
        $handlerNamespacePrefix = $commandNamespace . '\\Handler\\';

        $parser = new MessageClassParser();
        $jsonSerializerGenerator = new JsonSerializationGenerator();
        $propertiesGenerator = new ClassPropertiesGenerator($this->files, $targetPath, $commandNamespacePrefix);

        $parsedClasses = [];

        foreach ($files as $file) {
            try {
                $class = $classFactory->createFromFile($file);
                $result = $parser->parse($class->getNameWithNamespace());
            } catch (\Throwable $e) {
                if ($this->output->isVerbose()) {
                    $this->output->writeln("<error>Error parsing $file: " . $e->getFile() . "</error>");
                    $this->output->writeln("<error> " . $e->getMessage() . "</error>");
                }
                continue;
            }

            if ($class->class->isClass() && $class->class->getExtends() === null) {
                if ($this->output->isVerbose()) {
                    $this->output->writeln("<error>Skipped $file: it does not extend Message class</error>");
                }
                continue;
            }

            $commandClass = new ClassTransformer($class->getNameWithNamespace());
            $parsedClasses[$commandClass->cleanNamespace($commandNamespacePrefix)->class] = $result;
        }

        $classMap = [];

        foreach ($parsedClasses as $commandClassName => $parserClass) {
            $commandClass = $classFactory->createFromClass(
                $commandClassName,
                $targetPath . '/Command/',
            );

            if ($this->output->isVerbose()) {
                $this->output->writeln("<info>Generating $commandClass...</info>");
            }

            if ($commandClass->isClassNameEndsWith('Request')) {
                $commandClass->class->addImplement(CommandInterface::class);
                $commandClass->namespace->addUse(CommandInterface::class);
            }

            $commandClass->class->addComment('This class is read only. Please do not edit it directly.');
            $commandClass->markAsFinal();
            $commandClass->markAsReadonly();

            $propertiesGenerator->generate(
                $commandClass,
                $parserClass->properties,
            );

            $jsonSerializerGenerator->generate(
                $commandClass,
                $parserClass->properties,
            );

            $commandClass->persist();

            $classMap[$parserClass->class] = $commandClass->getNameWithNamespace();

            $this->commands[$commandClass->getNameWithNamespace()] = [
                'class' => $commandClass,
                'properties' => $parserClass->properties,
            ];
        }

        $this->generateMappers($classMap, $targetPath, $classFactory);

        foreach ($files as $file) {
            if (!\str_ends_with($file, 'Interface.php')) {
                continue;
            }

            $interface = $classFactory->createFromFile($file);
            $interfaceClass = new ClassTransformer($interface->getNameWithNamespace());

            $serviceClass = $interface->getNamespace() . '\\' . $interface->getName();

            foreach ($interface->class->getMethods() as $method) {
                $namespace = $interfaceClass->cleanNamespace($handlerNamespacePrefix)->getNamespace();
                $handlerClass = $classFactory->createFromClass(
                    $namespace . '\\' . $method->getName() . 'Handler',
                    $targetPath . '/Handler/',
                );

                if ($this->output->isVerbose()) {
                    $this->output->writeln("<info>Generating $handlerClass...</info>");
                }

                $handlerClass->namespace->addUse($serviceClass);
                $handlerClass->markAsReadonly();
                $handlerClass->markAsFinal();
                $handlerClass->class->setComment([
                    '@internal',
                    'This class is read only. Please do not edit it directly.',
                ]);

                $constructor = $handlerClass->class->addMethod('__construct');
                $constructor->addPromotedParameter('service')
                    ->setPrivate()
                    ->setType($serviceClass);
                $constructor->addPromotedParameter('mapper')
                    ->setPrivate()
                    ->setType(CommandMapper::class);
                $constructor->addPromotedParameter('container')
                    ->setPrivate()
                    ->setType(ContainerInterface::class);

                $handlerClass->namespace->addUse(CommandMapper::class);
                $handlerClass->namespace->addUse(ContainerInterface::class);

                $handlerMethod = $handlerClass->class->addMethod('__invoke');

                $requestClass = $classMap[\ltrim($method->getParameters()->get('in')->getType(), '\\')] ?? null;
                $responseClass = $classMap[\ltrim($method->getReturnType(), '\\')] ?? null;
                if (!$requestClass || !$responseClass) {
                    continue;
                }

                $requestClass = new ClassTransformer($requestClass);
                $responseClass = new ClassTransformer($responseClass);

                $handlerMethod->addParameter('request')
                    ->setType($requestClass->class);

                $handlerMethod->addAttribute(CommandHandler::class);
                $handlerMethod->setReturnType($responseClass->class);

                $handlerMethod->addBody(
                    \sprintf(
                        <<<'PHP'
$response = $this->service->%s(
    $this->container->get(RequestContext::class),
    $this->mapper->toMessage($request)
);

return $this->mapper->fromMessage($response);
PHP,
                        $method->getName(),
                    ),
                );

                $handlerClass->namespace->addUse($requestClass->class);
                $handlerClass->namespace->addUse($responseClass->class);
                $handlerClass->namespace->addUse(RequestContext::class);
                $handlerClass->namespace->addUse(CommandHandler::class);

                $handlerClass->persist();

                $commandClass = $this->commands[$requestClass->class]['class'];
                $commandClass->class->addAttribute(CommandReturn::class, [
                    'class' => new Literal($responseClass->getShortName() . '::class'),
                ]);
                $commandClass->class->addComment(
                    \sprintf('@implements CommandInterface<%s>', $responseClass->getShortName()),
                );

                $this->commands[$requestClass->class]['class']->namespace->addUse(CommandReturn::class);
                $this->commands[$requestClass->class]['class']->namespace->addUse($responseClass->class);
                $this->commands[$requestClass->class]['class']->persist();
            }
        }
    }

    private function generateMappers(array $classMap, string $targetPath, ClassDeclarationFactory $factory): void
    {
        foreach ($classMap as $message => $dto) {
            $dtoClass = new ClassTransformer($dto);

            $namespace = \str_replace('\\Command', '\\Mapper', $dtoClass->getNamespace());

            $messageClass = new ClassTransformer($message);
            $mapperClass = $factory->createFromClass(
                $namespace . '\\' . $dtoClass->getShortName() . 'Mapper',
                $targetPath . '/Mapper/',
            );
            if ($this->output->isVerbose()) {
                $this->output->writeln("<info>Generating $mapperClass...</info>");
            }

            $messageClassName = $messageClass->getShortName() . 'Message';
            $dtoClassName = $dtoClass->getShortName() . '::class';

            $mapperClass->namespace->addUse(AbstractMapper::class);
            $mapperClass->namespace->addUse(Message::class);
            $mapperClass->namespace->addUse(Mapper::class);
            $mapperClass->namespace->addUse($message, $messageClassName);
            $mapperClass->namespace->addUse($dto);
            $mapperClass->namespace->addUse(Source::class);


            $mapperClass->class->setComment([
                '@internal',
                \sprintf(
                    '@implements AbstractMapper<%s, %s>',
                    $messageClass->getShortName() . 'Message',
                    $dtoClass->getShortName(),
                ),
                'This class won\'t be overwritten after recompile.',
            ]);

            $mapperClass->class->setExtends(AbstractMapper::class);
            $mapperClass->markAsFinal();
            $mapperClass->markAsReadonly();

            $mapperClass->class->addAttribute(Mapper::class, [
                'class' => new Literal($dtoClass->getShortName() . '::class'),
                'messageClass' => new Literal($messageClassName . '::class'),
            ]);

            $messageRefl = new \ReflectionClass(MapperInterface::class);

            foreach ($messageRefl->getMethods() as $method) {
                if ($method->getName() !== 'fromMessage') {
                    continue;
                }

                $m = $mapperClass->class->addMethod($method->getName());
                $m->setReturnType($method->getReturnType()->getName());

                foreach ($method->getParameters() as $parameter) {
                    $m->addParameter($parameter->getName())
                        ->setType($parameter->getType()->getName());

                    if ($parameter->getName() === 'message') {
                        if ($parameter->getType()->getName() === 'string') {
                            $m->addComment(
                                '@param class-string<' . $messageClass->getShortName() . 'Message> $message',
                            );
                        } else {
                            $m->addComment('@param ' . $messageClass->getShortName() . 'Message $message');
                        }
                    } elseif ($parameter->getName() === 'class') {
                        $m->addComment('@param class-string<' . $dtoClass->getShortName() . '> $class');
                    } elseif ($parameter->getName() === 'object') {
                        $m->addComment('@param ' . $dtoClass->getShortName() . ' $object');
                    }
                }

                /** @var PropertyType[] $properties */
                $properties = $this->commands[$dto]['properties'];

                $array = "\$array = [];";
                foreach ($properties as $property) {
                    $array .= \sprintf(
                        "\n\$array['%s'] = \$this->mapValue(\$message->%s());",
                        $property->getCamelCaseVariable(),
                        $property->getMethodName(),
                    );
                }

                $m->addBody(
                    \sprintf(
                        <<<'PHP'
%s

return $this->treeMapper->map($class, Source::array(\array_filter($array)));
PHP
                        ,
                        $array,
                    ),
                );
            }

            $mapperClass->persist();
        }
    }
}
