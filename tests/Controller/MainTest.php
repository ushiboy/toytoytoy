<?php
namespace ToyToyToy\Tests\Controller;

use ToyToyToy\Controller\Main;
use ToyToyToy\Config;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\App;
use Slim\Http\Environment;
use ToyToyToy\Tests\Helper\HtmlAccessor;
use ToyToyToy\Tests\Helper\Http;

class MainTest extends \PHPUnit_Framework_TestCase
{

    private $controller;
    private $app;
    private $container;

    public function setUp()
    {
        $this->app = $app = new App(Config::get());
        Dependency::apply($app);
        $this->container = $app->getContainer();
        $this->controller = new Main($this->container);
    }

    public function testIndex()
    {
        $csrfName = 'testtesttest';
        $csrfValue = 'valuevaluevalue';
        $chunkSize = $this->container->get('settings')['responseChunkSize'];
        $request = Http::generateRequest(Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/'
        ]), [
            'csrf_name' => $csrfName,
            'csrf_value' => $csrfValue
        ]);

        $response = $this->controller->index($request, new Response());
        $this->assertEquals($response->getStatusCode(), 200);

        $body = Http::getResponseBody($response, $chunkSize);
        $accessor = new HtmlAccessor($body);

        $input1 = $accessor->find('/html/body/div[1]/form/input[1]');
        $this->assertEquals($input1->attr('name'), 'csrf_name');
        $this->assertEquals($input1->val(), $csrfName);

        $input2 = $accessor->find('/html/body/div[1]/form/input[2]');
        $this->assertEquals($input2->attr('name'), 'csrf_value');
        $this->assertEquals($input2->val(), $csrfValue);

    }

    public function testIndex__when_with_error()
    {
        Dependency::$storage = [
            'slimFlash' => [
                'error' => [
                    'Invalid email/password combination'
                ]
            ]
        ];
        $this->app = $app = new App(Config::get());
        Dependency::apply($app);
        $this->container = $app->getContainer();
        $this->controller = new Main($this->container);
        $chunkSize = $this->container->get('settings')['responseChunkSize'];

        $request = Http::generateRequest(Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/'
        ]));

        $response = $this->controller->index($request, new Response());
        $this->assertEquals($response->getStatusCode(), 200);

        $body = Http::getResponseBody($response, $chunkSize);
        $accessor = new HtmlAccessor($body);

        $errorMessage = $accessor->find('/html/body/div[1]/div');
        $this->assertEquals($errorMessage->text(), 'Invalid email/password combination');
    }
}
