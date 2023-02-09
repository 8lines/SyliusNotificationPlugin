<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Action;

use EightLines\SyliusCartLinksPlugin\Entity\CartLinkInterface;

final class AddProductVariantActionCartLinkCommand implements CartLinkActionCommandInterface
{
    public function execute($subject, array $actionConfiguration, CartLinkInterface $cartLink): bool
    {
        return true;
    }

    public function revert($subject, array $configuration, CartLinkInterface $cartLink): void
    {
        return;
    }
}
