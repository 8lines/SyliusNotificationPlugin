<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\Type;

use Sylius\Component\Registry\ServiceRegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class NotificationEventChoiceType extends AbstractType
{
    public function __construct(
        private ServiceRegistryInterface $notificationEventsRegistry,
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => array_keys($this->notificationEventsRegistry->all()),
            'choice_label' => fn (string $choice) => $choice,
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
