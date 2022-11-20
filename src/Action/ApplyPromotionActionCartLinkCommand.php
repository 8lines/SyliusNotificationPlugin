<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Action;

use EightLines\SyliusCartLinksPlugin\Entity\CartLinkInterface;

final class ApplyPromotionActionCartLinkCommand implements CartLinkActionCommandInterface
{
    public function execute($subject, array $configuration, CartLinkInterface $cartLink): bool
    {
        dump('execute');
        dump($subject);
        return true;
    }

    public function revert($subject, array $configuration, CartLinkInterface $cartLink): void
    {
        dump('revert');
        dump($subject);
        return;
    }
}
