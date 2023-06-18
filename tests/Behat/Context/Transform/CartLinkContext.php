<?php

declare(strict_types=1);

namespace Tests\EightLines\SyliusCartLinksPlugin\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use EightLines\SyliusCartLinksPlugin\Entity\CartLinkInterface;
use EightLines\SyliusCartLinksPlugin\Repository\CartLinkRepositoryInterface;
use EightLines\SyliusCartLinksPlugin\Resolver\CartLinkResolverInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Webmozart\Assert\Assert;

final class CartLinkContext implements Context
{
    public function __construct(
        private SharedStorageInterface $sharedStorage,
        private CartLinkResolverInterface $cartLinkResolver,
        private CartLinkRepositoryInterface $cartLinkRepository,
    ) { }

    /**
     * @Transform :cartLink
     * @Transform /^"([^"]+)" cart link$/
     * @Transform /^cart link with "([^"]+)" code$/
     */
    public function getCartLinkByCode(string $cartLinkCode): CartLinkInterface
    {
        /* @var CartLinkInterface $cartLink */
        $cartLink = $this->cartLinkRepository->findOneBy(['code' => $cartLinkCode]);

        Assert::isInstanceOf(
            $cartLink,
            CartLinkInterface::class,
            sprintf('Cart link with code "%s" does not exist', $cartLinkCode)
        );

        return $cartLink;
    }

    /**
     * @Transform /^cart link with slug "([^"]+)"$/
     */
    public function getCartLinkBySlug(string $cartLinkSlug): ?CartLinkInterface
    {
        return $this->cartLinkResolver->findBySlug($cartLinkSlug);
    }

    /**
     * @Transform this cart link
     * @Transform /^this cart link$/
     */
    public function getThisCartLink(): CartLinkInterface
    {
        Assert::true($this->sharedStorage->has('cart_link'), 'You must first create a cart link');

        return $this->sharedStorage->get('cart_link');
    }
}
