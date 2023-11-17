<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Processor;

use EightLines\SyliusNotificationPlugin\Entity\CartLinkInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface CartLinkProcessorInterface
{
    public function process(OrderInterface $order, CartLinkInterface $cartLink): void;
}
