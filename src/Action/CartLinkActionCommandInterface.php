<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Action;

use Sylius\Component\Core\Model\OrderInterface;

interface CartLinkActionCommandInterface
{
    public function execute(OrderInterface $order, array $actionConfiguration): bool;
}
