<?php

declare(strict_types=1);

namespace App\Application\Exception;

final class InvalidArgumentException extends \InvalidArgumentException
{
    protected $code = 422;
}
