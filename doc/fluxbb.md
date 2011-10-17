# Fluxbb Service Provider for Silex

The FluxbbServiceProvider provides integration with [Fluxbb](http://fluxbb.org/) in [Silex](http://silex-project.org/).

## Parameters

* __fluxbb.base_path:__ Absolute path of your Fluxbb installation.

## Services

* __fluxbb.user:__ Give information about the user currently connected.
* __fluxbb.db:__ Database layer of FluxBB, can be used to access the forum data or your own data.

## Registering

You need a valid installation of Fluxbb, version 1.4

Go to your Silex application directory and clone the Git repository into `vendor`.

```
git submodule add git://github.com/GromNaN/GromSilexExtensions.git vendor/GromSilexExtensions
```

Register the provider in your Silex application.

```php
$app['autoloader']->registerNamespace('Grom\\Silex', __DIR__.'/vendor/GromSilexExtensions/src/');

$app->register(new Grom\Silex\FluxbbServiceProvider(), array(
    'flux.base_path' => 'path/to/fluxbb',
));
```

## Usage

````php
$app->get('/hello', function() use ($app) {
    $user = $app['fluxbb.user'];

    if ($user) {
        return 'Hello '.$user['username'];
    } else {
        return 'Hello anonymous';
    }
});
```
