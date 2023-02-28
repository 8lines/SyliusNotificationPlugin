<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Action;

use Sylius\Component\Core\Factory\CartItemFactoryInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\ProductVariantRepositoryInterface;
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;

final class AddProductVariantActionCartLinkCommand implements CartLinkActionCommandInterface
{
    public function __construct(
        private ProductVariantRepositoryInterface $productVariantRepository,
        private OrderItemQuantityModifierInterface $itemQuantityModifier,
        private CartItemFactoryInterface $cartItemFactory,
        private OrderProcessorInterface $orderProcessor,
    ) { }

    public function execute(OrderInterface $order, array $actionConfiguration): bool
    {
        $productVariants = $this->productVariantRepository->findByCodes($actionConfiguration['product_variants']);

        foreach ($productVariants as $productVariant) {
            $cartItem = $this->cartItemFactory->createForCart($order);
            $cartItem->setVariant($productVariant);
            $this->itemQuantityModifier->modify($cartItem, $cartItem->getQuantity() + 1);
        }

        $this->orderProcessor->process($order);

        return true;
    }
}
