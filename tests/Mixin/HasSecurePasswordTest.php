<?php
namespace ToyToyToy\Tests\Mixin;

use ToyToyToy\Mixin\HasSecurePassword;
use \InvalidArgumentException;

class User
{
    use HasSecurePassword;

    public $passwordDigest;

    public function __construct($passwordDigest = null) {
        $this->passwordDigest = $passwordDigest;
    }

    protected function getPasswordDigest()
    {
        return $this->passwordDigest;
    }

    protected function applyPasswordDigest($passwordDigest)
    {
        $this->passwordDigest = $passwordDigest;
    }
}

class HasSecurePasswordTest extends \PHPUnit_Framework_TestCase
{

    private $user;

    const TEST_PASS_DIGEST = '$2y$10$f/Sj2DlIxC6ayS7tUJvtN.5.jnJ6fbGokCiYJ0ZdsXXp9QsAxG2aO';

    public function setUp()
    {
        $this->user = new User(self::TEST_PASS_DIGEST);
    }

    public function testAuthenticate()
    {
        $this->assertTrue($this->user->authenticate('test'));
    }

    public function testAuthenticate__when_password_is_not_match()
    {
        $this->assertFalse($this->user->authenticate('test*'));
    }

    public function testRegisterPassword()
    {
        $user = new User();
        $password = '012345678901234567890123456789012345678901234567890123456789012345678912';
        $user->registerPassword($password);
        $this->assertNotNull($user->passwordDigest);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRegisterPassword__when_password_is_empty()
    {
        $password = '';
        $this->user->registerPassword($password);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRegisterPassword__when_password_length_over_allowed_max()
    {
        $password = '0123456789012345678901234567890123456789012345678901234567890123456789123';
        $this->user->registerPassword($password);
    }
}
