<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

interface CartLinkUsageInterface extends ResourceInterface
{
    public function getUsedAt(): \DateTimeImmutable;

    public function setUsedAt(\DateTimeImmutable $usedAt): void;
}
