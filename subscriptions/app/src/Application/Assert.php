<?php

declare(strict_types=1);

namespace App\Application;

use App\Application\Exception\InvalidArgumentException;

final class Assert extends \Webmozart\Assert\Assert
{
    protected static function reportInvalidArgument($message)
    {
        throw new InvalidArgumentException($message);
    }
}
