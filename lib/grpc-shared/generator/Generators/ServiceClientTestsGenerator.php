<?php

declare(strict_types=1);

namespace Generator\Generators;

use Spiral\Files\FilesInterface;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\FileDeclaration;
use Spiral\Reactor\Partial\PhpNamespace;
use Spiral\Reactor\Writer;
use Tests\ServiceClientTestCase;
use Internal\Shared\gRPC\Exception\GrpcExceptionMapper;

final class ServiceClientTestsGenerator
{
    public function __construct(
        private readonly FilesInterface $files,
    ) {
    }

    public function run(string $targetPath, PhpNamespace $namespace, ClassDeclaration $class): void
    {
        $clientFile = new FileDeclaration();
        /** @psalm-suppress PossiblyNullArgument */
        $clientNamespace = $clientFile->addNamespace('Tests\\Service\\Client');
        $clientNamespace->addUse($namespace->getName() . '\\' . $class->getName());
        $clientNamespace->addUse(ServiceClientTestCase::class);
        $clientNamespace->addUse(GrpcExceptionMapper::class);

        $client = $clientNamespace->addClass($class->getName() . 'Test');
        $client->setFinal(true);
        $client->setExtends(ServiceClientTestCase::class);

        $code = [];

        foreach ($class->getMethods() as $method) {
            $line = 'yield \'' . $method->getName() . '\' => [';

            $line .= '\'' . $method->getName() . '\', ';

            foreach ($method->getParameters() as $parameter) {
                if ($parameter->getName() === 'ctx') {
                    continue;
                }

                $clientNamespace->addUse($parameter->getType());
                $line .= 'new ' . $this->getClassName($parameter->getType()) . '(), ';
            }

            $clientNamespace->addUse($method->getReturnType());
            $line .= 'new ' . $this->getClassName($method->getReturnType()) . '(), ';
            $line .= '];';

            $code[] = $line;
        }

        $code = \implode("\n", $code);

        $client->addMethod('methodsDataProvider')
            ->setStatic(true)
            ->setReturnType(\Generator::class)
            ->setBody($code);

        $client->addMethod('makeClient')
            ->setProtected()
            ->setReturnType('object')
            ->setBody('return new ' . $class->getName() . '($this->getCore(), new GrpcExceptionMapper());');

        (new Writer($this->files))->write(
            $targetPath . '/../tests/src/Service/Client/' . $class->getName() . 'Test.php',
            $clientFile
        );
    }

    private function getClassName(string $classNameWithNamespace): string
    {
        $parts = \explode('\\', $classNameWithNamespace);

        return \array_pop($parts);
    }
}
