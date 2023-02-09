<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Action;

use EightLines\SyliusCartLinksPlugin\Entity\CartLinkInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class ApplyPromotionActionCartLinkCommand implements CartLinkActionCommandInterface
{
    public function execute(OrderInterface $order, array $actionConfiguration, CartLinkInterface $cartLink): bool
    {
        dump('execute');
        dump($subject);
        return true;
    }
}
