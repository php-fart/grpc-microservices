<?php

declare(strict_types=1);

namespace App\Application;

use App\Application\Bootloader\AppBootloader;
use App\Application\Bootloader\AuthBootloader;
use App\Application\Bootloader\PersistenceBootloader;
use App\Application\Bootloader\UserBootloader;
use Internal\Shared\gRPC\Bootloader\ServiceBootloader;
use Spiral\Boot\Bootloader\CoreBootloader;
use Spiral\Bootloader as Framework;
use Spiral\Cycle\Bootloader as CycleBridge;
use Spiral\Debug\Bootloader\DumperBootloader;
use Spiral\DotEnv\Bootloader\DotenvBootloader;
use Spiral\Monolog\Bootloader\MonologBootloader;
use Spiral\Nyholm\Bootloader\NyholmBootloader;
use Spiral\Prototype\Bootloader\PrototypeBootloader;
use Spiral\RoadRunnerBridge\Bootloader as RoadRunnerBridge;
use Spiral\Scaffolder\Bootloader\ScaffolderBootloader;
use Spiral\Sentry\Bootloader\SentryReporterBootloader;
use Spiral\TemporalBridge\Bootloader\TemporalBridgeBootloader;
use Spiral\Tokenizer\Bootloader\TokenizerListenerBootloader;
use Spiral\YiiErrorHandler\Bootloader\YiiErrorHandlerBootloader;
use Spiral\OpenTelemetry\Bootloader\OpenTelemetryBootloader;

class Kernel extends \Spiral\Framework\Kernel
{
    public function defineSystemBootloaders(): array
    {
        return [
            CoreBootloader::class,
            DotenvBootloader::class,
            TokenizerListenerBootloader::class,

            DumperBootloader::class,
        ];
    }

    public function defineBootloaders(): array
    {
        return [
            // Logging and exceptions handling
            MonologBootloader::class,
            Bootloader\ExceptionHandlerBootloader::class,

            OpenTelemetryBootloader::class,

            // Sentry and Data collectors
            SentryReporterBootloader::class,
            Framework\DebugBootloader::class,
            Framework\Debug\LogCollectorBootloader::class,

            // RoadRunner
            RoadRunnerBridge\LoggerBootloader::class,

            // Core Services
            Framework\SnapshotsBootloader::class,

            // Security and validation
            Framework\Security\EncrypterBootloader::class,
            Framework\Security\FiltersBootloader::class,
            Framework\Security\GuardBootloader::class,

            // Databases
            CycleBridge\DatabaseBootloader::class,
            CycleBridge\MigrationsBootloader::class,

            // ORM
            CycleBridge\SchemaBootloader::class,
            CycleBridge\CycleOrmBootloader::class,
            CycleBridge\AnnotatedBootloader::class,
            CycleBridge\AuthTokensBootloader::class,

            NyholmBootloader::class,

            RoadRunnerBridge\GRPCBootloader::class,

            TemporalBridgeBootloader::class,

            // Console commands
            Framework\CommandBootloader::class,
            RoadRunnerBridge\CommandBootloader::class,
            CycleBridge\CommandBootloader::class,
            ScaffolderBootloader::class,
            RoadRunnerBridge\ScaffolderBootloader::class,
            CycleBridge\ScaffolderBootloader::class,
            CycleBridge\EntityBehaviorBootloader::class,

            // Fast code prototyping
            PrototypeBootloader::class,
            PersistenceBootloader::class,
            ServiceBootloader::class,

            AppBootloader::class,
        ];
    }
}
