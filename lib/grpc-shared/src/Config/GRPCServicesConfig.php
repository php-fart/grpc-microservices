<?php

declare(strict_types=1);

namespace Internal\Shared\gRPC\Config;

use Grpc\ChannelCredentials;
use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\InjectableConfig;

final class GRPCServicesConfig extends InjectableConfig
{
    public const CONFIG = 'grpc-services';

    /**
     * @var array{services: array{
     *     host: string, credentials?: mixed},
     *     interceptors: class-string<CoreInterceptorInterface>[]
     * }
     */
    protected array $config = ['services' => [], 'interceptors' => []];

    public function getDefaultCredentials(): ChannelCredentials|null
    {
        return ChannelCredentials::createInsecure();
    }

    public function getInterceptors(): array
    {
        return $this->config['interceptors'];
    }

    /**
     * Get service definition.
     * @return array{host: string, credentials?: mixed}
     */
    public function getService(string $name): array
    {
        return $this->config['services'][$name] ?? [
            'host' => 'localhost',
        ];
    }
}
