<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class Store extends Migrator
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
        $this->table('stores')
            ->addColumn('merchant_user_id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'comment' => '商品所属商家的用户ID'])
            ->addColumn('create_at', 'datetime')
            ->addColumn('update_at', 'datetime')
            ->addIndex(['merchant_user_id'])
            ->save();
    }

    public function down()
    {
        $this->table('stores')->drop();
    }
}
