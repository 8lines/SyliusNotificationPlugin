<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Applicator;

use EightLines\SyliusCartLinksPlugin\Entity\CartLinkActionInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface CartLinkActionApplicatorInterface
{
    public function apply(OrderInterface $order, CartLinkActionInterface $action): void;
}
