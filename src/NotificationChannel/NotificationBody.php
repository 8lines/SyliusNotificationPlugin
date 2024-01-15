<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationChannel;

use Sylius\Component\Core\Model\CustomerInterface;

final class NotificationBody
{
    public function __construct(
        private ?CustomerInterface $recipient,
        private ?string $subject,
        private ?string $message,
    ) {
    }

    public static function create(
        ?CustomerInterface $recipient,
        ?string $subject,
        ?string $message,
    ): self {
        return new self(
            recipient: $recipient,
            subject: $subject,
            message: $message,
        );
    }

    public function getRecipient(): ?CustomerInterface
    {
        return $this->recipient;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function hasRecipient(): bool
    {
        return $this->recipient !== null;
    }

    public function hasSubject(): bool
    {
        return $this->subject !== null;
    }

    public function hasMessage(): bool
    {
        return $this->message !== null;
    }
}
