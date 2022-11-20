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
use Sylius\Component\Core\Repository\PromotionRepositoryInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class GreetingController extends AbstractController
{
    public function __construct(
        private PromotionRepository $promotionRepository,
        private SerializerInterface $serializer,
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
    ) { }

    public function staticallyGreetAction(string $productCode): Response
    {
        dump($this->serializer->serialize($this->productRepository->findByPhrase('jean', 'en_US'), 'json'));
        dd($this->serializer->serialize($this->promotionRepository->findByPhrase('chr'), 'json'));
        $customer = $this->customerContext->getCustomer();

        /** @var ProductInterface $product */
        $product = $this->productRepository->findOneByCode($productCode);

        $promotion = $this->promotionRepository->findActiveByChannel($this->channelContext->getChannel());

        /** @var CartLinkInterface $cartLink */
        $cartLink = $this->cartLinkFactory->createNew();
        $cartLink->setCode('test');

        $this->entityManager->persist($cartLink);
        $this->entityManager->flush();

        /** @var OrderInterface $cart */
        $cart = $this->cartContext->getCart();
        dump($cart);
        $cart->setCustomer($customer);

        $cartItem = $this->cartItemFactory->createForCart($cart);
        $cartItem->setVariant($product->getVariants()->first());

        $this->itemQuantityModifier->modify($cartItem, $cartItem->getQuantity() + 1); // Or reset based on config

        $cart->addPromotion($promotion[0]);

        $this->orderProcessor->process($cart);


        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        dump($cart->getItems()->toArray());
        dump($cart->getPromotions()->toArray());
        dd($this->cartLinkRepository->findAll());
    }

    public function dynamicallyGreetAction(?string $name): Response
    {
        dd($name);
    }
}
