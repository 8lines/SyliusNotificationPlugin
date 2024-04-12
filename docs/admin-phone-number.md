<p align="center">
    <a href="https://8lines.io">
        <img alt="8lines" src="https://8lines-static.s3.eu-central-1.amazonaws.com/open-source-logo-main.png">
    </a>
</p>

# SyliusNotificationPlugin

--- 

## Admin phone number
By default, Sylius doesn't store the phone number of the admin.
If you want to send notifications to the admin via SMS, you need to provide somewhere the phone number.
You can achieve this by adding a new parameter to the container.

Edit `config/packages/_sylius.yaml` and add the following configuration:

```yaml
parameters:
    ...
    eightlines_sylius_notification_plugin.admin_phone_numbers:
        '<id>': '<phone_number>'
        ...
```

Replace `<id>` with the ID of the admin and `<phone_number>` with the phone number.
This way you can configure as many administrators as you want.
