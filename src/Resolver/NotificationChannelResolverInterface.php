<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Resolver;

use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationChannelInterface;

interface NotificationChannelResolverInterface
{
    public function resolveByCode(string $code): ?NotificationChannelInterface;
}
