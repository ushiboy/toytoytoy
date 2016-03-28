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

    public function updateRememberToken($rememberToken)
    {
        $this->remember_token = self::encrypt($rememberToken);
        $this->save();
    }

    public function clearRememberToken()
    {
        $this->remember_token = null;
        $this->save();
    }

    public static function findById($id)
    {
        return self::fillPasswordWithNoUpdate(self::find($id));
    }

    public static function findByEmail($email)
    {
        return self::fillPasswordWithNoUpdate(self::where('email', '=', $email)->get()->first());
    }

    public static function findByRememberToken($rememberToken)
    {
        $encryptedToken = self::encrypt($rememberToken);
        return self::fillPasswordWithNoUpdate(self::where('remember_token', '=', $encryptedToken)->get()->first());
    }

    public static function fillPasswordWithNoUpdate($user)
    {
        if ($user !== null) {
            $user->password = $user->passwordConfirmation = self::NO_UPDATE_PASSWORD;
        }
        return $user;
    }

    public static function generateRememberToken($length = 16)
    {
        $randomToken = openssl_random_pseudo_bytes($length);
        return str_replace(['+', '/', '='], ['-','_', ''], base64_encode($randomToken));
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
