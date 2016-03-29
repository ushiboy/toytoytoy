<?php
namespace ToyToyToy\Tests\Model;

use ToyToyToy\Model\User;

class UserTest extends \PHPUnit_Framework_TestCase
{

    use \ToyToyToy\Tests\Helper\Database;

    private $existedUser;

    public function setUp()
    {
        $this->setUpDataBase(__DIR__."/../../phinx.yml");

        $this->existedUser = new User([
            'name' => 'test1',
            'email' => 'test1@example.com',
            'password' => 'test1234',
            'password_confirmation' => 'test1234'
        ]);
        $this->existedUser->save();
    }

    /**
     * @expectedException \Respect\Validation\Exceptions\AllOfException
     */
    public function testVaildate__when_invalid_password()
    {
        $user = new User();
        $user->name = 'test';
        $user->email = 'test@example.com';
        $user->password = null;
        $user->validate();
    }

    public function testVaildate__when_no_update_password()
    {
        $user = new User();
        $user->name = 'test';
        $user->email = 'test@example.com';
        $user->password = User::NO_UPDATE_PASSWORD;
        $user->validate();
    }

    public function testRegisterPassword()
    {
        $user = new User();
        $user->name = 'test';
        $user->email = 'test@example.com';
        $user->password = 'test1234';
        $user->passwordConfirmation = 'test1234';
        $user->save();

        $id = $user->id;
        $sameUser = User::findOrFail($id);
        $this->assertEquals($sameUser->name, 'test');
        $this->assertEquals($sameUser->email, 'test@example.com');
        $this->assertNotNull($sameUser->password_digest);
    }

    public function testFindByEmail()
    {
        $sameUser = User::findByEmail('test1@example.com');
        $this->assertEquals($sameUser->name, 'test1');
        $this->assertEquals($sameUser->email, 'test1@example.com');
    }

    public function testFindByEmail__when_not_found()
    {
        $sameUser = User::findByEmail('none@example.com');
        $this->assertNull($sameUser);
    }

    public function testSetNoUpdatePassword()
    {
        $sameUser = User::find($this->existedUser->id);
        $this->assertNull($sameUser->password);
        $this->assertNull($sameUser->passwordConfirmation);

        $sameUser->setNoUpdatePassowrd();
        $this->assertEquals($sameUser->password, User::NO_UPDATE_PASSWORD);
        $this->assertEquals($sameUser->passwordConfirmation, User::NO_UPDATE_PASSWORD);
    }
}
