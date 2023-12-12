<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;

class Notification implements NotificationInterface
{
    use TimestampableTrait;

    use ToggleableTrait;

    use ChannelsAwareTrait;

    use NotificationActionsAwareTrait;

    private int $id;

    private ?string $code = null;

    private ?string $event = null;

    public function __construct()
    {
        $this->initializeChannelsCollection();
        $this->initializeActionsCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getEvent(): ?string
    {
        return $this->event;
    }

    public function setEvent(?string $event): void
    {
        $this->event = $event;
    }
}
