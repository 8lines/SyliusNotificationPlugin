<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

trait NotificationActionsAwareTrait
{
    /**
     * @var Collection|NotificationActionInterface[]
     *
     * @psalm-var Collection<array-key, NotificationActionInterface>
     */
    private Collection $actions;

    public function initializeActionsCollection(): void
    {
        $this->actions = new ArrayCollection();
    }

    public function getActions(): Collection
    {
        return $this->actions;
    }

    public function addAction(NotificationActionInterface $action): void
    {
        if ($this->hasAction($action)) {
            return;
        }

        $this->actions->add($action);
    }

    public function hasAction(NotificationActionInterface $action): bool
    {
        return $this->actions->contains($action);
    }

    public function removeAction(NotificationActionInterface $action): void
    {
        if (!$this->hasAction($action)) {
            return;
        }

        $this->actions->removeElement($action);
    }
}
