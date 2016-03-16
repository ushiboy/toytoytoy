<?php
namespace ToyToyToy\Tests\Mixin;

use ToyToyToy\Mixin\HasSecurePassword;

class User
{
    use HasSecurePassword;
}

class HasSecurePasswordTest extends \PHPUnit_Framework_TestCase
{

    private $user;

    public function setUp()
    {
        $this->user = new User();
    }

    public function testAuthenticate()
    {
        $this->user->passwordDigest = '$2y$10$f/Sj2DlIxC6ayS7tUJvtN.5.jnJ6fbGokCiYJ0ZdsXXp9QsAxG2aO';
        $this->assertTrue($this->user->authenticate('test'));
    }

    public function testAuthenticate__when_password_is_not_match()
    {
        $this->user->passwordDigest = '$2y$10$f/Sj2DlIxC6ayS7tUJvtN.5.jnJ6fbGokCiYJ0ZdsXXp9QsAxG2aO';
        $this->assertFalse($this->user->authenticate('test*'));
    }
}
