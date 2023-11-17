<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

final class CartLinkActionType extends AbstractConfigurableCartLinkElementType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('type', CartLinkActionChoiceType::class, [
                'label' => 'sylius.form.promotion_action.type',
                'attr' => [
                    'data-form-collection' => 'update',
                ],
            ])
        ;
    }
}
