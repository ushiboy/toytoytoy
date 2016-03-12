<?php
namespace ToyToyToy;

class Route
{
    static function registration($app)
    {
        $app->get('/', '\ToyToyToy\Controller\Main:index');
    }

}
