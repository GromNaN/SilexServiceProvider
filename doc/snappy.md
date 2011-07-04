# Snappy Extension for Silex

The SnappyExtension provides integration with [Snappy](https://github.com/knplabs/snappy/) in [Silex](http://silex-project.org/).

## Parameters

* __snappy.image_binary:__ Absolute path to `wkhtmltoimage`.
* __snappy.pdf_binary:__ Absolute path to `wkhtmltopdf`.
* __snappy.class_path:__ (optional) Path to where the Snappy library is located.

## Services

* __snappy.image:__ Snappy service to create image snapshots / thumbnails.
* __snappy.pdf:__ Snappy service to create pdf.

## Registering

Make sure you place a copy of Snappy in the `vendor/snappy` directory and a copy of SnappyExtension in `vendor/snappy-extension`.

```
git submodule add git://github.com/knplabs/snappy.git vendor/snappy
git submodule add git://github.com/GromNaN/GromSilexExtensions.git vendor/grom-silex
```

```php
$app['autoloader']->registerNamespace('Grom\\Silex', __DIR__.'/vendor/grom-silex/src/');

$app->register(new Grom\Silex\SnappyExtension(), array(
    'snappy.image_binary' => '/usr/local/bin/wkhtmltoimage',
    'snappy.pdf_binary'   => '/usr/local/bin/wkhtmltopdf',
    'snappy.class_path'   => __DIR__.'/vendor/snappy/src',
));
```

## Usage

````php
use Symfony\Component\HttpFoundation\Response;

$app->get('/snapshot', function() use ($app) {
    $url = $app['request']->get('url');
    $image = $app['snappy.image']->get($url);

    $response = new Response();
    $response->headers->set('Content-Type', 'image/jpeg');
    $response->setContent($image);

    return $response;
});
```

This will convert the given url into an image. Try `/snapshot?url=http://www.github.com`
