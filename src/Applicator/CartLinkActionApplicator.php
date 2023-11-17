<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Applicator;

use Doctrine\ORM\EntityManagerInterface;
use EightLines\SyliusNotificationPlugin\Action\AddProductVariantActionCartLinkCommand;
use EightLines\SyliusNotificationPlugin\Action\ApplyRandomPromotionCouponActionCartLinkCommand;
use EightLines\SyliusNotificationPlugin\Action\ApplySpecifiedPromotionCouponActionCartLinkCommand;
use EightLines\SyliusNotificationPlugin\Entity\CartLinkActionInterface;
use EightLines\SyliusNotificationPlugin\Entity\CartLinkInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;

final class CartLinkActionApplicator implements CartLinkActionApplicatorInterface
{
    public function __construct(
        private AddProductVariantActionCartLinkCommand $addProductVariantCommand,
        private ApplyRandomPromotionCouponActionCartLinkCommand $applyRandomPromotionCouponCommand,
        private ApplySpecifiedPromotionCouponActionCartLinkCommand $applySpecifiedPromotionCouponCommand,
        private EntityManagerInterface $entityManager,
        private OrderProcessorInterface $orderProcessor,
    ) { }

    public function apply(OrderInterface $order, CartLinkInterface $cartLink): void
    {
        if (true === $cartLink->getEmptyCart()) {
            $this->processEmptyCart($order);
        }

        foreach ($cartLink->getRules() as $action) {
            $this->handleAction($action, $order);
        }

        $cartLink->incrementUsed();

        $this->orderProcessor->process($order);
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    private function processEmptyCart(OrderInterface $order): void
    {
        $order->clearItems();
        $order->setPromotionCoupon(null);

        foreach ($order->getPromotions() as $promotionItem) {
            $order->removePromotion($promotionItem);
        }
    }

    private function handleAction(CartLinkActionInterface $action, OrderInterface $order): void
    {
        if ('add_product_variant' === $action->getType()) {
            $this->addProductVariantCommand->execute($order, $action->getConfiguration());

        } elseif ('apply_specified_promotion_coupon' === $action->getType()) {
            $this->applySpecifiedPromotionCouponCommand->execute($order, $action->getConfiguration());

        } elseif ('apply_random_promotion_coupon' === $action->getType()) {
            $this->applyRandomPromotionCouponCommand->execute($order, $action->getConfiguration());
        }
    }
}
