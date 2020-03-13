<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class User extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->table('users')
            ->addColumn('username', 'string', ['limit' => 255, 'comment' => '用户名'])
            ->addColumn('password', 'string', ['limit' => 255, 'comment' => '密码'])
            ->addColumn('authority', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'comment' => '权限'])
            ->addColumn('create_at', 'datetime')
            ->addColumn('update_at', 'datetime')
            ->addIndex(['username'], ['unique' => true])
            ->addIndex(['authority'])
            ->save();
    }

    public function down()
    {
        $this->table('users')->drop();
    }
}
