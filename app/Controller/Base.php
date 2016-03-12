<?php
namespace ToyToyToy\Controller;

use Slim\Container;

class Base
{

    protected $view;

    public  function __construct(Container $container)
    {
        $this->view = $container->get('view');
    }

}
