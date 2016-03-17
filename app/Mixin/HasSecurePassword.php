<?php
namespace ToyToyToy\Mixin;
use ToyToyToy\Exception\InvalidPasswordException;

trait HasSecurePassword
{

    public static $algo = PASSWORD_BCRYPT;
    public static $cost = 10;

    private $password;
    private $passwordConfirmation;
    private $passwordDigest;


    public function authenticate($unencryptedPassword)
    {
        return password_verify($unencryptedPassword, $this->passwordDigest);
    }

    public function __set($name, $value)
    {
        if ($name === 'password') {
            $this->setPassword($value);
        } else if ($name === 'passwordConfirmation') {
            $this->setPasswordConfirmation($value);
        } else if ($name === 'passwordDigest') {
            $this->passwordDigest = $value;
        } else {
            parent::__set($name, $value);
        }
    }

    public function __get($name)
    {
        if ($name === 'password') {
            return $this->password;
        } else if ($name === 'passwordConfirmation') {
            return $this->passwordConfirmation;
        } else if ($name === 'passwordDigest') {
            return $this->passwordDigest;
        }
        return parent::__get($name);
    }

    public function setPassword($unencryptedPassword)
    {
        if ($unencryptedPassword === null) {
            $this->password = null;
            $this->passwordDigest = null;
        } else if (!empty($unencryptedPassword)) {
            $this->password = $unencryptedPassword;
            $this->passwordDigest = password_hash($unencryptedPassword, self::$algo, [
                'cost' => self::$cost
            ]);
        }
    }

    public function setPasswordConfirmation($unencryptedPassword)
    {
        $this->passwordConfirmation = $unencryptedPassword;
    }

    public function validatePassword()
    {
        if ($this->password !== $this->passwordConfirmation) {
            throw new InvalidPasswordException();
        }
    }
}
