<?php
namespace ToyToyToy\Controller;

use Slim\Container;

class Base
{

    protected $csrf;
    protected $view;
    protected $auth;

    public function __construct(Container $container)
    {
        $this->csrf = $container->get('csrf');
        $this->view = $container->get('view');
        $this->auth = $container->get('auth');
    }
}
