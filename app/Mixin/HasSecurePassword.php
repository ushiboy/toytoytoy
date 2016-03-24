<?php
namespace ToyToyToy\Mixin;

use \InvalidArgumentException;

trait HasSecurePassword
{

    public static $maxPasswordLengthAllowed = 72;

    public function authenticate($unencryptedPassword)
    {
        return password_verify($unencryptedPassword, $this->getPasswordDigest());
    }

    public function registerPassword($unencryptedPassword, $algo = PASSWORD_BCRYPT, $cost = 10)
    {
        $this->validatePassword($unencryptedPassword);
        $passwordDigest = $this->createPasswordDigest($unencryptedPassword, $algo, $cost);
        $this->applyPasswordDigest($passwordDigest);
    }

    protected function createPasswordDigest($unencryptedPassword, $algo, $cost)
    {
        return password_hash($unencryptedPassword, $algo, [
            'cost' => $cost
        ]);
    }

    protected function validatePassword($password)
    {
        if (empty($password)) {
            throw new InvalidArgumentException('password required');
        } elseif (strlen($password) > self::$maxPasswordLengthAllowed) {
            throw new InvalidArgumentException('invalid password maximum length');
        }
    }

    abstract protected function getPasswordDigest();

    abstract protected function applyPasswordDigest($passwordDigest);
}
