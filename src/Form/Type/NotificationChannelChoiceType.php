<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\Type;

use Sylius\Component\Registry\ServiceRegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class NotificationChannelChoiceType extends AbstractType
{
    public function __construct(
        private ServiceRegistryInterface $notificationChannelsRegistry,
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => array_keys($this->notificationChannelsRegistry->all()),
            'choice_label' => fn (string $choice) => $choice,
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
