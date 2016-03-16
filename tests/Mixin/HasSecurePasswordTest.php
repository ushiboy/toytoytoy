<?php
namespace ToyToyToy\Tests\Mixin;

use ToyToyToy\Mixin\HasSecurePassword;

class User
{
    use HasSecurePassword;
}

class HasSecurePasswordTest extends \PHPUnit_Framework_TestCase
{

    public function testAuthenticate()
    {
        $user = new User();
        $user->setPassword('test');
        $this->assertEquals($user->authenticate('test'), true);
    }
}
