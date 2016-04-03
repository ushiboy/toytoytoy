<?php
namespace ToyToyToy\Controller;

use ToyToyToy\Model\User;
use ToyToyToy\Exception\RequestErrorException;

class Sessions extends Base
{
    public function create($request, $response)
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
            return $response->withRedirect('/', 301);
        } else {
            $this->flash->addMessage('error', 'Invalid email/password combination');
            return $response->withRedirect('/', 301);
        }
    }

    public function destroy($request, $response)
    {
        $this->auth->getAuthenticated()->clearRememberToken();
        $this->auth->clear();
        $this->cookie->set('remember_token', '');
        $response = $response->withHeader('Set-Cookie', $this->cookie->toHeaders());
        return $response->withRedirect('/', 301);
    }
}
