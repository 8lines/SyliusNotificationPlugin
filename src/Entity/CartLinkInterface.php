<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Entity;

use Sylius\Component\Channel\Model\ChannelsAwareInterface;
use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface CartLinkInterface extends
    ResourceInterface,
    CodeAwareInterface,
    TranslatableInterface,
    ToggleableInterface,
    TimestampableInterface,
    ChannelsAwareInterface,
    ActionsAwareInterface
{
    public function getEmptyCart(): bool;

    public function setEmptyCart(bool $emptyCart): void;

    public function getUsageLimit(): ?int;

    public function setUsageLimit(?int $usageLimit): void;

    public function getUsed(): int;

    public function setUsed(int $used): void;

    public function incrementUsed(): void;

    public function decrementUsed(): void;

    public function getStartsAt(): ?\DateTimeInterface;

    public function setStartsAt(?\DateTimeInterface $startsAt): void;

    public function getEndsAt(): ?\DateTimeInterface;

    public function setEndsAt(?\DateTimeInterface $endsAt): void;

    public function setSlug(string $slug): void;

    public function setName(string $name): void;
}
