<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Controller;

use EightLines\SyliusCartLinksPlugin\Processor\CartLinkProcessorInterface;
use EightLines\SyliusCartLinksPlugin\Resolver\CartLinkResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class CartLinkController extends AbstractController
{
    public function __construct(
        private CartLinkProcessorInterface $cartLinkProcessor,
        private CartContextInterface $cartContext,
        private CustomerContextInterface $customerContext,
        private CartLinkResolverInterface $cartLinkResolver,
    ) { }

    public function __invoke(string $slug): Response
    {
        $cartLink = $this->cartLinkResolver->findBySlug($slug);

        if (null === $cartLink) {
            throw $this->createNotFoundException('Given cart link does not exist.');
        }

        /** @var OrderInterface $cart */
        $cart = $this->cartContext->getCart();
        $cart->setCustomer($this->customerContext->getCustomer());

        $this->cartLinkProcessor->process($cart, $cartLink);

        return $this->redirectToRoute('sylius_shop_cart_summary');
    }
}
