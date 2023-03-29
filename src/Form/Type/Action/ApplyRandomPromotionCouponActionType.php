<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Form\Type\Action;

use EightLines\SyliusCartLinksPlugin\Form\Type\Autocomplete\PromotionCodeAutocompleteChoiceType;
use Sylius\Bundle\ResourceBundle\Form\DataTransformer\ResourceToIdentifierTransformer;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\ReversedTransformer;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class ApplyRandomPromotionCouponActionType extends AbstractType
{
    public function __construct(
        private RepositoryInterface $promotionRepository,
    ) { }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('promotion_code', PromotionCodeAutocompleteChoiceType::class, [
                'label' => 'cart_links.form.cart_link_action.apply_random_promotion_coupon.promotion',
                'constraints' => [
                    new NotBlank(['groups' => ['sylius']]),
                    new Type(['type' => 'string', 'groups' => ['sylius']]),
                ],
            ])
        ;

        $builder->get('promotion_code')->addModelTransformer(
            new ReversedTransformer(new ResourceToIdentifierTransformer($this->promotionRepository, 'code')),
        );
    }
}
