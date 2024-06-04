<?php

declare(strict_types=1);

namespace Generator\Generators;

use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\InjectableConfig;
use Spiral\Files\FilesInterface;
use Spiral\Reactor\FileDeclaration;
use Spiral\Reactor\Writer;

final class ConfigGenerator implements GeneratorInterface
{
    private const CONFIG_NAME = 'GRPCServicesConfig';

    public function __construct(
        private readonly FilesInterface $files
    ) {
    }

    /**
     * @param non-empty-string[] $files
     * @param non-empty-string $targetPath
     * @param non-empty-string $namespace
     */
    public function run(array $files, string $targetPath, string $namespace): void
    {
        if ($this->files->exists($this->getPath($targetPath))) {
            return;
        }

        $file = new FileDeclaration();
        $configNamespace = $file->addNamespace($namespace . '\\' . 'Config');
        $configNamespace->addUse(InjectableConfig::class);
        $configNamespace->addUse('Grpc\\ChannelCredentials');
        $configNamespace->addUse(CoreInterceptorInterface::class);
        $config = $configNamespace->addClass(self::CONFIG_NAME);
        $config->setFinal(true);
        $config->setExtends(InjectableConfig::class);

        $config->addConstant('CONFIG', 'grpc-services')->setPublic();
        $config
            ->addProperty('config', ['services' => [], 'interceptors' => []])
            ->setProtected()
            ->setType('array')
            ->setComment(<<<'PHPDOC'
@var array{services: array{
    host: string, credentials?: mixed},
    interceptors: class-string<CoreInterceptorInterface>[]
}
PHPDOC
);

        $config
            ->addMethod('getDefaultCredentials')
            ->setPublic()
            ->addBody('return ChannelCredentials::createInsecure();')
            ->setReturnType('Grpc\\ChannelCredentials|null');

        $config
            ->addMethod('getInterceptors')
            ->setPublic()
            ->setBody('return $this->config[\'interceptors\'];')
            ->setReturnType('array');

        $config
            ->addMethod('getService')
            ->addComment('Get service definition.')
            ->addComment('@return array{host: string, credentials?: mixed}')
            ->setPublic()
            ->setReturnType('array')
            ->setBody(
                <<<'EOL'
                return $this->config['services'][$name] ?? [
                    'host' => 'localhost'
                ];
                EOL
            )
            ->addParameter('name')
            ->setType('string');

        (new Writer($this->files))->write($this->getPath($targetPath), $file);
    }

    /**
     * @param non-empty-string $targetPath
     *
     * @return non-empty-string
     */
    private function getPath(string $targetPath): string
    {
        return \sprintf('%s/Config/%s.php', $targetPath, self::CONFIG_NAME);
    }
}
