<p align="center">
    <a href="https://8lines.io">
        <img alt="8lines" src="https://8lines-static.s3.eu-central-1.amazonaws.com/open-source-logo-main.png">
    </a>
</p>

# SyliusNotificationPlugin

--- 

## Notification Channels
Notification channels are implementations of how to send a specific notification.
Plugin offers a few basic channels, but you can also implement your own.

## Available channels
- [Audit Log (*)](#audit-log)
- [Mailer (*)](#mailer)
- [Slack](#slack)
- [Twilio](#twilio)
- [Ovh Cloud](#ovh-cloud)

*(\*) - included in the plugin out of the box*

## Audit Log
Audit Log channel is used to log all notifications to the database. 
It's useful for debugging and monitoring purposes. 
From the admin panel, you can see all notifications that were sent.

During adding action with the Audit Log channel, you will be asked to fill one additional field:
- `Message` - field that allows you to specify the message of the notification.

## Mailer
Mailer channel is used to send notifications via email. 
It uses the Symfony Mailer component.

Mailer offers these configuration fields during adding the action:
- `Email From` - email address from which the notification will be sent
- `Template` - optional field that allows you to specify the template that will be used to send the notification.
- `Subject` - field that allows you to specify the subject of the email.
- `Message` - optional field that is used if the template is not specified. It allows you to specify the message of the email.

## Slack
Slack channel is used to send notifications to Slack.
It uses Symfony Notifier component.

You need to install additional package to use it:
```bash
$ composer require 8lines/sylius-notification-plugin-slack-adapter
```

You can find more information about the package in the [documentation](https://github.com/8lines/sylius-notification-plugin-slack-adapter).

## Twilio
Twilio channel is used to send notifications via SMS.
It uses Symfony Notifier component.

You need to install additional package to use it:
```bash
$ composer require 8lines/sylius-notification-plugin-twilio-adapter
```

You can find more information about the package in the [documentation](https://github.com/8lines/sylius-notification-plugin-twilio-adapter).

## Ovh Cloud
Ovh Cloud channel is used to send notifications via SMS.
It uses Symfony Notifier component.

You also need to install additional package to use it:
```bash
$ composer require 8lines/sylius-notification-plugin-ovh-cloud-adapter
```

You can find more information about the package in the [documentation](https://github.com/8lines/sylius-notification-plugin-ovh-cloud-adapter).
