<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Command;

use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationContext;
use Sylius\Component\Core\Model\CustomerInterface;

final class SendNotificationToRecipient
{
    public function __construct(
        private ?CustomerInterface $recipient,
        private bool $primaryRecipient,
        private NotificationContext $context,
    ) {
    }

    public function getRecipient(): ?CustomerInterface
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
