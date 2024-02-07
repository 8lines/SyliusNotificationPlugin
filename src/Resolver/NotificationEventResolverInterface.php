<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Resolver;

use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventInterface;

interface NotificationEventResolverInterface
{
    public function resolveByEventCode(string $eventCode): ?NotificationEventInterface;
}
