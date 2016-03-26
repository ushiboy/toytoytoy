<?php
namespace ToyToyToy\Model;

use Illuminate\Database\Eloquent;
use ToyToyToy\Mixin\HasSecurePassword;
use Respect\Validation\Validator as v;

class User extends Eloquent\Model
{
    use HasSecurePassword;

    const NO_UPDATE_PASSWORD = '__NO_UPDATE_PASSWORD__';

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
        $obj = new \stdClass;
        $obj->name = $this->name;
        $obj->email = $this->email;
        $userValidator = v::attribute('name', v::stringType()->length(null, 100))
            ->attribute('email', v::email());
        if ($this->password !== self::NO_UPDATE_PASSWORD) {
            $userValidator->attribute('password', v::stringType()->length(8, 72))
                ->attribute('passwordConfirmation', v::equals($this->password));
            $obj->password = $this->password;
            $obj->passwordConfirmation = $this->passwordConfirmation;
        }
        $userValidator->assert($obj);
    }

    public function save(array $options = [])
    {
        $this->validate();
        if ($this->password !== self::NO_UPDATE_PASSWORD) {
            $this->registerPassword($this->password);
        }
        return parent::save($options);
    }

    public static function findByEmail($email)
    {
        return self::fillNoUpdatePassword(self::where('email', '=', $email)->get()->first());
    }

    public static function findByRememberToken($rememberToken)
    {
        return self::fillNoUpdatePassword(
            self::where('remember_token', '=', self::encrypt($rememberToken))->get()->first());
    }

    private static function fillNoUpdatePassword($user)
    {
        if ($user !== null) {
            $user->password = $user->passwordConfirmation = self::NO_UPDATE_PASSWORD;
        }
        return $user;
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
