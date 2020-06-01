KikwikApiAdminBundle
=================

Admin client for api-platform endpoint based on Hydra Core Vocabulary.

WARNING: this bundle is still under development! do not use it!


Installation
------------

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require kikwik/api-admin-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Kikwik\ApiAdminBundle\KikwikApiAdminBundle::class => ['all' => true],
];
```

Configuration
-------------

Create the `config/packages/kikwik_api_admin.yaml` config file, set the api endpoint

```yaml
kikwik_api_admin:
    api_endpoint: https://demo.api-platform.com
```

Import admin routes in `config/routes.yaml`:

```yaml
kikwik_api_admin_bundle_dashboard:
    resource: '@KikwikApiAdminBundle/Resources/config/routes.xml'
    prefix: '/kw-api-admin'
```