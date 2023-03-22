<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Resolver;

use EightLines\SyliusCartLinksPlugin\Entity\CartLinkInterface;

interface CartLinkResolverInterface
{
    public function findBySlug(string $slug): ?CartLinkInterface;
}
