<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

final class NotificationContentTranslationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        if (true === $options['subject']) {
            $builder->add('subject', TextType::class, [
                'label' => 'eightlines_sylius_notification_plugin.ui.subject',
                'constraints' => true === $options['required']
                    ? [new NotBlank([
                        'message' => 'eightlines_sylius_notification_plugin.notification.action.content.subject.not_blank',
                        'groups' => ['sylius'],
                    ])] : [],
            ]);
        }

        if (true === $options['message']) {
            $builder->add('message', TextareaType::class, [
                'label' => 'eightlines_sylius_notification_plugin.ui.message',
                'constraints' => true === $options['required']
                    ? [new NotBlank([
                        'message' => 'eightlines_sylius_notification_plugin.notification.action.content.message.not_blank',
                        'groups' => ['sylius'],
                    ])] : [],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->define('subject')
            ->allowedTypes('bool')
            ->default(true)
        ;

        $resolver
            ->define('message')
            ->allowedTypes('bool')
            ->default(true)
        ;
    }
}
