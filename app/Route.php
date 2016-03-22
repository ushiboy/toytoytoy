<?php
namespace ToyToyToy;

class Route
{
    public static function registration($app)
    {
        $auth = $app->getContainer()->get('auth');

        $app->get('/', '\ToyToyToy\Controller\Main:index');
        $app->get('/signup', '\ToyToyToy\Controller\Main:signup');
        $app->post('/signup', '\ToyToyToy\Controller\Users:create');
        $app->post('/signin', '\ToyToyToy\Controller\Users:signin');
        $app->add($app->getContainer()->get('csrf'));
    }
}
