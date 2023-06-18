<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Entity;

final class CartLinkAction implements CartLinkActionInterface
{
    private int $id;

    private string $type;

    private array $configuration = [];

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }
}
