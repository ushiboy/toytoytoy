<?php
namespace ToyToyToy\Mixin;

trait HasSecurePassword
{

    private $password;
    private $passwordConfirmation;
    private $passwordDigest;

    public function authenticate($unencryptedPassword)
    {
        return password_verify($unencryptedPassword, $this->passwordDigest);
    }

    public function setPassword($unencryptedPassword)
    {
        if ($unencryptedPassword === null) {
            $this->passwordDigest = null;
        } else if (!empty($unencryptedPassword)) {
            $this->password = $unencryptedPassword;
            $this->passwordDigest = password_hash($unencryptedPassword, PASSWORD_BCRYPT);
        }
    }

    public function setPasswordConfirmation($unencryptedPassword)
    {
        $this->passwordConfirmation = $unencryptedPassword;
    }
}
