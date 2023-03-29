<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Action;

use EightLines\SyliusCartLinksPlugin\Repository\Sylius\PromotionRepository;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PromotionInterface;

final class ApplyRandomPromotionCouponActionCartLinkCommand implements CartLinkActionCommandInterface
{
    public function __construct(
        private PromotionRepository $promotionRepository,
    ) { }

    public function execute(OrderInterface $order, array $actionConfiguration): bool
    {
        $promotion = $this->promotionRepository->findOneBy([
            'code' => $actionConfiguration['promotion_code']
        ]);

        if (!$promotion instanceof PromotionInterface) {
            return true;
        }

        foreach ($promotion->getCoupons() as $promotionCoupon) {
            if (!$promotionCoupon->isValid()) {
                continue;
            }

            $order->setPromotionCoupon($promotionCoupon);
            break;
        }

        return true;
    }
}
