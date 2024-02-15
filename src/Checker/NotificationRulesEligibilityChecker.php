<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Checker;

use EightLines\SyliusNotificationPlugin\Entity\NotificationInterface;
use EightLines\SyliusNotificationPlugin\Entity\NotificationRuleInterface;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariables;
use EightLines\SyliusNotificationPlugin\Resolver\ComparatorResolverInterface;

final class NotificationRulesEligibilityChecker implements NotificationRulesEligibilityCheckerInterface
{
    public function __construct(
        private ComparatorResolverInterface $comparatorResolver
    ) {
    }

    public function isEligible(
        NotificationInterface $notification,
        NotificationEventVariables $notificationVariables,
    ): bool {
        if (false === $notification->hasRules()) {
            return true;
        }

        foreach ($notification->getRules() as $rule) {
            if (false === $this->isEligibleToRule($rule, $notificationVariables)) {
                return false;
            }
        }

        return true;
    }

    private function isEligibleToRule(
        NotificationRuleInterface $rule,
        NotificationEventVariables $notificationVariables,
    ): bool {
        $comparatorType = $rule->getComparatorType();
        $variableName = $rule->getVariableName();

        if (true === empty($comparatorType) || true === empty($variableName)) {
            return true;
        }

        $comparator = $this->comparatorResolver->resolve(
            comparatorType: $comparatorType,
        );

        $subject = $notificationVariables->getByName(
            name: $variableName,
        )?->getValue()?->value();

        return $comparator->isSatisfiedBy(
            subject: $subject,
            value: $rule->getComparableValue(),
        );
    }
}
