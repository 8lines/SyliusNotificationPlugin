<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

final class NotificationContentTranslationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        if (true === $options['subject']) {
            $constraints = [new Length([
                'max' => 250,
                'maxMessage' => 'eightlines_sylius_notification_plugin.notification.action.content.subject.max_length',
                'groups' => ['sylius'],
            ])];

            if (true === $options['subject_required']) {
                $constraints[] = new NotBlank([
                    'message' => 'eightlines_sylius_notification_plugin.notification.action.content.subject.not_blank',
                    'groups' => ['sylius'],
                ]);
            }

            $builder->add('subject', TextType::class, [
                'label' => 'eightlines_sylius_notification_plugin.ui.subject',
                'required' => true === $options['subject_required'],
                'constraints' => $constraints,
            ]);
        }

        if (true === $options['message']) {
            $builder->add('message', TextareaType::class, [
                'label' => 'eightlines_sylius_notification_plugin.ui.message',
                'required' => true === $options['message_required'],
                'constraints' => true === $options['message_required']
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

        $resolver
            ->define('subject_required')
            ->allowedTypes('bool')
            ->default(true)
        ;

        $resolver
            ->define('message_required')
            ->allowedTypes('bool')
            ->default(true)
        ;
    }
}
