<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Applicator;

use Doctrine\ORM\EntityManagerInterface;
use EightLines\SyliusCartLinksPlugin\Action\AddProductVariantActionCartLinkCommand;
use EightLines\SyliusCartLinksPlugin\Action\ApplyPromotionActionCartLinkCommand;
use EightLines\SyliusCartLinksPlugin\Entity\CartLinkActionInterface;
use EightLines\SyliusCartLinksPlugin\Entity\CartLinkInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;

final class CartLinkActionApplicator implements CartLinkActionApplicatorInterface
{
    public function __construct(
        private AddProductVariantActionCartLinkCommand $addProductVariantCommand,
        private ApplyPromotionActionCartLinkCommand $applyPromotionCommand,
        private EntityManagerInterface $entityManager,
        private OrderProcessorInterface $orderProcessor,
    ) { }

    public function apply(OrderInterface $order, CartLinkInterface $cartLink): void
    {
        if (true === $cartLink->getEmptyCart()) {
            $order->clearItems();
            // TODO: handle remove promotion (?)
        }

        foreach ($cartLink->getActions() as $action) {
            $this->handleAction($action, $order);
        }

        $this->orderProcessor->process($order);
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    private function handleAction(CartLinkActionInterface $action, OrderInterface $order): void {
        if ('add_product_variant' === $action->getType()) {
            $this->addProductVariantCommand->execute($order, $action->getConfiguration());

        } elseif ('apply_promotion' === $action->getType()) {
            $this->applyPromotionCommand->execute($order, $action->getConfiguration());
        }
    }
}
