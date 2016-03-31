<?php
namespace ToyToyToy\Controller;

use ToyToyToy\Model\User;

class Users extends Base
{

    public function create($request, $response)
    {
        $params = $request->getParsedBody();
        $user = new User($params);
        try {
            $user->save();
            $this->auth->permit($user->id);
            return $response->withRedirect('/', 301);
        } catch (\Exception $e) {
            $this->flash->addMessage('error', $e->getMessage());
            return $response->withRedirect('/signup', 301);
        }
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
                $user->updateRememberToken($rememberToken);
                $response = $response->withHeader('Set-Cookie', $this->cookie->toHeaders());
            }
        } else {
            $this->flash->addMessage('error', 'Invalid email/password combination');
        }
        return $response->withRedirect('/', 301);
    }

    public function signout($request, $response)
    {
        $this->auth->getAuthenticated()->clearRememberToken();
        $this->auth->clear();
        $this->cookie->set('remember_token', '');
        $response = $response->withHeader('Set-Cookie', $this->cookie->toHeaders());
        return $response->withRedirect('/', 301);
    }
}
