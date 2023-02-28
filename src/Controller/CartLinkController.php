<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Controller;

use EightLines\SyliusCartLinksPlugin\Processor\CartLinkProcessorInterface;
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
    ) { }

    public function __invoke(string $code): Response
    {
        $cartLink = $this->cartLinkRepository->findOneBy(['code' => $code]);

        if (null === $cartLink) {
            throw new NotFoundHttpException('Given cart link does not exist.');
        }

        $cart = $this->cartContext->getCart();
        $this->cartLinkProcessor->process($cart, $cartLink);

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
