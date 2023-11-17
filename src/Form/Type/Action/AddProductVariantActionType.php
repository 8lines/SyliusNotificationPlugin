<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\Type\Action;

use EightLines\SyliusNotificationPlugin\Form\Type\Autocomplete\ProductVariantAutocompleteChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;

final class AddProductVariantActionType extends AbstractType
{
    public function __construct(
        private DataTransformerInterface $productVariantsToCodesTransformer,
    ) { }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product_variants', ProductVariantAutocompleteChoiceType::class, [
                'label' => 'sylius.form.promotion_action.add_product_configuration.product',
                'required' => true,
                'multiple' => true,
            ])
        ;

        $builder->get('product_variants')->addModelTransformer($this->productVariantsToCodesTransformer);
    }
}
