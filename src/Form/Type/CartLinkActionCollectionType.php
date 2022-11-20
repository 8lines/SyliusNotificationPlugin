<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

final class CartLinkActionCollectionType extends AbstractConfigurationCollectionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('entry_type', CartLinkActionType::class);
    }
}
