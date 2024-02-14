<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Command\Handler;

use EightLines\SyliusNotificationPlugin\Command\RunNotificationActionCommand;
use EightLines\SyliusNotificationPlugin\Command\SendNotificationToRecipientCommand;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationContext;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationRecipient;
use EightLines\SyliusNotificationPlugin\Resolver\NotificationChannelResolverInterface;
use Sylius\Component\Core\Model\AdminUserInterface;
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

        $notificationEventContext = $command->getContext();

        $notificationEvent = $notificationEventContext->getEvent();
        $notificationVariables = $command->getVariables();

        $primaryRecipient = $notificationEvent->getPrimaryRecipient(
            context: $notificationEventContext,
        );

        $syliusInvoker = $primaryRecipient->getSyliusInvoker();
        $syliusChannel = $command->getSyliusChannel();

        $primaryNotificationRecipient = $syliusInvoker instanceof CustomerInterface
            ? NotificationRecipient::createFromCustomer($syliusInvoker)
            : NotificationRecipient::createFromAdminUser($syliusInvoker);

        $notificationContext = NotificationContext::create(
            action: $notificationAction,
            event: $notificationEvent,
            channel: $notificationChannel,
            variables: $notificationVariables,
            configuration: $notificationConfiguration,
            syliusChannel: $syliusChannel,
            syliusInvoker: $syliusInvoker,
            eventLevelContext: $notificationEventContext,
        );

        if (true === $notificationConfiguration->isNotifyPrimaryRecipient()) {
            $this->sendNotificationToPrimaryRecipient(
                context: $notificationContext,
                recipient: $primaryNotificationRecipient,
            );
        }

        if (true === $notificationConfiguration->hasAdditionalRecipients()) {
            $additionalRecipients = array_map(
                callback: fn (AdminUserInterface $adminUser) => NotificationRecipient::createFromAdminUser($adminUser),
                array: $notificationConfiguration->getAdditionalRecipients()->toArray(),
            );

            $this->sendNotificationToAdditionalRecipients(
                context: $notificationContext,
                recipients: $additionalRecipients,
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
        NotificationRecipient $recipient,
    ): void {
        $this->commandBus->dispatch(new SendNotificationToRecipientCommand(
            recipient: $recipient,
            primaryRecipient: true,
            context: $context,
        ));
    }

    /**
     * @param NotificationRecipient[] $recipients
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

    private function sendNotificationToUnknownRecipient(
        NotificationContext $context,
    ): void {
        $this->commandBus->dispatch(new SendNotificationToRecipientCommand(
            recipient: null,
            primaryRecipient: false,
            context: $context,
        ));
    }
}
