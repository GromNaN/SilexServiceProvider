<?php

namespace Grom\Silex;

use Silex\ServiceProviderInterface;
use Silex\Application;

/**
 * Silex service provider to connect with your FluxBB forum.
 *
 * @author Jérôme Tamarelle <jerome@tamarelle.net>
 */
class FluxbbServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        if (!defined('PUN_ROOT')) {
            if (isset($app['fluxbb.base_path'])) {
                define('PUN_ROOT', realpath($app['fluxbb.base_path']).'/');
            } else {
                throw new \InvalidParameterException('You must register "fluxbb.base_path" with the base path of your installation of FluxBB');
            }
        }

        // Current user infos
        $app['fluxbb.user'] = $app->share(function ($app)
        {
            foreach($app['fluxbb.config'] as $name => $value)
                $GLOBALS[$name] = $value;
            $GLOBALS['db'] = $app['fluxbb.db'];

            require_once PUN_ROOT.'/include/functions.php';

            define('PUN_UNVERIFIED', 0);
            define('PUN_ADMIN', 1);
            define('PUN_MOD', 2);
            define('PUN_GUEST', 3);
            define('PUN_MEMBER', 4);

            $user = array();
            check_cookie($user);

            return $user;
        });

        // DBLayer: database abstraction
        // @link http://fluxbb.org/docs/v1.4/dblayer
        $app['fluxbb.db'] = $app->share(function ($app)
        {
            $config = $app['fluxbb.config'];

            require_once PUN_ROOT.'/include/dblayer/'.$config['db_type'].'.php';

            return new \DBLayer(
                $config['db_host'],
                $config['db_username'],
                $config['db_password'],
                $config['db_name'],
                $config['db_prefix'],
                $config['p_connect']);
        });

        // Fluxbb configuration from config.php
        if(!isset($app['fluxbb.config'])) {
            $app['fluxbb.config'] = $app->share(function ()
            {
                require_once PUN_ROOT.'/config.php';
                require_once PUN_ROOT.'/cache/cache_config.php';

                return compact(
                    'db_type',
                    'db_host',
                    'db_name',
                    'db_username',
                    'db_password',
                    'db_prefix',
                    'p_connect',
                    'cookie_name',
                    'cookie_domain',
                    'cookie_path',
                    'cookie_secure',
                    'cookie_seed',
                    'pun_config');
            });
        }
    }
}

