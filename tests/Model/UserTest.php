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

    public function testSetPassword()
    {
        $user = $this->user;
        $user->name = 'test';
        $user->email = 'test@example.com';
        $user->setPassword('test1234', 'test1234');
        $user->save();

        $id = $user->id;
        $sameUser = User::findOrFail($id);
        $this->assertEquals($sameUser->name, 'test');
        $this->assertEquals($sameUser->email, 'test@example.com');
        $this->assertNotNull($sameUser->password_digest);
    }
}
