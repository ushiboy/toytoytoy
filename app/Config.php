<?php
namespace ToyToyToy;

use Monolog\Logger;

class Config
{
    public static function get()
    {
        $environment = getenv('ENV');
        if (strlen($environment) === 0) {
            $environment = 'development';
        }
        return [
            'settings' => [
                'determineRouteBeforeAppMiddleware' => false,
                'displayErrorDetails' => true,
                'db' => [
                    'driver' => 'sqlite',
                    'database' => __DIR__."/../db/$environment.sqlite"
                ],
                'view' => [
                    'template_path' => __DIR__.'/templates',
                    'twig' => [
                        'cache' => __DIR__.'/../cache/twig',
                        'debug' => true,
                        'auto_reload' => true
                    ]
                ],
                'logger' => [
                    'name' => 'main',
                    'log_path' => __DIR__.'/../log/app.log',
                    'level' => Logger::DEBUG
                ]
            ]
        ];
    }
}
