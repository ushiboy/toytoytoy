<?php
namespace ToyToyToy;

class Config
{
    static function get()
    {
        $environment = getenv('ENV') ?? 'development';
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
                ]
            ]
        ];
    }
};
