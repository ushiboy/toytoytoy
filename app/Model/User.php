<?php
namespace ToyToyToy\Model;

use Illuminate\Database\Eloquent;
use ToyToyToy\Mixin\HasSecurePassword;
use Respect\Validation\Validator as v;


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

    public function validate()
    {
        $userValidator = v::attribute('name', v::stringType()->length(null, 100))
            ->attribute('email', v::email());
        $obj = new \stdClass;
        $obj->name = $this->name;
        $obj->email = $this->email;
        $userValidator->assert($obj);
    }

    public function save(array $options = [])
    {
        $this->validate();
        return parent::save($options);
    }

    static public function findByEmail($email) {
        return self::where('email', '=', $email)->get()->first();
    }
}
