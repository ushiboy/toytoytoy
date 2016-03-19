<?php
namespace ToyToyToy\Model;

use \Illuminate\Database\Eloquent;
use ToyToyToy\Mixin\HasSecurePassword;

class User extends Eloquent\Model
{
    use HasSecurePassword;

    protected $fillable = ['name', 'email'];

    protected function applyPasswordDigest($passwordDigest)
    {
        $this->password_digest = $passwordDigest;
    }

    protected function getPasswordDigest()
    {
        return $this->password_digest;
    }
}
