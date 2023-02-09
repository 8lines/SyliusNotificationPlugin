<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Processor;

use EightLines\SyliusCartLinksPlugin\Entity\CartLinkInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface CartLinkProcessorInterface
{
    public function process(OrderInterface $order, CartLinkInterface $cartLink): void;
}
