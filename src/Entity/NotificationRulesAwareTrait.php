<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

trait NotificationRulesAwareTrait
{
    /**
     * @var Collection<array-key, NotificationRuleInterface>
     */
    private Collection $rules;

    public function initializeRulesCollection(): void
    {
        $this->rules= new ArrayCollection();
    }

    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function hasRules(): bool
    {
        return false === $this->rules->isEmpty();
    }

    public function addRule(NotificationRuleInterface $rule): void
    {
        if (true === $this->hasRule($rule)) {
            return;
        }

        $this->rules->add($rule);
    }

    public function hasRule(NotificationRuleInterface $rule): bool
    {
        return $this->rules->contains($rule);
    }

    public function removeRule(NotificationRuleInterface $rule): void
    {
        if (false === $this->hasRule($rule)) {
            return;
        }

        $this->rules->removeElement($rule);
    }
}
