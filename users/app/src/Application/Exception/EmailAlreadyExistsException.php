<?php

declare(strict_types=1);

namespace App\Application\Exception;

final class EmailAlreadyExistsException extends \DomainException
{
    protected $code = 422;
    protected $message = 'Email already exists';
}
