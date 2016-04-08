<?php
namespace ToyToyToy\Tests\Controller;

use ToyToyToy\Controller\Main;
use ToyToyToy\Config;
use ToyToyToy\Dependency;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\App;
use Slim\Http\Environment;
use ToyToyToy\Tests\Helper\HtmlAccessor;

class MainTest extends \PHPUnit_Framework_TestCase
{

    private $controller;
    private $app;

    public function setUp()
    {
        $this->app = $app = new App(Config::get());
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
        $body = $this->getResponseBody($response);
        $accessor = new HtmlAccessor($body);
        var_dump($accessor->find('/html/body/div[1]/form/input[1]')->attr('name'));
        var_dump($accessor->find('/html/body/div[1]/form/input[1]')->val());

    }

    public function getResponseBody($response)
    {
        $body = $response->getBody();
        if ($body->isSeekable()) {
            $body->rewind();
        }
        $contentLength  = $response->getHeaderLine('Content-Length');
        if (!$contentLength) {
            $contentLength = $body->getSize();
        }
        $chunkSize = $this->app->getContainer()->get('settings')['responseChunkSize'];
        $result = [];
        if (isset($contentLength)) {
            $totalChunks    = ceil($contentLength / $chunkSize);
            $lastChunkSize  = $contentLength % $chunkSize;
            $currentChunk   = 0;
            while (!$body->eof() && $currentChunk < $totalChunks) {
                if (++$currentChunk == $totalChunks && $lastChunkSize > 0) {
                    $chunkSize = $lastChunkSize;
                }
                $chunk = $body->read($chunkSize);
                array_push($result, $chunk);
            }
        }
        return implode($result);
    }
}
