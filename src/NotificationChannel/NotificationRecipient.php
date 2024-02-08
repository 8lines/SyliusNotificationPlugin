<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationChannel;

use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Core\Model\CustomerInterface;

final class NotificationRecipient
{
    public const CUSTOMER = 'customer';
    public const ADMIN_USER = 'admin_user';

    private function __construct(
        private int $id,
        private string $type,
        private bool $primary,
        private ?string $firstName,
        private ?string $lastName,
        private ?string $email,
        private ?string $phoneNumber,
        private ?string $localeCode,
    ) {
    }

    public static function createFromCustomer(
        CustomerInterface $customer,
        bool $primary = false,
        ?string $localeCode = null,
    ): self {
        return new self(
            id: (int) $customer->getId(),
            type: self::CUSTOMER,
            primary: $primary,
            firstName: $customer->getFirstName(),
            lastName: $customer->getLastName(),
            email: $customer->getEmail(),
            phoneNumber: $customer->getPhoneNumber(),
            localeCode: $localeCode,
        );
    }

    public static function createFromAdminUser(
        AdminUserInterface $adminUser,
        bool $primary = false,
        ?string $localeCode = null,
    ): self {
        return new self(
            id: (int) $adminUser->getId(),
            type: self::ADMIN_USER,
            primary: $primary,
            firstName: $adminUser->getFirstName(),
            lastName: $adminUser->getLastName(),
            email: $adminUser->getEmail(),
            phoneNumber: null,
            localeCode: $localeCode ?? $adminUser->getLocaleCode(),
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isPrimary(): bool
    {
        return $this->primary;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function getLocaleCode(): ?string
    {
        return $this->localeCode;
    }

    public function getFullName(): ?string
    {
        if (null === $this->firstName || null === $this->lastName) {
            return null;
        }

        return sprintf('%s %s', $this->firstName, $this->lastName);
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
