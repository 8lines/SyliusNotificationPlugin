<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\Type\NotificationChannel;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class SlackNotificationChannelActionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('channel', TextType::class, [
            'required' => false,
            'label' => 'Slack channel'
        ]);
    }
}
