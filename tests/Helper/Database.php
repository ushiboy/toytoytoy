<?php
namespace ToyToyToy\Tests\Helper;

use Phinx\Migration\Manager;
use Phinx\Config\Config;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Database\Connection;

trait Database
{

    public function setUpDataBase($phinxYamlPath)
    {
        $config = Config::fromYaml($phinxYamlPath);
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
    }
}
