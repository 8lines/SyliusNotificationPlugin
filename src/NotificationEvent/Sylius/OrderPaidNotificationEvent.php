<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationEvent\Sylius;

use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationContext;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventInterface;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariable;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariableDefinition;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariableDescription;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariableName;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariableDefinitions;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariables;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariableValue;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class OrderPaidNotificationEvent implements NotificationEventInterface
{
    public static function getEventName(): string
    {
        return 'sylius.payment.post_complete';
    }

    public function getVariables(NotificationContext $context): NotificationEventVariables
    {
        $order = $this->getOrderFromContext($context);

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

    public function getPrimaryRecipient(NotificationContext $context): CustomerInterface
    {
        $order = $this->getOrderFromContext($context);

        if (null === $order) {
            throw new \InvalidArgumentException('Order should not be null');
        }

        $customer = $order->getCustomer();

        if (!$customer instanceof CustomerInterface) {
            throw new \InvalidArgumentException('Customer should be instance of CustomerInterface');
        }

        return $customer;
    }

    public function getPrimaryRecipientLocaleCode(NotificationContext $context): ?string
    {
        return $this->getOrderFromContext($context)?->getLocaleCode();
    }

    public function getSyliusChannel(NotificationContext $context): ?ChannelInterface
    {
        return $this->getOrderFromContext($context)?->getChannel();
    }

    private function getOrderFromContext(NotificationContext $context): ?OrderInterface
    {
        $subject = $context->getSubject();

        if (!$subject instanceof PaymentInterface) {
            return null;
        }

        return $subject->getOrder();
    }
}
