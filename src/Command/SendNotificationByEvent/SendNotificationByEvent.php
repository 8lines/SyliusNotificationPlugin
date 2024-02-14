<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Command\SendNotificationByEvent;

use EightLines\SyliusNotificationPlugin\Applicator\NotificationVariablesApplicatorInterface;
use EightLines\SyliusNotificationPlugin\Entity\NotificationActionInterface;
use EightLines\SyliusNotificationPlugin\Entity\NotificationInterface;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationBody;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationRecipient;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationContext as NotificationChannelContext;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationContext as NotificationEventContext;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventPayload;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariables;
use EightLines\SyliusNotificationPlugin\Resolver\NotificationChannelResolverInterface;
use EightLines\SyliusNotificationPlugin\Resolver\NotificationEventResolverInterface;
use EightLines\SyliusNotificationPlugin\Resolver\NotificationResolverInterface;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Core\Model\CustomerInterface;

final class SendNotificationByEvent implements SendNotificationByEventInterface
{
    public function __construct(
        private NotificationResolverInterface $notificationResolver,
        private NotificationEventResolverInterface $notificationEventResolver,
        private NotificationChannelResolverInterface $notificationChannelResolver,
        private NotificationVariablesApplicatorInterface $notificationVariablesApplicator,
        private LoggerInterface $logger,
    ) {
    }

    public function sendNotificationByEvent(
        string $eventCode,
        mixed $subject,
    ): void {
        $notificationEvent = $this->notificationEventResolver->resolveByEventCode(
            eventCode: $eventCode,
        );

        if (null === $notificationEvent) {
            $this->logger->debug(
                message: 'No notification event found for event code',
                context: ['eventCode' => $eventCode],
            );

            return;
        }

        $notificationEventContext = NotificationEventContext::create(
            subject: $subject,
            event: $notificationEvent,
        );

        $notificationPayload = $notificationEvent->getEventPayload(
            context: $notificationEventContext,
        );

        $syliusChannelCode = $notificationPayload->getSyliusChannel()?->getCode();

        $notifications = null === $syliusChannelCode
            ? $this->notificationResolver->resolveByEventCode($eventCode)
            : $this->notificationResolver->resolveByEventCodeAndSyliusChannelCode(
                eventCode: $eventCode,
                channelCode: $syliusChannelCode,
            );

        if (0 === \count($notifications)) {
            $this->logger->debug(
                message: 'No notifications found for event code and Sylius channel code',
                context: ['eventCode' => $eventCode, 'syliusChannelCode' => $syliusChannelCode],
            );

            return;
        }

        $notificationVariables = $notificationEvent->getVariables(
            context: $notificationEventContext,
        );

        /** @var array<integer, array<integer, NotificationActionInterface>> $notificationActions */
        $notificationActions = array_map(
            callback: fn(NotificationInterface $item): array => $item->getActions()->toArray(),
            array: $notifications,
        );

        $this->handleNotificationActions(
            context: $notificationEventContext,
            payload: $notificationPayload,
            variables: $notificationVariables,
            actions: array_merge(...$notificationActions),
        );
    }

    public function runNotificationAction(
        NotificationActionInterface $notificationAction,
        NotificationEventContext $notificationEventContext,
        NotificationEventPayload $notificationEventPayload,
        NotificationEventVariables $notificationVariables,
    ): void {
        $notificationChannelCode = $notificationAction->getChannelCode();

        if (null === $notificationChannelCode) {
            $this->logger->debug(
                message: 'No notification channel code found for notification action',
                context: ['notificationAction' => $notificationAction->getId()],
            );

            return;
        }

        $notificationChannel = $this->notificationChannelResolver->resolveByCode(
            code: $notificationChannelCode,
        );

        if (null === $notificationChannel) {
            $this->logger->debug(
                message: 'No notification channel found for notification action',
                context: ['notificationAction' => $notificationAction->getId()],
            );

            return;
        }

        $notificationConfiguration = $notificationAction->getConfiguration();

        if (false === $notificationConfiguration->hasAnyRecipients()
            && false === $notificationChannel::supportsUnknownRecipient()
        ) {
            $this->logger->debug(
                message: 'No recipients found for notification action',
                context: ['notificationAction' => $notificationAction->getId()],
            );

            return;
        }

        $notificationEvent = $notificationEventContext->getEvent();

        $syliusInvoker = $notificationEventPayload->getSyliusInvoker();
        $syliusChannel = $notificationEventPayload->getSyliusChannel();

        $primaryRecipientLocaleCode = $notificationEventPayload->getLocaleCode();

        $primaryNotificationRecipient = $syliusInvoker instanceof CustomerInterface
            ? NotificationRecipient::createFromCustomer($syliusInvoker, true, $primaryRecipientLocaleCode)
            : NotificationRecipient::createFromAdminUser($syliusInvoker, true, $primaryRecipientLocaleCode);

        $notificationChannelContext = NotificationChannelContext::create(
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
                context: $notificationChannelContext,
                recipient: $primaryNotificationRecipient,
            );
        }

        if (true === $notificationConfiguration->hasAdditionalRecipients()) {
            $additionalRecipients = array_map(
                callback: fn (AdminUserInterface $adminUser) => NotificationRecipient::createFromAdminUser($adminUser),
                array: $notificationConfiguration->getAdditionalRecipients()->toArray(),
            );

            $this->sendNotificationToAdditionalRecipients(
                context: $notificationChannelContext,
                recipients: $additionalRecipients,
            );
        }

        if (true === $notificationChannel::supportsUnknownRecipient()) {
            $this->sendNotificationToUnknownRecipient(
                context: $notificationChannelContext,
            );
        }
    }

    /**
     * @throws \Exception
     */
    public function sendNotificationToRecipient(
        ?NotificationRecipient $recipient,
        NotificationChannelContext $notificationContext,
    ): void {
        $notificationConfiguration = $notificationContext->getConfiguration();
        $notificationContent = $notificationConfiguration->getContent();

        $localeCode = $recipient?->getLocaleCode()
            ?? $notificationContext->getSyliusChannel()?->getDefaultLocale()?->getCode();

        $notificationSubject = null === $localeCode
            ? $notificationContent->getSubject()
            : $notificationContent->getSubjectByLocaleCode($localeCode);

        $notificationMessage = null === $localeCode
            ? $notificationContent->getMessage()
            : $notificationContent->getMessageByLocaleCode($localeCode);

        $notificationVariables = $notificationContext->getVariables();

        if (null !== $notificationSubject) {
            $notificationSubject = $this->notificationVariablesApplicator->apply(
                content: $notificationSubject,
                variables: $notificationVariables,
                definitions: $notificationContext->getEvent()->getVariableDefinitions(),
            );
        }

        if (null !== $notificationMessage) {
            $notificationMessage = $this->notificationVariablesApplicator->apply(
                content: $notificationMessage,
                variables: $notificationVariables,
                definitions: $notificationContext->getEvent()->getVariableDefinitions(),
            );
        }

        $notificationBody = NotificationBody::create(
            subject: $notificationSubject,
            message: $notificationMessage,
        );

        $notificationChannel = $notificationContext->getChannel();

        try {
            $notificationChannel->send(
                recipient: $recipient,
                body: $notificationBody,
                context: $notificationContext,
            );

        } catch (\Exception $exception) {
            $this->logger->error(
                message: $exception->getMessage(),
                context: ['recipient' => $recipient, 'context' => $notificationContext],
            );

            throw $exception;
        }
    }

    /**
     * @param NotificationActionInterface[] $actions
     */
    private function handleNotificationActions(
        NotificationEventContext $context,
        NotificationEventPayload $payload,
        NotificationEventVariables $variables,
        array $actions,
    ): void {
        foreach ($actions as $action) {
            $this->runNotificationAction(
                notificationAction: $action,
                notificationEventContext: $context,
                notificationEventPayload: $payload,
                notificationVariables: $variables,
            );
        }
    }

    private function sendNotificationToPrimaryRecipient(
        NotificationChannelContext $context,
        NotificationRecipient $recipient,
    ): void {
        $this->sendNotificationToRecipient(
            recipient: $recipient,
            notificationContext: $context,
        );
    }

    /**
     * @param NotificationRecipient[] $recipients
     */
    private function sendNotificationToAdditionalRecipients(
        NotificationChannelContext $context,
        array $recipients,
    ): void {
        foreach ($recipients as $recipient) {
            $this->sendNotificationToRecipient(
                recipient: $recipient,
                notificationContext: $context,
            );
        }
    }

    private function sendNotificationToUnknownRecipient(
        NotificationChannelContext $context,
    ): void {
        $this->sendNotificationToRecipient(
            recipient: null,
            notificationContext: $context,
        );
    }
}
