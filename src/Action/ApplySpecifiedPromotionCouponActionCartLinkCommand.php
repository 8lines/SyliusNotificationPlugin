<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Action;

use EightLines\SyliusCartLinksPlugin\Repository\Sylius\PromotionCouponRepository;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PromotionCouponInterface;

final class ApplySpecifiedPromotionCouponActionCartLinkCommand implements CartLinkActionCommandInterface
{
    public function __construct(
        private PromotionCouponRepository $promotionCouponRepository,
    ) { }

    public function execute(OrderInterface $order, array $actionConfiguration): bool
    {
        $promotionCoupon = $this->promotionCouponRepository->findOneBy([
            'code' => $actionConfiguration['promotion_coupon']
        ]);

        if (!$promotionCoupon instanceof PromotionCouponInterface || !$promotionCoupon->isValid()) {
            return true;
        }

        $order->setPromotionCoupon($promotionCoupon);

        return true;
    }
}
