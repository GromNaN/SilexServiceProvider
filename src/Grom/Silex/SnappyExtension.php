<?php

namespace Grom\Silex;

use Silex\Application;
use Silex\ExtensionInterface;
use Knplabs\Snappy\Image;
use Knplabs\Snappy\Pdf;

/**
 * Silex extension to integrate Snappy library.
 *
 * @author Jérôme Tamarelle <jerome@tamarelle.net>
 */
class SnappyExtension implements ExtensionInterface
{
    public function register(Application $app)
    {
        $app['snappy.image'] = $app->share(function () use ($app) {
            return new Image($app['snappy.image_binary']);
        });

        $app['snappy.pdf'] = $app->share(function () use ($app) {
            return new Pdf($app['snappy.pdf_binary']);
        });

        if (isset($app['snappy.class_path'])) {
            $app['autoloader']->registerNamespace('Knplabs\\Snappy', $app['snappy.class_path']);
        }
    }
}
