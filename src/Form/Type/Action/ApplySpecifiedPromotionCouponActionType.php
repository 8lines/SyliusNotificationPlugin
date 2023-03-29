<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Form\Type\Action;

use EightLines\SyliusCartLinksPlugin\Form\Type\Autocomplete\PromotionCouponAutocompleteChoiceType;
use Sylius\Bundle\ResourceBundle\Form\DataTransformer\ResourceToIdentifierTransformer;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\ReversedTransformer;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class ApplySpecifiedPromotionCouponActionType extends AbstractType
{
    public function __construct(
        private RepositoryInterface $promotionCouponRepository,
    ) { }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('promotion_coupon', PromotionCouponAutocompleteChoiceType::class, [
                'label' => 'cart_links.form.cart_link_action.apply_specified_promotion_coupon.promotion_coupon',
                'required' => true,
                'constraints' => [
                    new NotBlank(['groups' => ['sylius']]),
                    new Type(['type' => 'string', 'groups' => ['sylius']]),
                ],
            ])
        ;

        $builder->get('promotion_coupon')->addModelTransformer(
            new ReversedTransformer(new ResourceToIdentifierTransformer($this->promotionCouponRepository, 'code')),
        );
    }
}
