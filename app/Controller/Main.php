<?php
namespace ToyToyToy\Controller;

class Main extends Base
{

    public function index($request, $response)
    {
        return $this->view->render($response, 'index.html', [
            'name' => 'test'
        ]);
    }

}
