<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

use Sylius\Component\Core\Model\CustomerInterface;

interface NotificationEventInterface
{
    public static function getEventName(): string;

    public static function getConfigurationFormType(): ?string;

    public function getVariables(NotificationContext $context): NotificationEventVariables;

    public function getVariableDefinitions(): NotificationEventVariableDefinitions;

    public function getSyliusRecipient(NotificationContext $context): CustomerInterface;

    public function getSyliusChannelCode(NotificationContext $context): ?string;

    public function getSyliusLocaleCode(NotificationContext $context): ?string;
}
