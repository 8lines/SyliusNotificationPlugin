<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Form\Type;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
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
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => CartLinkTranslationType::class,
                'label' => 'sylius.form.product.translations',
            ])
            ->add('actions', CartLinkActionCollectionType::class, [
                'label' => 'sylius.form.promotion.actions',
                'button_add_label' => 'sylius.form.promotion.add_action',
            ])
        ;
    }
}
