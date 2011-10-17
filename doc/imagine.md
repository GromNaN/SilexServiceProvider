# Imagine Service Provider for Silex

The ImagineServiceProvider provides integration with [Imagine](https://github.com/avalanche123/Imagine)
in [Silex](http://silex-project.org/).

## Parameters

* __imagine.factory (optional):__ Image factory to use (_Gd_, Gmagick, Imagick)
* __imagine.class_path (optional):__ Path to where the Imagine library is located.

## Services

* __imagine:__ Imagine service to load/create images ([see the API](http://imagine.readthedocs.org/en/latest/introduction.html#basic-usage))

## Registering

Make sure you place a copy of _Imagine_ in the `vendor/imagine directory.

```
git submodule add git://github.com/GromNaN/GromSilexExtensions.git vendor/GromSilexExtensions
git submodule add git://github.com/avalanche123/Imagine.git vendor/imagine
```

Register the service provider in your Silex application.

```php
$app['autoloader']->registerNamespace('Grom\\Silex', __DIR__.'/vendor/GromSilexExtensions/src/');

$app->register(new Grom\Silex\ImagineServiceProvider(), array(
    'imagine.factory' => 'Gd',
    'imagine.base_path' => __DIR__.'/vendor/imagine',
));
```

## Usage

The Imagine service provider provide an `imagine` service.

```php
$app->get('/thumb/{file}', function($file) use ($app) {
    $image = $app['imagine']->open('images/'.$file);

    $transformation = new Imagine\Filter\Transformation();
    $transformation->thumbnail(new Imagine\Image\Box(200, 200));
    $image = $transformation->apply($image);

    $format = pathinfo($file, PATHINFO_EXTENSION);

    $response = new Symfony\Component\HttpFoundation\Response();
    $response->headers->set('Content-type', 'image/'.$format);
    $response->setContent($image->get($format));

    return $response;
});
```

Put some images inside a directory name `images`. This will create a thumbnail
of the image with a size of 200px.
