<?php
namespace ToyToyToy;

class Dependency
{
    static function apply(\Slim\Container $container)
    {
        $container['view'] = function($c) {
            $settings = $c->get('settings')['view'];
            $view = new \Slim\Views\Twig($settings['template_path'], $settings['twig']);
            $view->addExtension(new \Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
            $view->addExtension(new \Twig_Extension_Debug());
            return $view;
        };

        $container['csrf'] = function($c) {
            return new \Slim\Csrf\Guard();
        };

        $capsule = new \Illuminate\Database\Capsule\Manager();
        $capsule->addConnection($container->get('settings')['db']);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}
