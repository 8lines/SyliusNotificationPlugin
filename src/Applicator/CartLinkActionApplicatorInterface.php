<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Applicator;

use EightLines\SyliusNotificationPlugin\Entity\CartLinkInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface CartLinkActionApplicatorInterface
{
    public function apply(OrderInterface $order, CartLinkInterface $cartLink): void;
}
