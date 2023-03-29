<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Entity;

use Doctrine\Common\Collections\Collection;

interface ActionsAwareInterface
{
    /**
     * @return Collection|CartLinkActionInterface[]
     *
     * @psalm-return Collection<array-key, CartLinkActionInterface>
     */
    public function getActions(): Collection;

    public function addAction(CartLinkActionInterface $action): void;

    public function hasAction(CartLinkActionInterface $action): bool;

    public function removeAction(CartLinkActionInterface $action): void;
}
