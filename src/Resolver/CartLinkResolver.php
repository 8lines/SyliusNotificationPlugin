<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Resolver;

use EightLines\SyliusCartLinksPlugin\Entity\CartLinkInterface;
use EightLines\SyliusCartLinksPlugin\Repository\CartLinkRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class CartLinkResolver implements CartLinkResolverInterface
{
    public function __construct(
        private CartLinkRepositoryInterface $cartLinkRepository,
        private LocaleContextInterface $localeContext,
        private ChannelContextInterface $channelContext,
    ) { }

    public function findBySlug(string $slug): ?CartLinkInterface
    {
        $cartLink = $this->cartLinkRepository->findOneBySlugAndChannelCode(
            $slug,
            $this->localeContext->getLocaleCode(),
            $this->channelContext->getChannel()->getCode(),
        );

        if (!$cartLink instanceof CartLinkInterface) {
            return null;
        }

        return $cartLink;
    }
}
