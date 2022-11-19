<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Applicator;

use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariableDefinitions;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariables;

interface NotificationVariablesApplicatorInterface
{
    public function apply(
        string $content,
        NotificationEventVariables $variables,
        NotificationEventVariableDefinitions $definitions,
    ): string;
}
