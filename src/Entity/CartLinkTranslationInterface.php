<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\SlugAwareInterface;

interface CartLinkTranslationInterface extends ResourceInterface, SlugAwareInterface
{
    public function getName(): string;

    public function setName(string $name): void;
}
