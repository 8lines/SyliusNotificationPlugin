<?php

namespace Tests\EightLines\SyliusCartLinksPlugin\Behat\Context\Page\Shop\Cart;

use Behat\Mink\Exception\ElementNotFoundException;
use Sylius\Behat\Page\Shop\Cart\SummaryPage as BaseSummaryPage;

class SummaryPage extends BaseSummaryPage implements SummaryPageInterface
{
    /**
     * @throws ElementNotFoundException
     */
    public function isPromotionCouponApplied(string $couponCode): bool
    {
        return $this->getElement('coupon_field')->getValue() === $couponCode;
    }
}


