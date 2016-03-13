<?php
namespace ToyToyToy;

class Route
{
    static function registration($app)
    {
        $app->get('/', '\ToyToyToy\Controller\Main:index');
        $app->post('/signup', '\ToyToyToy\Controller\Main:signUp');
        $app->add($app->getContainer()->get('csrf'));
    }

}
