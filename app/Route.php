<?php
namespace ToyToyToy;

class Route
{
    public static function registration($app)
    {
        $auth = $app->getContainer()->get('auth');

        $app->get('/', '\ToyToyToy\Controller\Main:index');
        $app->get('/signup', '\ToyToyToy\Controller\Users:new');
        $app->post('/signup', '\ToyToyToy\Controller\Users:create');
        $app->get('/profile', '\ToyToyToy\Controller\Users:show')->add($auth->secure());
        $app->post('/profile', '\ToyToyToy\Controller\Users:update')->add($auth->secure());
        $app->post('/signin', '\ToyToyToy\Controller\Sessions:create');
        $app->get('/signout', '\ToyToyToy\Controller\Sessions:destroy')->add($auth->secure());

        $app->add($app->getContainer()->get('csrf'));
    }
}
