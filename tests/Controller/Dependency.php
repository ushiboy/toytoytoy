<?php
namespace ToyToyToy\Tests\Controller;

use ToyToyToy\Model\User;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use ToyToyToy\Exception\RequestErrorException;

class Dependency
{

    public static $storage = [];

    public static function apply(\Slim\App $app)
    {

        $container = $app->getContainer();
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
            return new \Slim\Flash\Messages(self::$storage);
        };

        \Swift::init(function () {
            \Swift_DependencyContainer::getInstance()
                ->register('mime.qpheaderencoder')
                ->asAliasOf('mime.base64headerencoder');
            \Swift_Preferences::getInstance()->setCharset('iso-2022-jp');
        });

        $container['mail'] = function($c) {
            $settings = $c->get('settings')['mail'];
            $transport = \Swift_SmtpTransport::newInstance($settings['host'], $settings['port'])
                ->setUsername($settings['user'])
                ->setPassword($settings['password'])
                ->setEncryption($settings['encryption']);
            return \Swift_Mailer::newInstance($transport);
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
    }
}
