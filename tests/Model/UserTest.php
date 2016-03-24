<?php
namespace ToyToyToy\Tests\Model;

use ToyToyToy\Model\User;
use ToyToyToy\Exception\InvalidPasswordException;

use Phinx\Migration\Manager;
use Phinx\Config\Config;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Database\Connection;

class UserTest extends \PHPUnit_Framework_TestCase
{

    private $user;

    public function setUp()
    {
        $config = Config::fromYaml(__DIR__."/../../phinx.yml");
        $manager = new Manager($config, new NullOutput());
        $manager->migrate('testing');
        $conn = $manager->getEnvironment('testing')->getAdapter()->getConnection();
        $capsule = new \Illuminate\Database\Capsule\Manager();
        $dbManager = $capsule->getDatabaseManager();
        $dbManager->extend('default', function($config, $name) use($conn) {
            return new Connection($conn);
        });
        $capsule->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:'
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $this->user = new User();
    }

    public function testRegisterPassword()
    {
        $user = $this->user;
        $user->name = 'test';
        $user->email = 'test@example.com';
        $user->registerPassword('test1234', 'test1234');
        $user->save();

        $id = $user->id;
        $sameUser = User::findOrFail($id);
        $this->assertEquals($sameUser->name, 'test');
        $this->assertEquals($sameUser->email, 'test@example.com');
        $this->assertNotNull($sameUser->password_digest);
    }

    public function testFindByEmail()
    {
        $user1 = new User([
            'name' => 'test1',
            'email' => 'test1@example.com'
        ]);
        $user1->registerPassword('test1', 'test1');
        $user1->save();

        $sameUser = User::findByEmail('test1@example.com');
        $this->assertEquals($sameUser->name, 'test1');
        $this->assertEquals($sameUser->email, 'test1@example.com');
    }

    public function testFindByEmail__when_not_found()
    {
        $user1 = new User([
            'name' => 'test1',
            'email' => 'test1@example.com'
        ]);
        $user1->registerPassword('test1', 'test1');
        $user1->save();

        $sameUser = User::findByEmail('none@example.com');
        $this->assertNull($sameUser);
    }
}
