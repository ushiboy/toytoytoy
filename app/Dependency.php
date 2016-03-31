<?php
namespace ToyToyToy;

use ToyToyToy\Model\User;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Dependency
{
    public static function apply(\Slim\Container $container)
    {
        $container['view'] = function ($c) {
            $settings = $c->get('settings')['view'];
            $view = new \Slim\Views\Twig($settings['template_path'], $settings['twig']);
            $view->addExtension(new \Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
            $view->addExtension(new \Twig_Extension_Debug());
            return $view;
        };

        $container['csrf'] = function ($c) {
            return new \Slim\Csrf\Guard();
        };

        $container['flash'] = function () {
            return new \Slim\Flash\Messages();
        };

        $container['logger'] = function ($c) {
            $settings = $c->get('settings')['logger'];
            $logger = new Logger($settings['name']);
            $logger->pushHandler(new StreamHandler($settings['log_path'], $settings['level']));
            return $logger;
        };

        $container['auth'] = function ($c) {
            return new \SlimAuth\Auth(function ($id, $request) use ($c) {
                if ($id !== null) {
                    return User::find($id);
                }
                $rememberToken = $c->get('cookie')->get('remember_token');
                if ($rememberToken !== null) {
                    return User::findByRememberToken($rememberToken);
                }
                return null;
            });
        };

        $container['cookie'] = function ($c) {
            $request = $c->get('request');
            return new \Slim\Http\Cookies($request->getCookieParams());
        };

        $capsule = new \Illuminate\Database\Capsule\Manager();
        $capsule->addConnection($container->get('settings')['db']);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}
