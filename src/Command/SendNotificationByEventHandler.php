<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Command;

use EightLines\SyliusNotificationPlugin\Entity\NotificationActionInterface;
use EightLines\SyliusNotificationPlugin\Entity\NotificationInterface;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationContext;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariables;
use EightLines\SyliusNotificationPlugin\Resolver\NotificationEventResolverInterface;
use EightLines\SyliusNotificationPlugin\Resolver\NotificationResolverInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class SendNotificationByEventHandler
{
    public function __construct(
        private NotificationResolverInterface $notificationResolver,
        private NotificationEventResolverInterface $notificationEventResolver,
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(SendNotificationByEvent $command): void
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

        $syliusChannel = $notificationEvent->getSyliusChannel($notificationContext);
        $syliusChannelCode = $syliusChannel?->getCode();

        if (null === $syliusChannel || null === $syliusChannelCode) {
            return;
        }

        $notifications = $this->notificationResolver->resolveByEventCodeAndSyliusChannelCode(
            eventCode: $eventCode,
            channelCode: $syliusChannelCode,
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
            syliusChannel: $syliusChannel,
            actions: array_merge(...$notificationActions),
        );
    }

    /**
     * @param NotificationActionInterface[] $actions
     */
    private function handleNotificationActions(
        NotificationContext $context,
        NotificationEventVariables $variables,
        ChannelInterface $syliusChannel,
        array $actions,
    ): void {
        foreach ($actions as $action) {
            $this->messageBus->dispatch(new RunNotificationAction(
                context: $context,
                action: $action,
                variables: $variables,
                syliusChannel: $syliusChannel,
            ));
        }
    }
}
