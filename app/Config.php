<?php
namespace ToyToyToy;

class Config
{
    static function get()
    {
        return [
            'settings' => [
                'determineRouteBeforeAppMiddleware' => false,
                'displayErrorDetails' => true,
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
