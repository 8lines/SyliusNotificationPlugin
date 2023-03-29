<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\ChannelsAwareInterface;
use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface CartLinkInterface extends ResourceInterface, CodeAwareInterface, TranslatableInterface, TimestampableInterface, ChannelsAwareInterface
{
    /**
     * @return Collection|CartLinkActionInterface[]
     *
     * @psalm-return Collection<array-key, CartLinkActionInterface>
     */
    public function getActions(): Collection;

    public function addAction(CartLinkActionInterface $action): void;

    public function removeAction(CartLinkActionInterface $action): void;

    public function hasAction(CartLinkActionInterface $action): bool;

    public function getEmptyCart(): bool;

    public function setEmptyCart(bool $emptyCart): void;
}
