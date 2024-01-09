<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Command\Handler;

use EightLines\SyliusNotificationPlugin\Applicator\NotificationVariablesApplicatorInterface;
use EightLines\SyliusNotificationPlugin\Command\SendNotificationToRecipientCommand;

final class SendNotificationToRecipientCommandHandler
{
    public function __construct(
        private NotificationVariablesApplicatorInterface $notificationVariablesApplicator,
    ) {
    }

    public function __invoke(SendNotificationToRecipientCommand $command): void
    {
        $notificationEvent = $command->getContext()->getEvent();
        $notificationAction = $command->getContext()->getAction();

        $localeCode = $command->isPrimaryRecipient()
            ? $notificationEvent->getPrimaryRecipientLocaleCode($command->getContext()->getEventLevelContext())
            : $command->getContext()->getSyliusChannel()->getDefaultLocale()?->getCode();

        $notificationMessage = null === $localeCode
            ? $notificationAction->getMessage()->getContent()
            : $notificationAction->getMessage()->getContentByLocaleCode($localeCode);

        if (null === $notificationMessage) {
            return;
        }

        $notificationChannel = $command->getContext()->getChannel();
        $notificationVariables = $command->getContext()->getVariables();

        $notificationMessage = $this->notificationVariablesApplicator->apply(
            content: $notificationMessage,
            variables: $notificationVariables,
            definitions: $command->getContext()->getEvent()->getVariableDefinitions(),
        );

        $notificationChannel->send(
            recipient: $command->getRecipient(),
            message: $notificationMessage,
            context: $command->getContext(),
        );
    }
}
