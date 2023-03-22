<?php

namespace EightLines\SyliusCartLinksPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

trait ActionsAwareTrait
{
    /**
     * @var Collection|CartLinkActionInterface[]
     *
     * @psalm-var Collection<array-key, CartLinkInterface>
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

    public function addAction(CartLinkActionInterface $action): void
    {
        if ($this->hasAction($action)) {
            return;
        }

        $this->actions->add($action);
    }

    public function hasAction(CartLinkActionInterface $action): bool
    {
        return $this->actions->contains($action);
    }

    public function removeAction(CartLinkActionInterface $action): void
    {
        if (!$this->hasAction($action)) {
            return;
        }

        $this->actions->removeElement($action);
    }
}
