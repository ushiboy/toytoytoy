<?php
namespace ToyToyToy\Model;

use Illuminate\Database\Eloquent;
use ToyToyToy\Mixin\HasSecurePassword;
use Respect\Validation\Validator as v;

class User extends Eloquent\Model
{
    use HasSecurePassword;

    public $password;

    public $passwordConfirmation;

    protected $fillable = ['name', 'email'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->password = $attributes['password'] ?? null;
        $this->passwordConfirmation = $attributes['password_confirmation'] ?? null;
    }

    public function validate()
    {
        $userValidator = v::attribute('name', v::stringType()->length(null, 100))
            ->attribute('password', v::stringType()->length(8, 72))
            ->attribute('passwordConfirmation', v::equals($this->password))
            ->attribute('email', v::email());
        $obj = new \stdClass;
        $obj->name = $this->name;
        $obj->email = $this->email;
        $obj->password = $this->password;
        $obj->passwordConfirmation = $this->passwordConfirmation;
        $userValidator->assert($obj);
    }

    public function save(array $options = [])
    {
        $this->validate();
        $this->registerPassword($this->password);
        return parent::save($options);
    }

    public static function findByEmail($email)
    {
        return self::where('email', '=', $email)->get()->first();
    }

    public static function findByRememberToken($rememberToken)
    {
        return self::where('remember_token', '=', $rememberToken)->get()->first();
    }

    public static function generateRememberToken($length = 16)
    {
        return str_replace(['+', '/', '='], ['-','_', ''],
            base64_encode(openssl_random_pseudo_bytes($length)));
    }

    public static function encrypt($token)
    {
        return sha1($token);
    }

    protected function applyPasswordDigest($passwordDigest)
    {
        $this->password_digest = $passwordDigest;
    }

    protected function getPasswordDigest()
    {
        return $this->password_digest;
    }
}
