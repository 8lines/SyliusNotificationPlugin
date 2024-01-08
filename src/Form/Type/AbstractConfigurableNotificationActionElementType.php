<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\Type;

use EightLines\SyliusNotificationPlugin\Entity\NotificationActionInterface;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractConfigurableNotificationActionElementType extends AbstractResourceType
{
    private const EVENT_CODE_FIELD_NAME = 'eventCode';

    public function __construct(
        string $dataClass,
        array $validationGroups,
        private FormTypeRegistryInterface $formTypeRegistry
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
                $notificationEvent = $this->getRegistryIdentifier($event->getForm(), $event->getData());

                if (null === $notificationEvent) {
                    return;
                }

                $this->addConfigurationFields(
                    form: $event->getForm(),
                    configurationType: $this->formTypeRegistry->get($notificationEvent, 'default')
                );
            })
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                $notificationEvent = $this->getRegistryIdentifier($event->getForm(), $event->getData());

                if (null === $notificationEvent) {
                    return;
                }

                $event->getForm()->get(self::EVENT_CODE_FIELD_NAME)->setData($notificationEvent);
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
                $data = $event->getData();

                if (null === $data[self::EVENT_CODE_FIELD_NAME]) {
                    return;
                }

                $this->addConfigurationFields(
                    form: $event->getForm(),
                    configurationType: $this->formTypeRegistry->get($data[self::EVENT_CODE_FIELD_NAME], 'default')
                );
            })
        ;
    }

    protected function getRegistryIdentifier(FormInterface $form, mixed $data = null): ?string
    {
        if ($data instanceof NotificationActionInterface && null !== $data->getEventCode()) {
            return $data->getEventCode();
        }

        /** @var string|null $configurationType */
        $configurationType = $form->getConfig()->getOption('configuration_type');
        return $configurationType;
    }

    protected function addConfigurationFields(FormInterface $form, ?string $configurationType): void
    {
        if (null === $configurationType || '' === $configurationType ) {
            return;
        }

        $configuration = $form->get('configuration');
        $configuration->add('data', $configurationType, [
            'label' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('configuration_type', null)
            ->setAllowedTypes('configuration_type', ['string', 'null'])
        ;
    }
}
