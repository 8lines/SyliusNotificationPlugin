<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Command;

use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationContext;
use EightLines\SyliusNotificationPlugin\Resolver\NotificationChannelResolverInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class RunNotificationActionHandler
{
    public function __construct(
        private NotificationChannelResolverInterface $notificationChannelResolver,
        private MessageBusInterface $commandBus,
    ) {
    }

    public function __invoke(RunNotificationAction $command): void {
        $notificationAction = $command->getAction();
        $notificationChannelCode = $notificationAction->getChannelCode();

        if (null === $notificationChannelCode) {
            return;
        }

        $notificationChannel = $this->notificationChannelResolver->resolveByCode(
            code: $notificationChannelCode,
        );

        if (null === $notificationChannel) {
            return;
        }

        $notificationConfiguration = $notificationAction->getConfiguration();

        if (false === $notificationConfiguration->hasAnyRecipients()
            && false === $notificationChannel::supportsUnknownRecipient()
        ) {
            return;
        }

        $notificationEvent = $command->getContext()->getEvent();
        $notificationVariables = $command->getVariables();

        $notificationContext = NotificationContext::create(
            action: $notificationAction,
            event: $command->getContext()->getEvent(),
            channel: $notificationChannel,
            variables: $notificationVariables,
            configuration: $notificationConfiguration,
            syliusChannel: $command->getSyliusChannel(),
            eventLevelContext: $command->getContext(),
        );

        if (true === $notificationConfiguration->isNotifyPrimaryRecipient()) {
            $this->sendNotificationToPrimaryRecipient(
                context: $notificationContext,
                recipient: $notificationEvent->getPrimaryRecipient($command->getContext()),
            );
        }

        if (true === $notificationConfiguration->hasAdditionalRecipients()) {
            $this->sendNotificationToAdditionalRecipients(
                context: $notificationContext,
                recipients: $notificationConfiguration->getAdditionalRecipients()->toArray(),
            );
        }

        if (true === $notificationChannel::supportsUnknownRecipient()) {
            $this->sendNotificationToUnknownRecipient(
                context: $notificationContext,
            );
        }
    }

    private function sendNotificationToPrimaryRecipient(
        NotificationContext $context,
        CustomerInterface $recipient,
    ): void {
        $this->commandBus->dispatch(new SendNotificationToRecipient(
            recipient: $recipient,
            primaryRecipient: true,
            context: $context,
        ));
    }

    /**
     * @param CustomerInterface[] $recipients
     */
    private function sendNotificationToAdditionalRecipients(
        NotificationContext $context,
        array $recipients,
    ): void {
        foreach ($recipients as $recipient) {
            $this->commandBus->dispatch(new SendNotificationToRecipient(
                recipient: $recipient,
                primaryRecipient: false,
                context: $context,
            ));
        }
    }

    private function sendNotificationToUnknownRecipient(
        NotificationContext $context,
    ): void {
        $this->commandBus->dispatch(new SendNotificationToRecipient(
            recipient: null,
            primaryRecipient: false,
            context: $context,
        ));
    }
}
