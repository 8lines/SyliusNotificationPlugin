<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Doctrine\Common\Collections\Collection;

interface NotificationActionsAwareInterface
{
    /**
     * @return Collection<array-key, NotificationActionInterface>
     */
    public function getActions(): Collection;

    public function addAction(NotificationActionInterface $action): void;

    public function hasAction(NotificationActionInterface $action): bool;

    public function removeAction(NotificationActionInterface $action): void;
}
