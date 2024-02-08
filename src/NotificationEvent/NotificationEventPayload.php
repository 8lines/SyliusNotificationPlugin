<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;

final class NotificationEventPayload
{
    private function __construct(
        private CustomerInterface|AdminUserInterface $syliusInvoker,
        private ?ChannelInterface $syliusChannel,
        private ?string $localeCode,
    ) {
    }

    public static function create(
        CustomerInterface|AdminUserInterface $syliusInvoker,
        ?ChannelInterface $syliusChannel,
        ?string $localeCode,
    ): self {
        return new self(
            syliusInvoker: $syliusInvoker,
            syliusChannel: $syliusChannel,
            localeCode: $localeCode,
        );
    }

    public function getSyliusInvoker(): CustomerInterface|AdminUserInterface
    {
        return $this->syliusInvoker;
    }

    public function getSyliusChannel(): ?ChannelInterface
    {
        return $this->syliusChannel;
    }

    public function getLocaleCode(): ?string
    {
        return $this->localeCode;
    }
}
