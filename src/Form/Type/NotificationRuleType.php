<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\Type;

use EightLines\SyliusNotificationPlugin\Resolver\ComparatorResolver;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class NotificationRuleType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('variableName', TextType::class, [
                'label' => 'eightlines_sylius_notification_plugin.ui.variable_name',
            ])
            ->add('comparatorType', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'eightlines_sylius_notification_plugin.ui.comparator_type.equal_to' => ComparatorResolver::EQUAL_TO,
                    'eightlines_sylius_notification_plugin.ui.comparator_type.not_equal_to' => ComparatorResolver::NOT_EQUAL_TO,
                    'eightlines_sylius_notification_plugin.ui.comparator_type.greater_than' => ComparatorResolver::GREATER_THAN,
                    'eightlines_sylius_notification_plugin.ui.comparator_type.greater_than_or_equal_to' => ComparatorResolver::GREATER_THAN_OR_EQUAL_TO,
                    'eightlines_sylius_notification_plugin.ui.comparator_type.less_than' => ComparatorResolver::LESS_THAN,
                    'eightlines_sylius_notification_plugin.ui.comparator_type.less_than_or_equal_to' => ComparatorResolver::LESS_THAN_OR_EQUAL_TO,
                ],
            ])
            ->add('comparableValue', TextType::class, [
                'label' => 'eightlines_sylius_notification_plugin.ui.comparable_value',
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'notification_rule';
    }
}
