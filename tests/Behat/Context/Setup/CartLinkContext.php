<?php

declare(strict_types=1);

namespace Tests\EightLines\SyliusCartLinksPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use EightLines\SyliusCartLinksPlugin\Entity\CartLinkActionInterface;
use EightLines\SyliusCartLinksPlugin\Entity\CartLinkInterface;
use EightLines\SyliusCartLinksPlugin\Repository\CartLinkRepositoryInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Model\PromotionCouponInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class CartLinkContext implements Context
{
    public function __construct(
        private SharedStorageInterface $sharedStorage,
        private EntityManagerInterface $entityManager,
        private FactoryInterface $cartLinkFactory,
        private FactoryInterface $cartLinkActionFactory,
        private CartLinkRepositoryInterface $cartLinkRepository,
    ) { }

    /**
     * @Given there is (also) a cart link :code with :slug slug
     * @Given the store has (also) a cart link :code with :slug slug
     */
    public function thereIsACartLink(string $code, string $slug): void
    {
        $this->createCartLink($code, $slug);
    }

    /**
     * @Given /^(this cart link) adds ("[^"]+" product variant) to the cart$/
     * @Given /^(cart link with "[^"]+" code) adds ("[^"]+" product variant) to the cart$/
     */
    public function thisCartLinkAddsProductVariantToTheOrder(CartLinkInterface $cartLink, ProductVariantInterface $productVariant): void
    {
        $this->addActionToCartLink($cartLink, 'add_product_variant', ['product_variants' => [$productVariant->getCode()]]);
    }

    /**
     * @Given (this cart link) applies specified :coupon promotion coupon (to the cart)
     * @Given cart link with :cartLink code applies specified :coupon promotion coupon (to the cart)
     */
    public function thisCartLinkAddsPromotionCouponToTheOrder(CartLinkInterface $cartLink, PromotionCouponInterface $coupon): void
    {
        $this->addActionToCartLink($cartLink, 'apply_specified_promotion_coupon', ['promotion_coupon' => $coupon->getCode()]);
    }

    /**
     * @Given (this cart link) clears the cart
     * @Given cart link with :cartLink code clears the cart
     */
    public function thisCartLinkClearsCart(CartLinkInterface $cartLink): void
    {
        $cartLink->setEmptyCart(true);

        $this->entityManager->flush();
    }

    /**
     * @Given (this cart link) is disabled
     * @Given cart link with :cartLink code is disabled
     */
    public function thisCartLinkIsDisabled(CartLinkInterface $cartLink): void
    {
        $cartLink->setEnabled(false);

        $this->entityManager->flush();
    }

    /**
     * @Given (this cart link) has already expired
     * @Given cart link with :cartLink code has already expired
     */
    public function thisCartLinkHasExpired(CartLinkInterface $cartLink): void
    {
        $cartLink->setEndsAt(new \DateTime('1 day ago'));

        $this->entityManager->flush();
    }

    /**
     * @Given (this cart link) expires tomorrow
     * @Given cart link with :cartLink code expires tomorrow
     */
    public function thisCartLinkExpiresTomorrow(CartLinkInterface $cartLink): void
    {
        $cartLink->setEndsAt(new \DateTime('tomorrow'));

        $this->entityManager->flush();
    }

    /**
     * @Given (this cart link) has started yesterday
     * @Given cart link with :cartLink code has started yesterday
     */
    public function thisCartLinkHasStartedYesterday(CartLinkInterface $cartLink): void
    {
        $cartLink->setStartsAt(new \DateTime('1 day ago'));

        $this->entityManager->flush();
    }

    /**
     * @Given (this cart link) starts tomorrow
     * @Given cart link with :cartLink code starts tomorrow
     */
    public function thisCartLinkStartsTomorrow(CartLinkInterface $cartLink): void
    {
        $cartLink->setStartsAt(new \DateTime('tomorrow'));

        $this->entityManager->flush();
    }

    /**
     * @Given /^(this cart link) can be used (\d+) times?$/
     * @Given (this cart link) can be used once
     * @Given /^(cart link with "[^"]+" code) can be used (\d+) times?$/
     * @Given cart link with :cartLink code can be used once
     */
    public function thisCartLinkCanBeUsedNTimes(CartLinkInterface $cartLink, int $usageLimit = 1): void
    {
        $cartLink->setUsageLimit($usageLimit);

        $this->entityManager->flush();
    }

    /**
     * @Given (this cart link) usage limit is already reached
     * @Given cart link with :cartLink code usage limit is already reached
     */
    public function thisCartLinkUsageLimitIsAlreadyReached(CartLinkInterface $cartLink): void
    {
        $cartLink->setUsed($cartLink->getUsageLimit());

        $this->entityManager->flush();
    }

    private function createCartLink(string $code, string $slug): void
    {
        /** @var CartLinkInterface $cartLink */
        $cartLink = $this->cartLinkFactory->createNew();

        $cartLink->setCode($code);
        $cartLink->setCurrentLocale('en_US');
        $cartLink->setSlug($slug);
        $cartLink->addChannel($this->sharedStorage->get('channel'));

        $this->cartLinkRepository->add($cartLink);
        $this->entityManager->flush();

        $this->sharedStorage->set('cart_link', $cartLink);
    }

    private function addActionToCartLink(CartLinkInterface $cartLink, string $type, array $configuration): void
    {
        /** @var CartLinkActionInterface $cartLinkAction */
        $cartLinkAction = $this->cartLinkActionFactory->createNew();

        $cartLinkAction->setType($type);
        $cartLinkAction->setConfiguration($configuration);

        $cartLink->addAction($cartLinkAction);

        $this->entityManager->flush();
    }
}
