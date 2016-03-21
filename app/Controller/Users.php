<?php
namespace ToyToyToy\Controller;

use ToyToyToy\Model\User;

class Users extends Base
{

    public function create($request, $response)
    {
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        $params = $request->getParsedBody();

        $user = new User($params);
        $user->setPassword($params['password'], $params['password_confirmation']);
        $user->save();

        return $this->view->render($response, 'index.html', [
            'csrfName' => $name,
            'nameKey' => $nameKey,
            'valueKey' => $valueKey,
            'value' => $value
        ]);
    }
}
