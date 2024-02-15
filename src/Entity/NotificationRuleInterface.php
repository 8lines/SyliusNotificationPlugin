<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface NotificationRuleInterface extends
    ResourceInterface,
    TimestampableInterface
{
    public function getVariableName(): ?string;

    public function setVariableName(?string $variableName): void;

    public function getComparatorType(): ?string;

    public function setComparatorType(?string $comparatorType): void;

    public function getComparableValue(): mixed;

    public function setComparableValue(mixed $comparableValue): void;
}
