<p align="center">
    <a href="https://8lines.io">
        <img alt="8lines" src="https://8lines-static.s3.eu-central-1.amazonaws.com/open-source-logo-main.png">
    </a>
</p>

# SyliusNotificationPlugin

--- 

## Plugin installation

To install the plugin you need to run the following command:

```bash
$ composer require 8lines/sylius-notification-plugin
```

Then you need to add the plugin to your `config/bundles.php` file:

```php
return [
    // ...
    EightLines\SyliusNotificationPlugin\EightLinesSyliusNotificationPlugin::class => ['all' => true],
];
```

After that you need to import required config in your `config/packages/_sylius.yaml` file:

```yaml
imports:
    ...
    - { resource: "@EightLinesSyliusNotificationPlugin/config/app/config.yml" }
```

And import routing in your `config/routes.yaml` file:

```yaml
sylius_notification_plugin_admin:
    resource: "@EightLinesSyliusNotificationPlugin/config/routing/admin_routing.yml"
    prefix: /admin
```

Finish the installation by updating the database schema and installing assets:

```bash
$ bin/console cache:clear
$ bin/console doctrine:migrations:migrate
$ bin/console assets:install
```

But this is not the end! You need to add the plugin's assets to your project. 

## Importing assets

To start using the plugin with webpack, you need to import the plugin's `webpack.config.js` file to your project.
You need to open your `webpack.config.js` file and add the following code:

```js
const [ syliusNotificationPluginAdmin ] = require('./vendor/8lines/sylius-notification-plugin/webpack.config.js')

// ...

module.exports = [..., syliusNotificationPluginAdmin]
```

Then you need to add new package in `config/packages/assets.yaml`:

```yml
framework:
    assets:
        packages:
            # ...
            sylius_notification_plugin_admin:
                json_manifest_path: '%kernel.project_dir%/public/build/8lines/sylius-notification-plugin/admin/manifest.json'
```

And add new build path in `config/packages/webpack_encore.yml`:

```yml
webpack_encore:
    builds:
        # ...
        sylius_notification_plugin_admin: '%kernel.project_dir%/public/build/8lines/sylius-notification-plugin/admin'
```

Finally, you need to add encore function to your `@SyliusAdminBundle/_scripts.html.twig` template:

```twig
{# ... #}

{{ encore_entry_script_tags('8lines-sylius-notification-plugin-admin', null, 'sylius_notification_plugin_admin') }}
```

And run `yarn encore dev` or `yarn encore production`.
