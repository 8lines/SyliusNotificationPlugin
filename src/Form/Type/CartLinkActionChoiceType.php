<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CartLinkActionChoiceType extends AbstractType
{
    public function __construct(
        private array $actions
    ) { }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => array_flip($this->actions),
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
