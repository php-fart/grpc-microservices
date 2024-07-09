<?php

declare(strict_types=1);

namespace Internal\Shared\Temporal;

interface TaskQueue
{
    public const KYC = 'kyc';
    public const UserRegistration = 'user_registration';
    public const EmailVerification = 'email_verification';
    public const Notifications = 'notifications';
    public const Subscription = 'subscription';
    public const Statuses = 'statuses';
}
