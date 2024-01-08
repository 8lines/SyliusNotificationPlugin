<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Applicator;

use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariable;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariableDefinitions;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariables;

final class NotificationVariablesApplicator implements NotificationVariablesApplicatorInterface
{
    public function apply(
        string $content,
        NotificationEventVariables $variables,
        NotificationEventVariableDefinitions $definitions,
    ): string {
        /** @var NotificationEventVariable $variable */
        foreach ($variables as $variable) {
            $value = $variable->getValue()->isNull()
                ? $definitions->getByName($variable->getName())->getDefaultValue()
                : $variable->getValue();

            $content = str_replace(
                search: '{' . $variable->getName() . '}',
                replace: $value->valueAsString(),
                subject: $content,
            );
        }

        return $content;
    }
}
