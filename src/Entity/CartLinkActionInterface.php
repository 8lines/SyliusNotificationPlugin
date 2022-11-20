<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

interface CartLinkActionInterface extends ResourceInterface
{
    public function getType(): string;

    public function setType(string $type): void;

    public function getConfiguration(): array;

    public function setConfiguration(array $configuration): void;

    public function getCartLink(): CartLinkInterface;

    public function setCartLink(CartLinkInterface $cartLink): void;
}
