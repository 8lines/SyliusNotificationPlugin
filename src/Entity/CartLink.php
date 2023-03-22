<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Entity;

use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;

final class CartLink implements CartLinkInterface
{
    use TimestampableTrait;

    use ChannelsAwareTrait;

    use ActionsAwareTrait;

    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

    private ?int $id = null;

    private ?string $code = null;

    private bool $emptyCart = false;

    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->initializeChannelsCollection();
        $this->initializeActionsCollection();
    }

    public function getId(): ?int
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

    public function getEmptyCart(): bool
    {
        return $this->emptyCart;
    }

    public function setEmptyCart(bool $emptyCart): void
    {
        $this->emptyCart = $emptyCart;
    }

    protected function createTranslation(): TranslationInterface
    {
        return new CartLinkTranslation();
    }
}
