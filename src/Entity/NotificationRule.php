<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Sylius\Component\Resource\Model\TimestampableTrait;

class NotificationRule implements NotificationRuleInterface
{
    use TimestampableTrait;

    private ?int $id;

    private ?string $variableName = null;

    private ?string $comparatorType = null;

    private mixed $comparableValue = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVariableName(): ?string
    {
        return $this->variableName;
    }

    public function setVariableName(?string $variableName): void
    {
        $this->variableName = $variableName;
    }

    public function getComparatorType(): ?string
    {
        return $this->comparatorType;
    }

    public function setComparatorType(?string $comparatorType): void
    {
        $this->comparatorType = $comparatorType;
    }

    public function getComparableValue(): mixed
    {
        return $this->comparableValue;
    }

    public function setComparableValue(mixed $comparableValue): void
    {
        $this->comparableValue = $comparableValue;
    }
}
