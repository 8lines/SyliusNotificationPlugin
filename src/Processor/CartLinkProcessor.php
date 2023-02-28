<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Processor;

use EightLines\SyliusCartLinksPlugin\Applicator\CartLinkActionApplicatorInterface;
use EightLines\SyliusCartLinksPlugin\Entity\CartLinkInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CartLinkProcessor implements CartLinkProcessorInterface
{
    public function __construct(
        private CartLinkActionApplicatorInterface $cartLinkActionApplicator,
        private ChannelContextInterface $channelContext,
    ) { }

    public function process(OrderInterface $order, CartLinkInterface $cartLink): void
    {
        if (false === in_array($this->channelContext->getChannel(), $cartLink->getChannels()->toArray(), true)) {
            throw new NotFoundHttpException('Given cartLink is not available in this channel.');
        }

        $this->cartLinkActionApplicator->apply($order, $cartLink);
    }
}
