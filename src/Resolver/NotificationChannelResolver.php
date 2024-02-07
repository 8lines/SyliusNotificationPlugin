<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Resolver;

use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationChannelInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

final class NotificationChannelResolver implements NotificationChannelResolverInterface
{
    public function __construct(
        private ServiceRegistryInterface $registry,
    ) {
    }

    public function resolveByCode(string $code): ?NotificationChannelInterface
    {
        if (!$this->registry->has($code)) {
            return null;
        }

        $notificationChannel = $this->registry->get($code);

        if (!$notificationChannel instanceof NotificationChannelInterface) {
            return null;
        }

        return $notificationChannel;
    }
}
