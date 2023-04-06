<?php

declare(strict_types=1);

namespace Tests\EightLines\SyliusCartLinksPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use EightLines\SyliusCartLinksPlugin\Entity\CartLinkInterface;
use EightLines\SyliusCartLinksPlugin\Processor\CartLinkProcessorInterface;
use FriendsOfBehat\PageObjectExtension\Page\UnexpectedPageException;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Tests\EightLines\SyliusCartLinksPlugin\Behat\Context\Page\Shop\Cart\SummaryPageInterface;
use Webmozart\Assert\Assert;
final class CartContext implements Context
{
    public function __construct(
        private SharedStorageInterface $sharedStorage,
        private SummaryPageInterface $summaryPage,
        private CartLinkProcessorInterface $cartLinkProcessor,
        private OrderRepositoryInterface $orderRepository,
    ) { }

    /**
     * @Then there should be :couponCode coupon in my cart
     */
    public function thereShouldBeCouponInMyCart(string $couponCode): void
    {
        Assert::true($this->summaryPage->isPromotionCouponApplied($couponCode));
    }

    /**
     * @When /^I use (cart link with slug "[^"]+")$/
     * @throws UnexpectedPageException
     */
    public function iUseCartLinkWithSlug(?CartLinkInterface $cartLink)
    {
        if ($cartLink === null) {
            $this->sharedStorage->set('used_cart_link', null);
            return;
        }

        /* @var OrderInterface $order */
        $order = $this->orderRepository->findOneBy(['state' => OrderInterface::STATE_CART]);

        $this->cartLinkProcessor->process($order, $cartLink);

        $this->summaryPage->open(['tokenValue' => $order->getTokenValue()]);
        $this->summaryPage->updateCart();

        $this->sharedStorage->set('used_cart_link', $cartLink);
    }

    /**
     * @Then there should appear an error that (used cart link) does not exist
     */
    public function usedCartLinkShouldNotExist(): void
    {
        Assert::true(!$this->sharedStorage->has('used_cart_link'), 'Used cart link exists');
    }
}
