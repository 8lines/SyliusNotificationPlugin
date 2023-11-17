<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\Type;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

final class CartLinkType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->add('channels', ChannelChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'label' => 'sylius.form.product.channels',
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'sylius.ui.enabled',
                'required' => false,
            ])
            ->add('emptyCart', CheckboxType::class, [
                'label' => 'cart_links.form.whether_empty_cart',
                'required' => false,
            ])
            ->add('usageLimit', IntegerType::class, [
                'label' => 'sylius.form.promotion.usage_limit',
                'required' => false,
            ])
            ->add('startsAt', DateTimeType::class, [
                'label' => 'sylius.form.promotion.starts_at',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ])
            ->add('endsAt', DateTimeType::class, [
                'label' => 'sylius.form.promotion.ends_at',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => CartLinkTranslationType::class,
                'label' => 'sylius.form.product.translations',
            ])
            ->add('actions', CartLinkActionCollectionType::class, [
                'label' => false,
                'button_add_label' => 'sylius.form.promotion.add_action',
            ])
        ;
    }
}
