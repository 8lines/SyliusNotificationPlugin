<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\EventListener;

use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventInterface;
use Sylius\Component\Registry\ServiceRegistry;
use Symfony\Component\EventDispatcher\GenericEvent;

class NotificationEventListener
{
    public function __construct(
        private ServiceRegistry $notificationEventRegistry,
    ) {
    }

    public function onNotificationEvent(
        GenericEvent $event,
        string $eventName,
    ): void {
        if (false === $this->notificationEventRegistry->has($eventName)) {
            return;
        }

        /** @var NotificationEventInterface $notificationEvent */
        $notificationEvent = $this->notificationEventRegistry->get($eventName);

        // TODO: handle event
    }
}
