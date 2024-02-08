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
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventPayload;
use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class OrderPlacedNotificationEvent implements NotificationEventInterface
{
    public function __construct(
        private MoneyFormatterInterface $moneyFormatter,
    ) {
    }

    public static function getEventName(): string
    {
        return 'sylius.order.post_complete';
    }

    public function getEventPayload(NotificationContext $context): NotificationEventPayload
    {
        $order = $this->getOrderFromContext($context);
        $customer = $order->getCustomer();

        if (false === $customer instanceof CustomerInterface) {
            throw new \InvalidArgumentException('Order has no customer');
        }

        return NotificationEventPayload::create(
            syliusInvoker: $customer,
            syliusChannel: $order->getChannel(),
            localeCode: $order->getLocaleCode(),
        );
    }

    public function getVariables(NotificationContext $context): NotificationEventVariables
    {
        $order = $this->getOrderFromContext($context);

        $orderChannel = $order->getChannel();

        $orderCurrencyCode = $order->getCurrencyCode() ?? $orderChannel?->getBaseCurrency()?->getCode() ?? '';
        $orderLocaleCode = $order->getLocaleCode() ?? $orderChannel?->getDefaultLocale()?->getCode();

        $orderTotal = $this->moneyFormatter->format(
            amount: $order->getTotal(),
            currencyCode: $orderCurrencyCode,
            locale: $orderLocaleCode,
        );

        $orderItemsTotal = $this->moneyFormatter->format(
            amount: $order->getItemsTotal(),
            currencyCode: $orderCurrencyCode,
            locale: $orderLocaleCode,
        );

        $orderTaxTotal = $this->moneyFormatter->format(
            amount: $order->getTaxTotal(),
            currencyCode: $orderCurrencyCode,
            locale: $orderLocaleCode,
        );

        $orderTaxIncludedTotal = $this->moneyFormatter->format(
            amount: $order->getTaxIncludedTotal(),
            currencyCode: $orderCurrencyCode,
            locale: $orderLocaleCode,
        );

        $orderTaxExcludedTotal = $this->moneyFormatter->format(
            amount: $order->getTaxExcludedTotal(),
            currencyCode: $orderCurrencyCode,
            locale: $orderLocaleCode,
        );

        $orderShippingTotal = $this->moneyFormatter->format(
            amount: $order->getShippingTotal(),
            currencyCode: $orderCurrencyCode,
            locale: $orderLocaleCode,
        );

        $orderPromotionTotal = $this->moneyFormatter->format(
            amount: $order->getOrderPromotionTotal(),
            currencyCode: $orderCurrencyCode,
            locale: $orderLocaleCode,
        );

        $orderPaymentMethods = $order->getPayments()->map(function ($payment) {
            return $payment->getMethod()?->getName();
        })->filter(fn (?string $name) => null !== $name);

        $orderShippingMethods = $order->getShipments()->map(function ($shipment) {
            return $shipment->getMethod()?->getName();
        })->filter(fn (?string $name) => null !== $name);

        return NotificationEventVariables::create(
            new NotificationEventVariable(
                name: new NotificationEventVariableName('order_number'),
                value: new NotificationEventVariableValue($order->getNumber()),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('order_total_quantity'),
                value: new NotificationEventVariableValue($order->getTotalQuantity()),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('order_total'),
                value: new NotificationEventVariableValue($orderTotal),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('order_items_total'),
                value: new NotificationEventVariableValue($orderItemsTotal),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('order_tax_total'),
                value: new NotificationEventVariableValue($orderTaxTotal),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('order_tax_included_total'),
                value: new NotificationEventVariableValue($orderTaxIncludedTotal),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('order_tax_excluded_total'),
                value: new NotificationEventVariableValue($orderTaxExcludedTotal),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('order_shipping_total'),
                value: new NotificationEventVariableValue($orderShippingTotal),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('order_promotion_total'),
                value: new NotificationEventVariableValue($orderPromotionTotal),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('order_currency_code'),
                value: new NotificationEventVariableValue($order->getCurrencyCode()),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('order_promotion_coupon_code'),
                value: new NotificationEventVariableValue($order->getPromotionCoupon()?->getCode()),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('customer_first_name'),
                value: new NotificationEventVariableValue($order->getBillingAddress()?->getFirstName()),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('customer_last_name'),
                value: new NotificationEventVariableValue($order->getBillingAddress()?->getLastName()),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('order_channel'),
                value: new NotificationEventVariableValue($order->getChannel()?->getName()),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('order_notes'),
                value: new NotificationEventVariableValue($order->getNotes()),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('order_payment_methods'),
                value: new NotificationEventVariableValue(implode(', ', $orderPaymentMethods->toArray())),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('order_shipping_methods'),
                value: new NotificationEventVariableValue(implode(', ', $orderShippingMethods->toArray())),
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
                name: new NotificationEventVariableName('order_total_quantity'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Order total quantity'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('order_total'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Order total'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('order_items_total'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Order items total'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('order_tax_total'),
                defaultValue: new NotificationEventVariableValue(''),
                description: new NotificationEventVariableDescription('Order tax total'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('order_tax_included_total'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Order tax included total'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('order_tax_excluded_total'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Order tax excluded total'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('order_shipping_total'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Order shipping total'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('order_promotion_total'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Order promotion total'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('order_currency_code'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Order currency code'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('order_promotion_coupon_code'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Order promotion coupon code'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('customer_first_name'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Customer first name'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('customer_last_name'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Customer last name'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('order_channel'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Order channel'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('order_notes'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Order notes'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('order_payment_methods'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Order payment methods'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('order_shipping_methods'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Order shipping methods'),
            ),
        );
    }

    private function getOrderFromContext(NotificationContext $context): OrderInterface
    {
        $subject = $context->getSubject();

        if (false === $subject instanceof OrderInterface) {
            throw new \InvalidArgumentException('Subject is not a OrderInterface');
        }

        return $subject;
    }
}
