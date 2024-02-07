<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationChannel;

interface NotificationChannelInterface
{
    public function send(
        ?NotificationRecipient $recipient,
        NotificationBody $body,
        NotificationContext $context,
    ): void;

    public static function getIdentifier(): string;

    public static function supportsUnknownRecipient(): bool;

    public static function getConfigurationFormType(): ?string;

    public static function supports(): bool;
}
