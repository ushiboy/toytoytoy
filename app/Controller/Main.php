<?php
namespace ToyToyToy\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class Main extends Base
{

    public function index(Request $request, Response $response)
    {
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);
        return $this->view->render($response, 'session/signin.html', [
            'csrfName' => $name,
            'nameKey' => $nameKey,
            'valueKey' => $valueKey,
            'value' => $value,
            'errors' => $this->flash->getMessage('error')
        ]);
    }

}
