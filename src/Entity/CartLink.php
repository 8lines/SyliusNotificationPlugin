<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;

final class CartLink implements CartLinkInterface
{
    use TimestampableTrait;

    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

    private ?int $id = null;

    private ?string $code = null;

    private bool $emptyCart = false;

    /**
     * @var Collection|ChannelInterface[]
     *
     * @psalm-var Collection<array-key, ChannelInterface>
     */
    private Collection $channels;

    /**
     * @var Collection|CartLinkActionInterface[]
     *
     * @psalm-var Collection<array-key, CartLinkInterface>
     */
    private Collection $actions;

    public function __construct()
    {
        $this->initializeTranslationsCollection();

        $this->channels = new ArrayCollection();
        $this->actions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getEmptyCart(): bool
    {
        return $this->emptyCart;
    }

    public function setEmptyCart(bool $emptyCart): void
    {
        $this->emptyCart = $emptyCart;
    }

    public function getChannels(): Collection
    {
        return $this->channels;
    }

    public function hasChannel(ChannelInterface $channel): bool
    {
        return $this->channels->contains($channel);
    }

    public function addChannel(ChannelInterface $channel): void
    {
        if ($this->hasChannel($channel)) {
            return;
        }

        $this->channels->add($channel);
    }

    public function removeChannel(ChannelInterface $channel): void
    {
        if (!$this->hasChannel($channel)) {
            return;
        }

        $this->channels->add($channel);
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

        $action->setCartLink($this);

        $this->actions->add($action);
    }

    public function removeAction(CartLinkActionInterface $action): void
    {
        if (!$this->hasAction($action)) {
            return;
        }

        $this->actions->removeElement($action);
    }

    public function hasAction(CartLinkActionInterface $action): bool
    {
        return $this->actions->contains($action);
    }

    protected function createTranslation(): TranslationInterface
    {
        return new CartLinkTranslation();
    }
}
