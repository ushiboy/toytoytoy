<?php
namespace ToyToyToy\Tests\Controller;

use ToyToyToy\Controller\Main;
use ToyToyToy\Config;
use ToyToyToy\Dependency;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\App;
use Slim\Http\Environment;

class MainTest extends \PHPUnit_Framework_TestCase
{

    private $controller;

    public function setUp()
    {
        $app = new App(Config::get());
        Dependency::apply($app);
        $this->controller = new Main($app->getContainer());
    }

    public function testIndex()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/'
        ]);
        $response = $this->controller->index(Request::createFromEnvironment($env), new Response());
        $this->assertEquals($response->getStatusCode(), 200);
    }
}
