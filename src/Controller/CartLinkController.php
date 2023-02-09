<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Controller;

use Doctrine\ORM\EntityManagerInterface;
use EightLines\SyliusCartLinksPlugin\Entity\CartLinkInterface;
use EightLines\SyliusCartLinksPlugin\Repository\Sylius\PromotionRepository;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sylius\Bundle\ApiBundle\Context\UserContextInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Context\ShopperContextInterface;
use Sylius\Component\Core\Factory\CartItemFactoryInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Core\Repository\ProductVariantRepositoryInterface;
use Sylius\Component\Core\Repository\PromotionRepositoryInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class CartLinkController extends AbstractController
{
    public function __construct(
        private PromotionRepository $promotionRepository,
        private CustomerContextInterface $customerContext,
        private ChannelContextInterface $channelContext,
        private ProductRepositoryInterface $productRepository,
//        private PromotionRepositoryInterface $promotionRepository,
        private FactoryInterface $cartLinkFactory,
        private EntityManagerInterface $entityManager,
        private RepositoryInterface $cartLinkRepository,
        private CartContextInterface $cartContext,
        private CartItemFactoryInterface $cartItemFactory,
        private OrderItemQuantityModifierInterface $itemQuantityModifier,
        private OrderProcessorInterface $orderProcessor,
        private ProductVariantRepositoryInterface $productVariantRepository,
    ) { }

    public function __invoke(string $code): Response
    {
        /** @var CartLinkInterface $cartLink */
        $cartLink = $this->cartLinkRepository->findOneBy(['code' => $code]);

        foreach ($cartLink->getActions() as $action) {
            if ($action->getType() === 'add_product_variant') {
                $productVariantCodes = $action->getConfiguration()['product_variants'];
            }
        }

        $productVariants = $this->productVariantRepository->findByCodes($productVariantCodes);

        dd($cartLink);

        // In CartLink we have
        // - ProductVariant[]
        // - Promotions[] - this later

        // Goal:
        // $this->cartLinkProcessor($cart, $cartLink);

        $customer = $this->customerContext->getCustomer();

        /** @var ProductInterface $product */
        $product = $this->productRepository->findOneByCode($productCode);

        $promotion = $this->promotionRepository->findActiveByChannel($this->channelContext->getChannel());

        /** @var OrderInterface $cart */
        $cart = $this->cartContext->getCart();
        dump($cart);
        $cart->setCustomer($customer);

        $cartItem = $this->cartItemFactory->createForCart($cart);
        $cartItem->setVariant($productVariant);

        $this->itemQuantityModifier->modify($cartItem, $cartItem->getQuantity() + 1); // Or reset based on config

        $cart->addPromotion($promotion[0]);

        $this->orderProcessor->process($cart);
        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        dump($cart->getItems()->toArray());
        dump($cart->getPromotions()->toArray());
        dd($this->cartLinkRepository->findAll());
    }
}
