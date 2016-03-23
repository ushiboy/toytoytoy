<?php
namespace ToyToyToy\Controller;

class Main extends Base
{

    public function index($request, $response)
    {

        if ($this->auth->getAuthenticated()) {
            return $this->view->render($response, 'index_signed.html', [
            ]);
        }
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);
        return $this->view->render($response, 'index.html', [
            'csrfName' => $name,
            'nameKey' => $nameKey,
            'valueKey' => $valueKey,
            'value' => $value
        ]);
    }

    public function signUp($request, $response)
    {
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);
        return $this->view->render($response, 'signup.html', [
            'csrfName' => $name,
            'nameKey' => $nameKey,
            'valueKey' => $valueKey,
            'value' => $value
        ]);
    }
}
