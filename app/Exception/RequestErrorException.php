<?php
namespace ToyToyToy\Exception;

class RequestErrorException extends \Exception
{

    protected $response;

    public function __construct ($response, \Exception $previous, string $message = "", int $code = 0)
    {
        parent::__construct($message, $code, $previous);
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
