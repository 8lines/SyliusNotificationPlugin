<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationEvent\Sylius;

use EightLines\SyliusNotificationPlugin\Form\Type\NotificationEvent\OrderPaidNotificationEventActionType;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventInterface;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariable;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariableDefinition;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariableDescription;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariableName;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariableDefinitions;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariables;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariableValue;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\PaymentInterface;

class OrderPaidNotificationEvent implements NotificationEventInterface
{
    public static function getEventName(): string
    {
        return 'sylius.payment.post_complete';
    }

    public static function getConfigurationFormType(): ?string
    {
        return OrderPaidNotificationEventActionType::class;
    }

    public function getVariables(mixed $subject): NotificationEventVariables
    {
        if (!$subject instanceof PaymentInterface) {
            throw new \InvalidArgumentException('Subject should be instance of PaymentInterface');
        }

        $order = $subject->getOrder();

        if (null === $order) {
            throw new \InvalidArgumentException('Order should not be null');
        }

        return NotificationEventVariables::create(
            new NotificationEventVariable(
                name: new NotificationEventVariableName('order_number'),
                value: new NotificationEventVariableValue($order->getNumber()),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('order_total'),
                value: new NotificationEventVariableValue($order->getTotal()),
            ),
        );
    }

    public function getVariableDefinitions(): NotificationEventVariableDefinitions
    {
        return NotificationEventVariableDefinitions::create(
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('order_number'),
                defaultValue: new NotificationEventVariableValue(''),
                description: new NotificationEventVariableDescription('Order number'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('order_total'),
                defaultValue: new NotificationEventVariableValue(''),
                description: new NotificationEventVariableDescription('Order total'),
            )
        );
    }

    public function getEventChannel(mixed $subject): ?ChannelInterface
    {
        if (!$subject instanceof PaymentInterface) {
            return null;
        }

        $order = $subject->getOrder();
        return $order?->getChannel();
    }


    public function getEventLocaleCode(mixed $subject): ?string
    {
        if (!$subject instanceof PaymentInterface) {
            return null;
        }

        $order = $subject->getOrder();
        return $order?->getLocaleCode();
    }
}
