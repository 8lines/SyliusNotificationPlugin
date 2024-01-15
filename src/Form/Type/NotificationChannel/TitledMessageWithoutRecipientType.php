<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\Type\NotificationChannel;

use EightLines\SyliusNotificationPlugin\Form\EventSubscriber\AddContentFormSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

final class TitledMessageWithoutRecipientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventSubscriber(new AddContentFormSubscriber(
            subject: true,
            message: true,
        ));
    }
}
