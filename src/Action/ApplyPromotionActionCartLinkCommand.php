<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Action;

use EightLines\SyliusCartLinksPlugin\Repository\Sylius\PromotionRepository;
use Sylius\Component\Core\Model\OrderInterface;

final class ApplyPromotionActionCartLinkCommand implements CartLinkActionCommandInterface
{
    public function __construct(
        private PromotionRepository $promotionRepository,
    ) { }

    public function execute(OrderInterface $order, array $actionConfiguration): bool
    {
        $promotion = $this->promotionRepository->findByPhrase($actionConfiguration['promotion_code'], 1);

        if (count($promotion) < 1 || null === $promotion[0]) {
            return true;
        }

        $order->addPromotion($promotion[0]);
        return true;
    }
}
