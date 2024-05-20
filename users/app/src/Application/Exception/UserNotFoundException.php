<?php

declare(strict_types=1);

namespace App\Application\Exception;

final class UserNotFoundException extends \DomainException
{
    protected $code = 100_404;
    protected $message = 'users.user_not_found';
}
