<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Command\SendNotificationByEvent;

use EightLines\SyliusNotificationPlugin\Entity\NotificationActionInterface;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationContext as NotificationChannelContext;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationRecipient;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationContext as NotificationEventContext;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventInvoker;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventPayload;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariables;

interface SendNotificationByEventInterface
{
    public function sendNotificationByEvent(
        string $eventCode,
        mixed $subject,
        ?NotificationEventInvoker $invoker,
    ): void;

    public function runNotificationAction(
        NotificationActionInterface $notificationAction,
        NotificationEventContext $notificationEventContext,
        NotificationEventPayload $notificationEventPayload,
        NotificationEventVariables $notificationVariables,
    ): void;

    public function sendNotificationToRecipient(
        ?NotificationRecipient $recipient,
        NotificationChannelContext $notificationContext,
    ): void;
}
