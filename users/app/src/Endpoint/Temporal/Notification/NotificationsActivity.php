<?php

declare(strict_types=1);

namespace App\Endpoint\Temporal\Notification;

use GRPC\Services\Users\v1\User;
use Spiral\TemporalBridge\Attribute\AssignWorker;
use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;

#[AssignWorker('notifications.service')]
#[ActivityInterface(prefix: 'notification.')]
interface NotificationsActivity
{
    #[ActivityMethod(name: 'sendWelcomeEmail')]
    public function sendWelcomeEmail(User $user): void;
}
