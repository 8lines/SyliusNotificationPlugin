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
        private ?string $firstName,
        private ?string $lastName,
        private ?string $email,
        private ?string $phoneNumber,
    ) {
    }

    public static function createFromCustomer(CustomerInterface $customer): self
    {
        return new self(
            id: (int) $customer->getId(),
            type: self::CUSTOMER,
            firstName: $customer->getFirstName(),
            lastName: $customer->getLastName(),
            email: $customer->getEmail(),
            phoneNumber: $customer->getPhoneNumber(),
        );
    }

    public static function createFromAdminUser(AdminUserInterface $adminUser): self
    {
        return new self(
            id: (int) $adminUser->getId(),
            type: self::ADMIN_USER,
            firstName: $adminUser->getFirstName(),
            lastName: $adminUser->getLastName(),
            email: $adminUser->getEmail(),
            phoneNumber: null,
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
