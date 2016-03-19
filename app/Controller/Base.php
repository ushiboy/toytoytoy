<?php
namespace ToyToyToy\Controller;

use Slim\Container;

class Base
{

    protected $csrf;
    protected $view;

    public function __construct(Container $container)
    {
        $this->csrf = $container->get('csrf');
        $this->view = $container->get('view');
    }
}
