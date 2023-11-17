<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Action;

use Sylius\Component\Core\Model\OrderInterface;

interface CartLinkActionCommandInterface
{
    public function execute(OrderInterface $order, array $actionConfiguration): bool;
}
