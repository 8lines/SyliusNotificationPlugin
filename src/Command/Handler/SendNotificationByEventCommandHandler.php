<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Command\Handler;

use EightLines\SyliusNotificationPlugin\Command\SendNotificationByEventCommand;
use EightLines\SyliusNotificationPlugin\Command\RunNotificationActionCommand;
use EightLines\SyliusNotificationPlugin\Entity\NotificationActionInterface;
use EightLines\SyliusNotificationPlugin\Entity\NotificationInterface;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationContext;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariables;
use EightLines\SyliusNotificationPlugin\Resolver\NotificationEventResolverInterface;
use EightLines\SyliusNotificationPlugin\Resolver\NotificationResolverInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class SendNotificationByEventCommandHandler
{
    public function __construct(
        private NotificationResolverInterface $notificationResolver,
        private NotificationEventResolverInterface $notificationEventResolver,
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(SendNotificationByEventCommand $command): void
    {
        $eventCode = $command->getEventName();

        /** @var object $subject */
        $subject = $command->getSubject();

        $notificationEvent = $this->notificationEventResolver->resolveByEventCode(
            eventCode: $eventCode,
        );

        if (null === $notificationEvent) {
            return;
        }

        $notificationContext = NotificationContext::create(
            subject: $subject,
            event: $notificationEvent,
        );

        $channelCode = $notificationEvent->getSyliusChannelCode($notificationContext);

        if (null === $channelCode) {
            return;
        }

        $notifications = $this->notificationResolver->resolveByEventCodeAndSyliusChannelCode(
            eventCode: $eventCode,
            channelCode: $channelCode,
        );

        if (0 === \count($notifications)) {
            return;
        }

        $notificationVariables = $notificationEvent->getVariables($notificationContext);

        /** @var array<integer, array<integer, NotificationActionInterface>> $notificationActions */
        $notificationActions = array_map(
            callback: fn(NotificationInterface $item) => $item->getActions()->toArray(),
            array: $notifications,
        );

        $this->handleNotificationActions(
            context: $notificationContext,
            variables: $notificationVariables,
            actions: array_merge(...$notificationActions),
        );
    }

    /**
     * @param NotificationActionInterface[] $actions
     */
    private function handleNotificationActions(
        NotificationContext $context,
        NotificationEventVariables $variables,
        array $actions,
    ): void {
        foreach ($actions as $action) {
            $this->messageBus->dispatch(new RunNotificationActionCommand(
                context: $context,
                action: $action,
                variables: $variables,
            ));
        }
    }
}
