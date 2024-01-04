<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\Type\NotificationEvent;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

final class OrderPaidNotificationEventActionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('recipient', ChoiceType::class, [
                'label' => 'eightlines_sylius_notification_plugin.ui.recipient',
                'choices' => [
                    'sylius.ui.customer' => 'customer',
                ],
            ])
        ;
    }
}
