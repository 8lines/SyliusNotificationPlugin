<p align="center">
    <a href="https://8lines.io">
        <img alt="8lines" src="https://8lines-static.s3.eu-central-1.amazonaws.com/open-source-logo-main.png">
    </a>
</p>

# SyliusNotificationPlugin

--- 

## Custom Notification Event
To create a custom Notification Event, you need to create a class that implements `NotificationEventInterface`.

```php
<?php

declare(strict_types=1);

namespace ...;

use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationContext;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventInterface;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventPayload;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariableDefinitions;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariables;

final class CustomNotificationEvent implements NotificationEventInterface
{

    public static function getEventName(): string
    {
        // TODO: Implement getEventName() method.
    }

    public function getEventPayload(NotificationContext $context): NotificationEventPayload
    {
        // TODO: Implement getEventPayload() method.
    }

    public function getVariables(NotificationContext $context): NotificationEventVariables
    {
        // TODO: Implement getVariables() method.
    }

    public function getVariableDefinitions(): NotificationEventVariableDefinitions
    {
        // TODO: Implement getVariableDefinitions() method.
    }
}
```

### getEventName()
This method is used to get the name of the Notification Event.
It should return a string that represents the exact name of the event supported by the Symfony EventDispatcher.

```php

### getEventPayload()
This method is used to get the payload of the Notification Event.

It takes one argument [$context](https://github.com/8lines/SyliusNotificationsPlugin/blob/main/src/NotificationEvent/NotificationContext.php) that contains event name and event subject.

Finally, it should return a [NotificationEventPayload](https://github.com/8lines/SyliusNotificationsPlugin/blob/main/src/NotificationEvent/NotificationEventPayload.php) object:

```php
public function getEventPayload(NotificationContext $context): NotificationEventPayload
{
    return NotificationEventPayload::create(
        syliusTarget: /* ... */,
        syliusChannel: /* ... */,
        localeCode: /* ... */,
    );
}
```

- `syliusTarget` - User that is the target of the event.
- `syliusChannel` - The Sylius channel where the event was triggered.
- `localeCode` - The locale code of the event.

### getVariables()
This method is used to get the prepared variables of the Notification Event.

It takes [$context](https://github.com/8lines/SyliusNotificationsPlugin/blob/main/src/NotificationEvent/NotificationContext.php) and should return a [NotificationEventVariables](https://github.com/8lines/SyliusNotificationsPlugin/blob/main/src/NotificationEvent/NotificationEventVariables.php) object:

```php
public function getVariables(NotificationContext $context): NotificationEventVariables
{
    return NotificationEventVariables::create(
        new NotificationEventVariable(
            name: new NotificationEventVariableName(/* ... */),
            value: new NotificationEventVariableValue(/* ... */),
        ),
        // ...
    );
}
```

You should return all the variables that you specified in the `getVariableDefinitions` method.

### getVariableDefinitions()
This method is used to get the variable definitions of the Notification Event.

It should return a [NotificationEventVariableDefinitions](https://github.com/8lines/SyliusNotificationsPlugin/blob/main/src/NotificationEvent/NotificationEventVariableDefinitions.php):

```php
public function getVariableDefinitions(): NotificationEventVariableDefinitions
{
    return NotificationEventVariableDefinitions::create(
        new NotificationEventVariableDefinition(
            name: new NotificationEventVariableName(/* ... */),
            defaultValue: new NotificationEventVariableValue(/* ... */),
            description: new NotificationEventVariableDescription(/* ... */),
        ),
        // ...
    );
}
```

You should return all the variables that should be available to use by users in the notification configuration.
