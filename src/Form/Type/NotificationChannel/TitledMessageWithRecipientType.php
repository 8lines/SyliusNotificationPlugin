<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\Type\NotificationChannel;

use EightLines\SyliusNotificationPlugin\Form\EventSubscriber\AddContentFormSubscriber;
use EightLines\SyliusNotificationPlugin\Form\EventSubscriber\AddRecipientsFormSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

final class TitledMessageWithRecipientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber(new AddRecipientsFormSubscriber())
            ->addEventSubscriber(new AddContentFormSubscriber(
                subject: true,
                message: true,
            ))
        ;
    }
}
