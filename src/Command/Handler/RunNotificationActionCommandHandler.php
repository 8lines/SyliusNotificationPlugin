<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Command\Handler;

use EightLines\SyliusNotificationPlugin\Applicator\NotificationVariablesApplicatorInterface;
use EightLines\SyliusNotificationPlugin\Command\RunNotificationActionCommand;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationContext;
use EightLines\SyliusNotificationPlugin\Resolver\NotificationChannelResolverInterface;

final class RunNotificationActionCommandHandler
{
    public function __construct(
        private NotificationChannelResolverInterface $notificationChannelResolver,
        private NotificationVariablesApplicatorInterface $notificationVariablesApplicator,
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

        $localeCode = $command
            ->getContext()
            ->getEvent()
            ->getSyliusLocaleCode($command->getContext());

        $notificationMessage = null === $localeCode
            ? $notificationAction->getMessage()->getContent()
            : $notificationAction->getMessage()->getContentByLocaleCode($localeCode);

        if (null === $notificationMessage) {
            return;
        }

        $notificationVariables = $command->getVariables();

        $notificationMessage = $this->notificationVariablesApplicator->apply(
            content: $notificationMessage,
            variables: $notificationVariables,
            definitions: $command->getContext()->getEvent()->getVariableDefinitions(),
        );

        $notificationContext = NotificationContext::create(
            event: $command->getContext()->getEvent(),
            channel: $notificationChannel,
            variables: $notificationVariables,
            configuration: $notificationAction->getConfiguration(),
        );

        $notificationChannel->send(
            message: $notificationMessage,
            context: $notificationContext,
        );
    }
}
