<?php
namespace ToyToyToy\Tests\Mixin;

use ToyToyToy\Mixin\HasSecurePassword;
use ToyToyToy\Exception\InvalidPasswordException;

class User
{
    use HasSecurePassword;
}

class HasSecurePasswordTest extends \PHPUnit_Framework_TestCase
{

    private $user;

    const TEST_PASS_DIGEST = '$2y$10$f/Sj2DlIxC6ayS7tUJvtN.5.jnJ6fbGokCiYJ0ZdsXXp9QsAxG2aO';

    public function setUp()
    {
        $this->user = new User();
    }

    public function testAuthenticate()
    {
        $user = $this->user;
        $user->passwordDigest = self::TEST_PASS_DIGEST;
        $this->assertTrue($user->authenticate('test'));
    }

    public function testAuthenticate__when_password_is_not_match()
    {
        $user = $this->user;
        $user->passwordDigest = self::TEST_PASS_DIGEST;
        $this->assertFalse($user->authenticate('test*'));
    }

    /**
     * @expectedException ToyToyToy\Exception\InvalidPasswordException
     */
    public function testValidatePassword()
    {
        $this->user->password = 'test';
        $this->user->passwordConfirmation = 'test+';
        $this->user->validatePassword();
    }
}
