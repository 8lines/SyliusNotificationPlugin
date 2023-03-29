<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Form\Type\Autocomplete;

use Sylius\Bundle\ResourceBundle\Form\Type\ResourceAutocompleteChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class PromotionCouponAutocompleteChoiceType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'resource' => 'sylius.promotion_coupon',
            'choice_name' => 'code',
            'choice_value' => 'code',
        ]);
    }

    /**
     * @psalm-suppress MissingPropertyType
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['remote_criteria_type'] = 'contains';
        $view->vars['remote_criteria_name'] = 'phrase';
    }

    public function getBlockPrefix(): string
    {
        return 'eightlines_sylius_cart_links_promotion_coupon_autocomplete_choice';
    }

    public function getParent(): string
    {
        return ResourceAutocompleteChoiceType::class;
    }
}
