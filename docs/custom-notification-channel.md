<p align="center">
    <a href="https://8lines.io">
        <img alt="8lines" src="https://8lines-static.s3.eu-central-1.amazonaws.com/open-source-logo-main.png">
    </a>
</p>

# SyliusNotificationPlugin

--- 

## Custom Notification Channel
To create a custom Notification Channel, you need to create a class that implements `NotificationChannelInterface`.

```php
<?php

declare(strict_types=1);

namespace ...;

use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationBody;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationChannelInterface;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationContext;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationRecipient;

final class CustomNotificationChannel implements NotificationChannelInterface
{

    public function send(
        ?NotificationRecipient $recipient, 
        NotificationBody $body, 
        NotificationContext $context,
    ): void {
        // TODO: Implement send() method.
    }

    public static function getIdentifier(): string
    {
        // TODO: Implement getIdentifier() method.
    }

    public static function supportsUnknownRecipient(): bool
    {
        // TODO: Implement supportsUnknownRecipient() method.
    }

    public static function getConfigurationFormType(): ?string
    {
        // TODO: Implement getConfigurationFormType() method.
    }
}
```

### send()
This method is used to send the notification. It takes three arguments:
- [$recipient](https://github.com/8lines/SyliusNotificationsPlugin/blob/main/src/NotificationChannel/NotificationRecipient.php) - The recipient of the notification. It can be null if the recipient is unknown.
- [$body](https://github.com/8lines/SyliusNotificationsPlugin/blob/main/src/NotificationChannel/NotificationBody.php) - The body of the notification. It contains the subject and the message of the notification.
- [$context](https://github.com/8lines/SyliusNotificationsPlugin/blob/main/src/NotificationChannel/NotificationContext.php) - The context of the notification. It contains all the information about the notification.

### getIdentifier()
This method is used to get the identifier of the Notification Channel.

### supportsUnknownRecipient()
This method is used to check if the Notification Channel supports unknown recipients.

### getConfigurationFormType()
This method is used to get the configuration form type of the Notification Channel.
There are several predefined configuration form types:
- [MessageWithRecipientType](https://github.com/8lines/SyliusNotificationsPlugin/blob/main/src/Form/Type/NotificationChannel/MessageWithRecipientType.php) - A form type that allows you to send a specified message to a specified recipient.
- [MessageWithoutRecipientType](https://github.com/8lines/SyliusNotificationsPlugin/blob/main/src/Form/Type/NotificationChannel/MessageWithoutRecipientType.php) - A form type that allows you to send a specified message without the need to configure the recipient.
- [TitledMessageWithRecipientType](https://github.com/8lines/SyliusNotificationsPlugin/blob/main/src/Form/Type/NotificationChannel/TitledMessageWithRecipientType.php) - A form type that allows you to send a titled message to a specified recipient.
- [TitledMessageWithoutRecipientType](https://github.com/8lines/SyliusNotificationsPlugin/blob/main/src/Form/Type/NotificationChannel/TitledMessageWithoutRecipientType.php) - A form type that allows you to send a titled message without the need to configure the recipient.
- [RecipientWithoutMessageType](https://github.com/8lines/SyliusNotificationsPlugin/blob/main/src/Form/Type/NotificationChannel/RecipientWithoutMessageType.php) - A form type that allows you to send a message to a recipient but without providing the message during the configuration.

Of course, you can [create your own configuration form type](./custom-configuration-form-type.md).

## Registering the custom Notification Channel
Service that holds the custom Notification Channel needs to be registered in the service container. 
It also needs to be tagged with `eightlines_sylius_notification_plugin.notification_channel` tag.
By default, if autoconfiguration is enabled, the service will be automatically tagged with the `eightlines_sylius_notification_plugin.notification_channel` tag.
If not, you need to add the tag manually.
