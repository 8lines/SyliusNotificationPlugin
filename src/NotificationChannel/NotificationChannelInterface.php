<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationChannel;

interface NotificationChannelInterface
{
    public function send(
        string $message,
        NotificationContext $context,
    ): void;

    public static function getIdentifier(): string;

    public static function supports(): bool;
}
