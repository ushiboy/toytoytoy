<?php

use Phinx\Migration\AbstractMigration;

class CreateUsers extends AbstractMigration
{
    public function up()
    {

        $users = $this->table('users');
        $users->addColumn('name', 'string')
            ->addColumn('email', 'string')
            ->addColumn('admin', 'boolean', ['default' => false])
            ->addColumn('password_digest', 'string')
            ->addColumn('remember_token', 'string')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex(['email'], ['unique' => true])
            ->addIndex(['remember_token'])
            ->save();
    }
}
