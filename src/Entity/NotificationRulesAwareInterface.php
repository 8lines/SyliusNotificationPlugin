<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Doctrine\Common\Collections\Collection;

interface NotificationRulesAwareInterface
{
    /**
     * @return Collection<array-key, NotificationRuleInterface>
     */
    public function getRules(): Collection;

    public function hasRules(): bool;

    public function addRule(NotificationRuleInterface $rule): void;

    public function hasRule(NotificationRuleInterface $rule): bool;

    public function removeRule(NotificationRuleInterface $rule): void;
}
