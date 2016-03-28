<?php
namespace ToyToyToy;

use ToyToyToy\Model\User;

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

        $container['auth'] = function ($c) {
            return new \SlimAuth\Auth(function ($id, $request) use ($c) {
                if ($id !== null) {
                    return User::findAndFill($id);
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
