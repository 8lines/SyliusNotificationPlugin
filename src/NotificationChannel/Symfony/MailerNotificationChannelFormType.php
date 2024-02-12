<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationChannel\Symfony;

use EightLines\SyliusNotificationPlugin\Form\EventSubscriber\AddContentFormSubscriber;
use EightLines\SyliusNotificationPlugin\Form\EventSubscriber\AddCustomConfigurationFormSubscriber;
use EightLines\SyliusNotificationPlugin\Form\EventSubscriber\AddRecipientsFormSubscriber;
use EightLines\SyliusNotificationPlugin\Form\Type\NotificationChannel\TitledMessageWithRecipientType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

final class MailerNotificationChannelFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new AddRecipientsFormSubscriber());

        $builder->addEventSubscriber(new AddCustomConfigurationFormSubscriber(
            type: MailerNotificationChannelCustomConfigurationFormType::class,
        ));

        $builder->addEventSubscriber(new AddContentFormSubscriber(
            subject: true,
            message: true,
        ));
    }
}
