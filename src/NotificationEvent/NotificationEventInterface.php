<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

use Sylius\Component\Core\Model\ChannelInterface;

interface NotificationEventInterface
{
    public static function getEventName(): string;

    public static function getConfigurationFormType(): ?string;

    public function getVariables(mixed $subject): NotificationEventVariables;

    public function getVariableDefinitions(): NotificationEventVariableDefinitions;

    public function getEventChannel(mixed $subject): ?ChannelInterface;

    public function getEventLocaleCode(mixed $subject): ?string;
}
