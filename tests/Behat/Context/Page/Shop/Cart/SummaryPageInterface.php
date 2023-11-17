<?php

declare(strict_types=1);

namespace Tests\EightLines\SyliusNotificationPlugin\Behat\Context\Page\Shop\Cart;

use Sylius\Behat\Page\Shop\Cart\SummaryPageInterface as BaseSummaryPageInterface;

interface SummaryPageInterface extends BaseSummaryPageInterface
{
    public function isPromotionCouponApplied(string $couponCode): bool;
}
