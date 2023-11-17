<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Processor;

use EightLines\SyliusNotificationPlugin\Applicator\CartLinkActionApplicatorInterface;
use EightLines\SyliusNotificationPlugin\Entity\CartLinkInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class CartLinkProcessor implements CartLinkProcessorInterface
{
    public function __construct(
        private CartLinkActionApplicatorInterface $cartLinkActionApplicator,
    ) { }

    public function process(OrderInterface $order, CartLinkInterface $cartLink): void
    {
        $this->cartLinkActionApplicator->apply($order, $cartLink);
    }
}
