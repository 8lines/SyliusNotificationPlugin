<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\EventListener;

use EightLines\SyliusNotificationPlugin\Command\SendNotificationByEvent\SendNotificationByEventCommand;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventInvoker;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class NotificationEventListener
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private TokenStorageInterface $tokenStorage,
    ) {
    }

    public function onNotificationEvent(
        GenericEvent $event,
        string $eventName,
    ): void {
        /** @var UserInterface|null $currentUser */
        $currentUser = $this->tokenStorage->getToken()?->getUser();

        $invoker = null !== $currentUser
            ? NotificationEventInvoker::fromUser($currentUser)
            : null;

        $sendNotificationCommand = SendNotificationByEventCommand::create(
            eventName: $eventName,
            subject: $event->getSubject(),
            invoker: $invoker,
        );

        $this->messageBus->dispatch($sendNotificationCommand);
    }
}
