<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\Component\Resource\Translation\Provider\TranslationLocaleProviderInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class NotificationContentType extends AbstractResourceType
{
    private string $defaultLocaleCode;

    public function __construct(
        string $dataClass,
        array $validationGroups,
        TranslationLocaleProviderInterface $localeProvider,
    ) {
        parent::__construct($dataClass, $validationGroups);

        $this->defaultLocaleCode = $localeProvider->getDefaultLocaleCode();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('translations', ResourceTranslationsType::class, [
            'entry_type' => NotificationContentTranslationType::class,
            'entry_options' => function (string $localeCode) use ($options): array {
                return [
                    'subject' => $options['subject'],
                    'message' => $options['message'],
                    'required' => $localeCode === $this->defaultLocaleCode,
                ];
            },
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->define('subject')
            ->allowedTypes('bool')
            ->default(true)
        ;

        $resolver
            ->define('message')
            ->allowedTypes('bool')
            ->default(true)
        ;
    }
}
