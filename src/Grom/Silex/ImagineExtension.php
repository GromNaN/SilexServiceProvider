<?php

namespace Grom\Silex;

use Silex\Application;
use Silex\ExtensionInterface;
use Symfony\Component\HttpFoundation\Response;
use Imagine\Filter\Transformation;

/**
 * Silex extension to integrate Imagine library.
 *
 * @author Jérôme Tamarelle <jerome@tamarelle.net>
 */
class ImagineExtension implements ExtensionInterface
{
    public function register(Application $app)
    {
        if(!isset($app['imagine.factory'])) {
            $app['imagine.factory'] = 'Gd';
        }

        $app['imagine'] = $app->share(function () use ($app) {
            $class = sprintf('\Imagine\%s\Imagine', $app['imagine.factory']);
            return new $class();
        });

        if (isset($app['imagine.class_path'])) {
            $app['autoloader']->registerNamespace('Imagine', $app['imagine.class_path']);
        }
    }
}
