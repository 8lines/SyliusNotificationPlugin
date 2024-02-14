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
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class CustomerRegisteredNotificationEvent implements NotificationEventInterface
{
    public function __construct(
        private ChannelContextInterface $channelContext,
        private LocaleContextInterface $localeContext,
    ) {
    }

    public static function getEventName(): string
    {
        return 'sylius.customer.post_register';
    }

    public function getEventPayload(NotificationContext $context): NotificationEventPayload
    {
        $customer = $this->getCustomerFromContext($context);

        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();

        $localeCode = $this->localeContext->getLocaleCode();

        return NotificationEventPayload::create(
            syliusInvoker: $customer,
            syliusChannel: $channel,
            localeCode: $localeCode,
        );
    }

    public function getVariables(NotificationContext $context): NotificationEventVariables
    {
        $customer = $this->getCustomerFromContext($context);

        return NotificationEventVariables::create(
            new NotificationEventVariable(
                name: new NotificationEventVariableName('customer_id'),
                value: new NotificationEventVariableValue($customer->getId()),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('customer_first_name'),
                value: new NotificationEventVariableValue($customer->getFirstName()),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('customer_last_name'),
                value: new NotificationEventVariableValue($customer->getLastName()),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('customer_email'),
                value: new NotificationEventVariableValue($customer->getEmail()),
            ),
            new NotificationEventVariable(
                name: new NotificationEventVariableName('customer_phone_number'),
                value: new NotificationEventVariableValue($customer->getPhoneNumber()),
            ),
        );
    }

    public function getVariableDefinitions(): NotificationEventVariableDefinitions
    {
        return NotificationEventVariableDefinitions::create(
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('customer_id'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Customer ID'),
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
                name: new NotificationEventVariableName('customer_email'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Customer email'),
            ),
            new NotificationEventVariableDefinition(
                name: new NotificationEventVariableName('customer_phone_number'),
                defaultValue: new NotificationEventVariableValue('-'),
                description: new NotificationEventVariableDescription('Customer phone number'),
            ),
        );
    }

    private function getCustomerFromContext(NotificationContext $context): CustomerInterface
    {
        $subject = $context->getSubject();

        if (false === $subject instanceof CustomerInterface) {
            throw new \InvalidArgumentException('Subject is not a CustomerInterface');
        }

        return $subject;
    }
}
