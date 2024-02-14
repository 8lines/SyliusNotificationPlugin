<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

class AuditLogInvoker
{
    public const CUSTOMER = 'customer';
    public const ADMIN_USER = 'admin_user';

    private ?int $id = null;

    private ?string $fullName = null;

    private ?string $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function isCustomer(): bool
    {
        return self::CUSTOMER === $this->type;
    }

    public function isAdminUser(): bool
    {
        return self::ADMIN_USER === $this->type;
    }
}
