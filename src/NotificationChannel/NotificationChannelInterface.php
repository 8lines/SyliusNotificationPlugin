<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationChannel;

use Sylius\Component\Core\Model\CustomerInterface;

interface NotificationChannelInterface
{
    public function send(
        CustomerInterface $recipient,
        string $message,
        NotificationContext $context,
    ): void;


    public static function getIdentifier(): string;

    public static function getConfigurationFormType(): ?string;

    public static function supports(): bool;
}
