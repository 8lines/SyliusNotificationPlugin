<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationChannel;

final class NotificationBody
{
    public function __construct(
        private ?string $subject,
        private ?string $message,
    ) {
    }

    public static function create(
        ?string $subject,
        ?string $message,
    ): self {
        return new self(
            subject: $subject,
            message: $message,
        );
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function getMessage(): ?string
    {
        return $this->message;
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
