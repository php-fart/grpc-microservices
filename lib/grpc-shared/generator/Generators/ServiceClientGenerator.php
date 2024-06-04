<?php

declare(strict_types=1);

namespace Generator\Generators;

use Internal\Shared\gRPC\Attribute\ServiceClient;
use Internal\Shared\gRPC\Service\ServiceClientTrait;
use Spiral\Files\FilesInterface;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\FileDeclaration;
use Spiral\Reactor\InterfaceDeclaration;
use Spiral\Reactor\Partial\Method;
use Spiral\Reactor\Partial\PhpNamespace;
use Spiral\Reactor\Writer;
use Spiral\RoadRunner\GRPC\ContextInterface;

final class ServiceClientGenerator implements GeneratorInterface
{
    public function __construct(
        private readonly FilesInterface $files,
    ) {
    }

    public function run(array $files, string $targetPath, string $namespace): void
    {
        foreach ($files as $file) {
            if (!\str_ends_with($file, 'Interface.php')) {
                continue;
            }

            $interfaceFile = FileDeclaration::fromCode($this->files->read($file));

            /** @var InterfaceDeclaration $interface */
            $interface = $interfaceFile->getInterfaces()->getIterator()->current();

            /** @var PhpNamespace $interfaceNamespace */
            $interfaceNamespace = $interfaceFile->getNamespaces()->getIterator()->current();
            $namespacePrefix = \str_replace($namespace, '', $interfaceNamespace->getName());

            $clientFile = new FileDeclaration();
            /** @psalm-suppress PossiblyNullArgument */
            $clientNamespace = $clientFile->addNamespace($interfaceNamespace->getName());
            $clientNamespace->addUse(ContextInterface::class);
            $clientNamespace->addUse(ServiceClient::class);
            $clientNamespace->addUse(ServiceClientTrait::class);
            $clientNamespace->addUse($interfaceNamespace->getName() . '\\' . $interface->getName());

            $className = \str_replace('Interface', '', (string)$interface->getName());
            if (!\str_ends_with($className, 'Service')) {
                $className .= 'Service';
            }

            $className .= 'Client';

            $client = $clientNamespace->addClass($className);
            $client->addAttribute(ServiceClient::class, [
                'name' => $interface->getConstant('NAME')->getValue(),
            ]);
            $client->setFinal(true);
            $client->addImplement($interfaceNamespace->getName() . '\\' . $interface->getName());
            $client->addTrait(ServiceClientTrait::class);

            foreach ($interface->getMethods() as $method) {
                $this->addMethodBody($method, $client, $interface);

                foreach ($method->getParameters() as $parameter) {
                    $clientNamespace->addUse($parameter->getType());
                }

                $clientNamespace->addUse($method->getReturnType());
            }

            $filePathPrefix = \str_replace('\\', '/', $namespacePrefix);
            $clientFilePath = $targetPath . $filePathPrefix . '/' . $client->getName() . '.php';

            (new Writer($this->files))->write($clientFilePath, $clientFile);

            (new ServiceClientTestsGenerator($this->files))->run($targetPath, $clientNamespace, $client);
        }
    }

    private function addMethodBody(Method $method, ClassDeclaration $client, InterfaceDeclaration $interface): void
    {
        $methodName = $method->getName();

        \assert($methodName !== null);

        $clientMethod = $client->addMethod($methodName);
        $clientMethod->setParameters($method->getParameters());
        $clientMethod->setReturnType($method->getReturnType());

        $clientMethod->addBody(
            <<<'EOL'
return $this->callAction(__FUNCTION__, $ctx, $in);
EOL,
        );
    }
}
