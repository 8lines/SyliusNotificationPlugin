## Usage
- [Creating new notification](#creating-new-notification)
- [Adding new rule](#adding-new-rule)
- [Adding new action](#adding-new-action)
- [Variables](#variables)
- [Audit Logs](#audit-logs)

## Creating new notification
To create a new notification, go to the admin panel and click on the "Notifications" tab. 
Then click on the "Create" button. 
You will be redirected to the form where you can create a new notification.

You will be able to specify `code`, `event` and `channels`.
Complete it according to your needs.

Below you will see the `Actions & Rules` section.
There are three main parts:
- `Variables` - list of all available variables that you can use in the notification configuration.
- `Rules` - place where you can define rules that will filter the notification.
- `Actions` - place where you can define actions that will be executed when the notification is triggered.

After you complete the form, click on the "Save" button.

## Adding new rule
To add a new rule, click on the `Add` button under the `Rules` section.

After that you will see a new form where you can define 3 fields:
- `Variable name` - fill it with the name of the available variable. It shouldn't contain `{` and `}`.
- `Operator` - choose the operator that will be used to compare the variable with the value.
- `Comparable value` - fill it with the value that will be used to compare the variable.

Then notification will be triggered only if all rules are met.

## Adding new action
To add a new action, click on the `Add` button under the `Actions` section.

After that you will see a new form where you will be able to choose the 'Notification Channel'.
Choose the channel that you want to use to send the notification.

Then you will be able to fill the form with the configuration of the notification channel.
You will see several fields that you can fill according to your needs.

It will be different for each channel, so you need to check the documentation of the channel that you want to use.

## Variables
Variables are used to pass the data to the notification.
The available variables are listed in the `Variables` section of the notification form.
They are used in the `Rules` and `Actions` sections.

In the `Rules` section, you can use them to filter the notification.

In the `Actions` section, you can use them in the subject or message of the notification to pass the data.
If you want to use the variable in the message, you need to use the `{variable_name}` syntax.

## Audit Logs
All notifications that are sent to `audit-log` channel are stored in the database.
You can see them in the admin panel in the `Audit Logs` tab.

There you will see the list of all notifications that were sent.
You can also click `Show` button to see the details of the notification.

You cannot delete or edit the Audit Log from the admin panel.
