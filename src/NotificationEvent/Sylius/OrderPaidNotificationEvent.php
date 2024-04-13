<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationEvent\Sylius;

use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationContext;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventInterface;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventPayload;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariable;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariableDefinition;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariableDefinitions;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariableDescription;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariableName;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariables;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariableValue;
use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class OrderPaidNotificationEvent implements NotificationEventInterface
{
    public function __construct(
        private MoneyFormatterInterface $moneyFormatter,
    ) {
    }

    public static function getEventName(): string
    {
        return 'sylius.payment.post_complete';
    }

    public function getEventPayload(NotificationContext $context): NotificationEventPayload
    {
        $payment = $this->getPaymentFromContext($context);
        $order = $this->getOrderFromPayment($payment);
        $customer = $order->getCustomer();

        if (false === $customer instanceof CustomerInterface) {
            throw new \InvalidArgumentException('Order has no customer');
        }

        return NotificationEventPayload::create(
            syliusTarget: $customer,
            syliusChannel: $order->getChannel(),
            localeCode: $order->getLocaleCode(),
        );
    }

    public function getVariables(NotificationContext $context): NotificationEventVariables
    {
        $payment = $this->getPaymentFromContext($context);
        $order = $this->getOrderFromPayment($payment);

        $paymentCurrencyCode = $payment->getCurrencyCode()
            ?? $order->getCurrencyCode()
            ?? $order->getChannel()?->getBaseCurrency()?->getCode();

        $paymentAmount = $payment->getAmount();

        if (null !== $paymentAmount) {
            $paymentAmount = $this->moneyFormatter->format(
                amount: $paymentAmount,
                currencyCode: $paymentCurrencyCode ?? '',
                locale: $order->getLocaleCode() ?? $order->getChannel()?->getDefaultLocale()?->getCode(),
            );
        }

        return NotificationEventVariables::create(
            new NotificationEventVariable(
                name: new NotificationEventVariableName('order_number'),
                value: new NotificationEventVariableValue($order->getNumber()),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('payment_method'),
                value: new NotificationEventVariableValue($payment->getMethod()?->getName()),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('payment_amount'),
                value: new NotificationEventVariableValue($paymentAmount),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('payment_currency'),
                value: new NotificationEventVariableValue($paymentCurrencyCode),
            ),
        );
    }

    public function getVariableDefinitions(): NotificationEventVariableDefinitions
    {
        return NotificationEventVariableDefinitions::create(
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('order_number'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Order number'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('payment_method'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Payment method'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('payment_amount'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Payment amount'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('payment_currency'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Payment currency'),
            ),
        );
    }

    private function getPaymentFromContext(NotificationContext $context): PaymentInterface
    {
        $subject = $context->getSubject();

        if (false === $subject instanceof PaymentInterface) {
            throw new \InvalidArgumentException('Subject is not a PaymentInterface');
        }

        return $subject;
    }

    private function getOrderFromPayment(PaymentInterface $payment): OrderInterface
    {
        $order = $payment->getOrder();

        if (null === $order) {
            throw new \InvalidArgumentException('Payment has no order');
        }

        return $order;
    }
}
