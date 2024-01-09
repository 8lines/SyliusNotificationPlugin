<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Command\Handler;

use EightLines\SyliusNotificationPlugin\Command\RunNotificationActionCommand;
use EightLines\SyliusNotificationPlugin\Command\SendNotificationToRecipientCommand;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationContext;
use EightLines\SyliusNotificationPlugin\Resolver\NotificationChannelResolverInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class RunNotificationActionCommandHandler
{
    public function __construct(
        private NotificationChannelResolverInterface $notificationChannelResolver,
        private MessageBusInterface $commandBus,
    ) {
    }

    public function __invoke(RunNotificationActionCommand $command): void {
        $notificationAction = $command->getAction();

        if (false === $notificationAction->hasAnyRecipients()) {
            return;
        }

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

        $notificationEvent = $command->getContext()->getEvent();
        $notificationVariables = $command->getVariables();

        $notificationContext = NotificationContext::create(
            action: $notificationAction,
            event: $command->getContext()->getEvent(),
            channel: $notificationChannel,
            variables: $notificationVariables,
            configuration: $notificationAction->getConfiguration(),
            syliusChannel: $command->getSyliusChannel(),
            eventLevelContext: $command->getContext(),
        );

        if ($notificationAction->isNotifyPrimaryRecipient()) {
            $this->sendNotificationToPrimaryRecipient(
                context: $notificationContext,
                recipient: $notificationEvent->getPrimaryRecipient($command->getContext()),
            );
        }

        $this->sendNotificationToAdditionalRecipients(
            context: $notificationContext,
            recipients: $notificationAction->getAdditionalRecipients()->toArray(),
        );
    }

    private function sendNotificationToPrimaryRecipient(
        NotificationContext $context,
        CustomerInterface $recipient,
    ): void
    {
        $this->commandBus->dispatch(new SendNotificationToRecipientCommand(
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
            $this->commandBus->dispatch(new SendNotificationToRecipientCommand(
                recipient: $recipient,
                primaryRecipient: false,
                context: $context,
            ));
        }
    }
}
