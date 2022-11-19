<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class AddCustomConfigurationFormSubscriber implements EventSubscriberInterface
{
    /**
     * @param array<string, mixed> $options,
     */
    public function __construct(
        private string $type,
        private array $options = [],
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
        ];
    }

    public function preSetData(FormEvent $event): void
    {
        $form = $event->getForm();

        $form->add(
            child: 'custom',
            type: $this->type,
            options: array_merge(['label' => false], $this->options),
        );
    }
}
