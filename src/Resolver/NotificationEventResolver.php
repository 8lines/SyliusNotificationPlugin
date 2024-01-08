<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Resolver;

use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

final class NotificationEventResolver implements NotificationEventResolverInterface
{
    public function __construct(
        private ServiceRegistryInterface $registry,
    ) {
    }

    public function resolveByEventCode(string $eventCode): ?NotificationEventInterface
    {
        if (!$this->registry->has($eventCode)) {
            return null;
        }

        $notificationEvent = $this->registry->get($eventCode);

        if (!$notificationEvent instanceof NotificationEventInterface) {
            return null;
        }

        return $notificationEvent;
    }
}
