<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Processor;

use EightLines\SyliusCartLinksPlugin\Action\AddProductVariantActionCartLinkCommand;
use EightLines\SyliusCartLinksPlugin\Applicator\CartLinkActionApplicatorInterface;
use EightLines\SyliusCartLinksPlugin\Entity\CartLinkInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Context\CartContextInterface;

final class CartLinkProcessor implements CartLinkProcessorInterface
{
    public function __construct(
        private CartContextInterface $cartContext,
        private CartLinkActionApplicatorInterface $cartLinkActionApplicator,
    ) { }

    public function process(OrderInterface $order, CartLinkInterface $cartLink): void
    {
        // Applicator has to iterate over Actions
        // foreach ($cartLink->getActions();
        // For each action we run ActionCommand
        // eg. AddProductVariantActionCartLinkCommand

    }
}
