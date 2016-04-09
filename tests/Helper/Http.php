<?php
namespace ToyToyToy\Tests\Helper;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;

class Http
{

    static public function generateRequest(Environment $env, array $attributes = [])
    {
        $request = Request::createFromEnvironment($env);
        foreach ($attributes as $key => $value) {
            $request = $request->withAttribute($key, $value);
        }
        return $request;
    }


    static public function getResponseBody(Response $response, int $chunkSize = 4096)
    {
        $body = $response->getBody();
        if ($body->isSeekable()) {
            $body->rewind();
        }
        $contentLength  = $response->getHeaderLine('Content-Length');
        if (!$contentLength) {
            $contentLength = $body->getSize();
        }
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
