<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Action;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\PromotionRepositoryInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;

final class ApplyPromotionActionCartLinkCommand implements CartLinkActionCommandInterface
{
    public function __construct(
        private PromotionRepositoryInterface $promotionRepository,
        private ChannelContextInterface $channelContext,
        private OrderProcessorInterface $orderProcessor,
    ) { }

    public function execute(OrderInterface $order, array $actionConfiguration): bool
    {
        $promotion = $this->promotionRepository->findActiveByChannel($this->channelContext->getChannel());

        foreach ($promotion as $promotionItem) {
            if ($promotionItem->getCode() !== $actionConfiguration['promotion_code']) {
                continue;
            }

            $order->addPromotion($promotionItem);
        }

        $this->orderProcessor->process($order);

        return true;
    }
}
