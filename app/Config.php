<?php
namespace ToyToyToy;

use Monolog\Logger;
use Dotenv\Dotenv;

class Config
{
    public static function get()
    {
        if (file_exists(__DIR__.'/../.env')) {
            $dotenv = new Dotenv(__DIR__.'/../');
            $dotenv->load();
        }

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
                ],
                'mail' => [
                    'host' => getenv('SMTP_HOST'),
                    'port' => getenv('SMTP_PORT'),
                    'user' => getenv('SMTP_USER'),
                    'password' => getenv('SMTP_PASSWORD'),
                    'encryption' => getenv('SMTP_ENCRYOTION'),
                    'from_address' => getenv('SMTP_FROM_ADDRESS'),
                    'from_name' => getenv('SMTP_FROM_NAME')
                ]
            ]
        ];
    }
}
