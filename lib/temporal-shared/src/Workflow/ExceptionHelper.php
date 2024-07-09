<?php

declare(strict_types=1);

namespace Internal\Shared\Temporal\Workflow;

use Temporal\Exception\Client\WorkflowException;
use Temporal\Exception\Failure\ApplicationFailure;
use Temporal\Exception\Failure\TemporalFailure;

final class ExceptionHelper
{
    public static function isThrown(\Throwable $e, string ...$exception): bool
    {
        if (!$e instanceof TemporalFailure) {
            return false;
        }

        while ($e = $e->getPrevious()) {
            if (\method_exists($e, 'getType') && \in_array($e->getType(), $exception, true)) {
                return true;
            }
        }

        return false;
    }

    public static function getApplicationFailure(\Throwable $e): ?ApplicationFailure
    {
        if (!$e instanceof WorkflowException) {
            return null;
        }

        while ($e = $e->getPrevious()) {
            if ($e instanceof ApplicationFailure) {
                return $e;
            }
        }

        return new ApplicationFailure('Unknown error');
    }

    public static function convertToRealException(\Throwable $e): \Throwable
    {
        $failure = self::getApplicationFailure($e);

        if ($failure === null) {
            return $e;
        }

        $type = $failure->getType();

        return new $type($failure->getOriginalMessage(), $failure->getCode(), $e);
    }
}