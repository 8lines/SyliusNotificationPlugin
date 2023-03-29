<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Entity;

use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;

final class CartLink implements CartLinkInterface
{
    use TimestampableTrait;

    use ChannelsAwareTrait;

    use ActionsAwareTrait;

    use ToggleableTrait;

    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

    private ?int $id = null;

    private ?string $code = null;

    private bool $emptyCart = false;

    private ?int $usageLimit;

    private int $used = 0;

    protected ?\DateTimeInterface $startsAt;

    protected ?\DateTimeInterface $endsAt;

    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->initializeChannelsCollection();
        $this->initializeActionsCollection();
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

    protected function createTranslation(): TranslationInterface
    {
        return new CartLinkTranslation();
    }

    public function getUsageLimit(): ?int
    {
        return $this->usageLimit;
    }

    public function setUsageLimit(?int $usageLimit): void
    {
        $this->usageLimit = $usageLimit;
    }

    public function getUsed(): int
    {
        return $this->used;
    }

    public function setUsed(int $used): void
    {
        $this->used = $used;
    }

    public function incrementUsed(): void
    {
        $this->used++;
    }

    public function decrementUsed(): void
    {
        $this->used--;
    }

    public function getStartsAt(): ?\DateTimeInterface
    {
        return $this->startsAt;
    }

    public function setStartsAt(?\DateTimeInterface $startsAt): void
    {
        $this->startsAt = $startsAt;
    }

    public function getEndsAt(): ?\DateTimeInterface
    {
        return $this->endsAt;
    }

    public function setEndsAt(?\DateTimeInterface $endsAt): void
    {
        $this->endsAt = $endsAt;
    }
}
