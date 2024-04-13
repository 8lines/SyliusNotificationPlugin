<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\User\Model\UserInterface;
use Webmozart\Assert\Assert;

final class NotificationEventInvoker
{
    public const CUSTOMER = 'customer';
    public const ADMIN_USER = 'admin_user';

    private function __construct(
        private int $id,
        private string $fullName,
        private string $type,
    ) {
    }

    public static function fromUser(UserInterface $user): self
    {
        if (true === $user instanceof AdminUserInterface) {
            $fullName = null !== $user->getFirstName() && null !== $user->getLastName()
                ? \sprintf('%s %s', $user->getFirstName(), $user->getLastName())
                : $user->getUsername();

            return new self(
                id: $user->getId(),
                fullName: $fullName,
                type: self::ADMIN_USER,
            );
        }

        /** @var ShopUserInterface $user */
        $customer = $user->getCustomer();

        return new self(
            id: $customer->getId(),
            fullName: $customer->getFullName(),
            type: self::CUSTOMER,
        );
    }

    public function id(): int
    {
        return $this->id;
    }

    public function fullName(): string
    {
        return $this->fullName;
    }

    public function type(): ?string
    {
        return $this->type;
    }

    public function isCustomer(): bool
    {
        return self::CUSTOMER === $this->type;
    }

    public function isAdminUser(): bool
    {
        return self::ADMIN_USER === $this->type;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'fullName' => $this->fullName,
            'type' => $this->type,
        ];
    }

    public static function fromArray(array $data): self
    {
        Assert::keyExists($data, 'id', 'Key "id" must exists in NotificationEventInvoker $data');
        Assert::keyExists($data, 'fullName', 'Key "fullName" must exists in NotificationEventInvoker $data');
        Assert::keyExists($data, 'type', 'Key "type" must exists in NotificationEventInvoker $data');

        return new self(
            id: (int) $data['id'],
            fullName: $data['fullName'],
            type: $data['type'],
        );
    }
}
