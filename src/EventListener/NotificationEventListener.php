<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\EventListener;

use EightLines\SyliusNotificationPlugin\Command\SendNotificationByEvent;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Messenger\MessageBusInterface;

final class NotificationEventListener
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function onNotificationEvent(
        GenericEvent $event,
        string $eventName,
    ): void {
        $sendNotificationCommand = new SendNotificationByEvent(
            eventName: $eventName,
            subject: $event->getSubject(),
        );

        $this->messageBus->dispatch($sendNotificationCommand);
    }
}
