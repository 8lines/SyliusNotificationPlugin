<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Command;

use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationContext;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationRecipient;

final class SendNotificationToRecipientCommand
{
    public function __construct(
        private ?NotificationRecipient $recipient,
        private bool $primaryRecipient,
        private NotificationContext $context,
    ) {
    }

    public function getRecipient(): ?NotificationRecipient
    {
        return $this->recipient;
    }

    public function isPrimaryRecipient(): bool
    {
        return $this->primaryRecipient;
    }

    public function getContext(): NotificationContext
    {
        return $this->context;
    }
}
