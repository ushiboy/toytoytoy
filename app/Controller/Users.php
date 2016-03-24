<?php
namespace ToyToyToy\Controller;

use ToyToyToy\Model\User;

class Users extends Base
{

    public function create($request, $response)
    {
        $params = $request->getParsedBody();

        $user = new User($params);
        $user->registerPassword($params['password'], $params['password_confirmation']);
        $user->save();

        $this->auth->permit($user->id);
        return $response->withRedirect('/', 301);
    }

    public function signin($request, $response)
    {
        $parsedBody = $request->getParsedBody();
        $user = User::findByEmail($parsedBody['email']);
        if ($user && $user->authenticate($parsedBody['password'])) {
            $this->auth->permit($user->id);
        }
        return $response->withRedirect('/', 301);
    }

    public function signout($request, $response)
    {
        $this->auth->clear();
        return $response->withRedirect('/', 301);
    }
}
