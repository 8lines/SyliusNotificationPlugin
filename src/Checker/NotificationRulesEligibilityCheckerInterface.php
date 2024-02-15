<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Checker;

use EightLines\SyliusNotificationPlugin\Entity\NotificationInterface;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariables;

interface NotificationRulesEligibilityCheckerInterface
{
    public function isEligible(
        NotificationInterface $notification,
        NotificationEventVariables $notificationVariables,
    ): bool;
}
