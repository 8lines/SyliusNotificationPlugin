<?php

declare(strict_types=1);

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
    public static function getEventName(): string
    {
        return 'sylius.payment.post_complete';
    }

    public function getVariables(object $subject): NotificationEventVariables
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

    public function getVariableNames(): NotificationEventVariableNames
    {
        return NotificationEventVariableNames::create(
            new NotificationEventVariableName('order_number'),
            new NotificationEventVariableName('order_total'),
        );
    }
}
