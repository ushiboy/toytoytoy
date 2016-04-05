<?php
namespace ToyToyToy\Controller;

use ToyToyToy\Model\User;
use ToyToyToy\Exception\RequestErrorException;

class Users extends Base
{

    public function new($request, $response)
    {
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);
        return $this->view->render($response, 'signup.html', [
            'csrfName' => $name,
            'nameKey' => $nameKey,
            'valueKey' => $valueKey,
            'value' => $value,
            'errors' => $this->flash->getMessage('error')
        ]);
    }

    public function create($request, $response)
    {
        $params = $request->getParsedBody();
        $user = new User($params);
        try {
            $user->save();
            $this->logger->addInfo('created new user');

            $message = \Swift_Message::newInstance()
                ->setCharset('iso-2022-jp')
                ->setEncoder(\Swift_Encoding::get7BitEncoding())
                ->setSubject('test')
                ->setFrom($user->email)
                ->setTo($user->email)
                ->setBody('testtesttest');

            $result = $this->mail->send($message);
            $this->logger->addInfo('sendmail result ' + $result);

            $this->auth->permit($user->id);
            return $response->withRedirect('/profile', 301);
        } catch (\Exception $e) {
            $this->flash->addMessage('error', $e->getMessage());
            throw new RequestErrorException($response->withRedirect('/signup', 301), $e);
        }
    }

    public function show($request, $response)
    {
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);
        $profile = $this->auth->getAuthenticated();
        $profile->setNoUpdatePassowrd();
        return $this->view->render($response, 'index_signed.html', [
            'csrfName' => $name,
            'nameKey' => $nameKey,
            'valueKey' => $valueKey,
            'value' => $value,
            'errors' => $this->flash->getMessage('error'),
            'profile' => $profile
        ]);
    }

    public function update($request, $response)
    {
        $params = $request->getParsedBody();
        $user = $this->auth->getAuthenticated();
        try {
            $user->update($params);
            $this->logger->addInfo('update user');
        } catch (\Exception $e) {
            $this->flash->addMessage('error', $e->getMessage());
        }
        return $response->withRedirect('/profile', 301);
    }
}
