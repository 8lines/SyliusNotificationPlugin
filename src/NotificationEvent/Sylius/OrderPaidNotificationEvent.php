<?php

namespace EightLines\SyliusNotificationPlugin\NotificationEvent\Sylius;

use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventInterface;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariable;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariableName;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariableNames;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariables;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariableValue;
use Sylius\Component\Core\Model\PaymentInterface;

class OrderPaidNotificationEvent implements NotificationEventInterface
{
    public function getEventName(): string
    {
        return 'sylius.order.paid';
    }

    public function getVariables(mixed $context): NotificationEventVariables
    {
        if (!$context instanceof PaymentInterface) {
            throw new \InvalidArgumentException('Context should be instance of PaymentInterface');
        }

        $order = $context->getOrder();

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

    public function getVariableNames(): NotificationEventVariableNames
    {
        return NotificationEventVariableNames::create(
            new NotificationEventVariableName('order_number'),
            new NotificationEventVariableName('order_total'),
        );
    }
}
