<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Entity;

final class CartLinkUsage implements CartLinkUsageInterface
{
    private int $id;

    private CartLinkInterface $cartLink;

    private \DateTimeImmutable $usedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCartLink(): CartLinkInterface
    {
        return $this->cartLink;
    }

    public function setCartLink(CartLinkInterface $cartLink): void
    {
        $this->cartLink = $cartLink;
    }

    public function getUsedAt(): \DateTimeImmutable
    {
        return $this->usedAt;
    }

    public function setUsedAt(\DateTimeImmutable $usedAt): void
    {
        $this->usedAt = $usedAt;
    }
}
