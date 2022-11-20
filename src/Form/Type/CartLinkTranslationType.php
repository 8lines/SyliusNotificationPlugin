<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class CartLinkTranslationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'sylius.ui.name',
            ])
            ->add('slug', TextType::class, [
                'label' => 'sylius.form.product.slug',
            ])
        ;
    }
}
