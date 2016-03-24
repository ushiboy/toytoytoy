<?php
namespace ToyToyToy\Mixin;

use ToyToyToy\Exception\InvalidPasswordException;

trait HasSecurePassword
{

    public static $maxPasswordLengthAllowed = 72;

    public function authenticate($unencryptedPassword)
    {
        return password_verify($unencryptedPassword, $this->getPasswordDigest());
    }

    // register_
    // password
    //
    public function registerPassword($password, $passwordConfirmation)
    {
        $this->validatePassword($password, $passwordConfirmation);
        $passwordDigest = $this->createPasswordDigest($password);
        $this->applyPasswordDigest($passwordDigest);
    }

    protected function createPasswordDigest($unencryptedPassword)
    {
        return password_hash($unencryptedPassword, PASSWORD_BCRYPT, [
            'cost' => 10
        ]);
    }

    protected function validatePassword($password, $passwordConfirmation)
    {
        if (empty($password)) {
            throw new InvalidPasswordException('password required');
        } elseif (strlen($password) > self::$maxPasswordLengthAllowed) {
            throw new InvalidPasswordException('invalid password maximum length');
        } elseif ($password !== $passwordConfirmation) {
            throw new InvalidPasswordException('confirumation is not match');
        }
    }

    abstract protected function getPasswordDigest();

    abstract protected function applyPasswordDigest($passwordDigest);
}
