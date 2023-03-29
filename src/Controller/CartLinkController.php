<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Controller;

use EightLines\SyliusCartLinksPlugin\Processor\CartLinkProcessorInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CartLinkController extends AbstractController
{
    public function __construct(
        private CartLinkProcessorInterface $cartLinkProcessor,
        private RepositoryInterface $cartLinkRepository,
        private CartContextInterface $cartContext,
        private ChannelContextInterface $channelContext,
    ) { }

    public function __invoke(string $code): Response
    {
        $cartLink = $this->cartLinkRepository->findOneBy(['code' => $code]);

        if (null === $cartLink) {
            throw new NotFoundHttpException('Given cart link does not exist.');
        }

        if (false === in_array($this->channelContext->getChannel(), $cartLink->getChannels()->toArray(), true)) {
            throw new NotFoundHttpException('Given cartLink is not available in this channel.');
        }

        $cart = $this->cartContext->getCart();
        $this->cartLinkProcessor->process($cart, $cartLink);

        return $this->redirectToRoute('sylius_shop_cart_summary');
    }
}
