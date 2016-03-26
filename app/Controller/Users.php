<?php
namespace ToyToyToy\Controller;

use ToyToyToy\Model\User;

class Users extends Base
{

    public function create($request, $response)
    {
        $params = $request->getParsedBody();

        $user = new User($params);
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
            if (($parsedBody['remember_me'] ?? 'off') === 'on') {
                $rememberToken = User::generateRememberToken();
                $this->cookie->set('remember_token', $rememberToken);
                $user->remember_token = User::encrypt($rememberToken);
                $user->save();
                $response = $response->withHeader('Set-Cookie', $this->cookie->toHeaders());
            }
        }
        return $response->withRedirect('/', 301);
    }

    public function signout($request, $response)
    {
        $this->auth->clear();
        return $response->withRedirect('/', 301);
    }
}
