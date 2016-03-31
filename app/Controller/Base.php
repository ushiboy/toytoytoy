<?php
namespace ToyToyToy\Controller;

use Slim\Container;

class Base
{

    protected $csrf;
    protected $view;
    protected $auth;
    protected $cookie;
    protected $flash;
    protected $logger;

    public function __construct(Container $container)
    {
        $this->csrf = $container->get('csrf');
        $this->view = $container->get('view');
        $this->auth = $container->get('auth');
        $this->cookie = $container->get('cookie');
        $this->flash = $container->get('flash');
        $this->logger = $container->get('logger');
    }
}
