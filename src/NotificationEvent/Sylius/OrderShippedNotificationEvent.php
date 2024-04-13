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
use Sylius\Component\Core\Model\ShipmentInterface;

final class OrderShippedNotificationEvent implements NotificationEventInterface
{
    public function __construct(
        private MoneyFormatterInterface $moneyFormatter,
    ) {
    }

    public static function getEventName(): string
    {
        return 'sylius.shipment.post_ship';
    }

    public function getEventPayload(NotificationContext $context): NotificationEventPayload
    {
        $shipment = $this->getShipmentFromContext($context);
        $order = $this->getOrderFromShipment($shipment);
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
        $shipment = $this->getShipmentFromContext($context);
        $order = $this->getOrderFromShipment($shipment);

        $shippingUnitTotal = $this->moneyFormatter->format(
            amount: $shipment->getShippingUnitTotal(),
            currencyCode: $order->getCurrencyCode() ?? $order->getChannel()?->getBaseCurrency()?->getCode() ?? '',
            locale: $order->getLocaleCode() ?? $order->getChannel()?->getDefaultLocale()?->getCode(),
        );

        return NotificationEventVariables::create(
            new NotificationEventVariable(
                name: new NotificationEventVariableName('order_number'),
                value: new NotificationEventVariableValue($order->getNumber()),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('shipment_method'),
                value: new NotificationEventVariableValue($shipment->getMethod()?->getName()),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('shipment_shipped_at'),
                value: new NotificationEventVariableValue($shipment->getShippedAt()?->format('Y-m-d H:i:s')),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('shipment_tracking'),
                value: new NotificationEventVariableValue($shipment->getTracking()),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('shipment_shipping_volume'),
                value: new NotificationEventVariableValue($shipment->getShippingVolume()),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('shipment_shipping_weight'),
                value: new NotificationEventVariableValue($shipment->getShippingWeight()),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('shipment_shipping_unit_total'),
                value: new NotificationEventVariableValue($shippingUnitTotal),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('shipment_shipping_unit_count'),
                value: new NotificationEventVariableValue($shipment->getShippingUnitCount()),
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
                name: new NotificationEventVariableName('shipment_method'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Shipment method'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('shipment_shipped_at'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Shipped at'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('shipment_tracking'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Shipment tracking'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('shipment_shipping_volume'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Shipping volume'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('shipment_shipping_weight'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Shipping weight'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('shipment_shipping_unit_total'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Value of items in the shipment'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('shipment_shipping_unit_count'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Number of items in the shipment'),
            ),
        );
    }

    private function getShipmentFromContext(NotificationContext $context): ShipmentInterface
    {
        $subject = $context->getSubject();

        if (false === $subject instanceof ShipmentInterface) {
            throw new \InvalidArgumentException('Subject is not a ShipmentInterface');
        }

        return $subject;
    }

    private function getOrderFromShipment(ShipmentInterface $shipment): OrderInterface
    {
        $order = $shipment->getOrder();

        if (null === $order) {
            throw new \InvalidArgumentException('Shipment has no order');
        }

        return $order;
    }
}
