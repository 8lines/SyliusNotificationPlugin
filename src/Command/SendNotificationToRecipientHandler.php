<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Command;

use EightLines\SyliusNotificationPlugin\Applicator\NotificationVariablesApplicatorInterface;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationBody;

final class SendNotificationToRecipientHandler
{
    public function __construct(
        private NotificationVariablesApplicatorInterface $notificationVariablesApplicator,
    ) {
    }

    public function __invoke(SendNotificationToRecipient $command): void
    {
        $notificationEvent = $command->getContext()->getEvent();
        $notificationConfiguration = $command->getContext()->getConfiguration();
        $notificationContent = $notificationConfiguration->getContent();

        $localeCode = $command->isPrimaryRecipient()
            ? $notificationEvent->getPrimaryRecipientLocaleCode($command->getContext()->getEventLevelContext())
            : $command->getContext()->getSyliusChannel()->getDefaultLocale()?->getCode();

        $notificationSubject = null === $localeCode
            ? $notificationContent->getSubject()
            : $notificationContent->getSubjectByLocaleCode($localeCode);

        $notificationMessage = null === $localeCode
            ? $notificationContent->getMessage()
            : $notificationContent->getMessageByLocaleCode($localeCode);

        $notificationVariables = $command->getContext()->getVariables();

        if (null !== $notificationSubject) {
            $notificationSubject = $this->notificationVariablesApplicator->apply(
                content: $notificationSubject,
                variables: $notificationVariables,
                definitions: $command->getContext()->getEvent()->getVariableDefinitions(),
            );
        }

        if (null !== $notificationMessage) {
            $notificationMessage = $this->notificationVariablesApplicator->apply(
                content: $notificationMessage,
                variables: $notificationVariables,
                definitions: $command->getContext()->getEvent()->getVariableDefinitions(),
            );
        }

        $notificationBody = NotificationBody::create(
            recipient: $command->getRecipient(),
            subject: $notificationSubject,
            message: $notificationMessage,
        );

        $notificationChannel = $command->getContext()->getChannel();
        $notificationChannel->send(
            body: $notificationBody,
            context: $command->getContext(),
        );
    }
}
