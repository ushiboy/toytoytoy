<?php
namespace ToyToyToy\Controller;

class Main extends Base
{

    public function index($request, $response)
    {
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);
        return $this->view->render($response, 'index.html', [
            'csrfName' => $name,
            'nameKey' => $nameKey,
            'valueKey' => $valueKey,
            'value' => $value,
            'errors' => $this->flash->getMessage('error')
        ]);
    }

}
