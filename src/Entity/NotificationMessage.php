<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;

class NotificationMessage implements NotificationMessageInterface
{
    use TimestampableTrait;

    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

    protected function createTranslation(): TranslationInterface
    {
        // TODO: Implement createTranslation() method.
    }
}
