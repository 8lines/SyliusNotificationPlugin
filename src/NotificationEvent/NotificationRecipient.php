<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Core\Model\CustomerInterface;

final class NotificationRecipient
{
    private function __construct(
        private CustomerInterface|AdminUserInterface $syliusInvoker,
    ) {
    }

    public static function create(
        CustomerInterface|AdminUserInterface $syliusInvoker
    ): self {
        return new self(
            syliusInvoker: $syliusInvoker,
        );
    }

    public function getSyliusInvoker(): CustomerInterface|AdminUserInterface
    {
        return $this->syliusInvoker;
    }

    public function isCustomer(): bool
    {
        return $this->syliusInvoker instanceof CustomerInterface;
    }

    public function isAdminUser(): bool
    {
        return $this->syliusInvoker instanceof AdminUserInterface;
    }
}
