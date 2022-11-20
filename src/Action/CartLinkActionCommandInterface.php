<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Action;

use EightLines\SyliusCartLinksPlugin\Entity\CartLinkInterface;

interface CartLinkActionCommandInterface
{
    public function execute($subject, array $configuration, CartLinkInterface $cartLink): bool;

    public function revert($subject, array $configuration, CartLinkInterface $cartLink): void;
}
