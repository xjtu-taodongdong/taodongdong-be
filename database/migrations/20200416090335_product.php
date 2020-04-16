<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class Product extends Migrator
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
        $this->table('products')
            ->addColumn('store_id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'comment' => '商铺ID'])
            ->addColumn('product_name', 'string', ['limit' => 255, 'comment' => '商品名称'])
            ->addColumn('product_price', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'comment' => '商品价格(以分为单位的整数)'])
            ->addColumn('product_amount', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'comment' => '剩余数量'])
            ->addColumn('product_description', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'comment' => '商品描述'])
            ->addColumn('product_image', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'comment' => '商品图片'])
            ->addColumn('create_at', 'datetime')
            ->addColumn('update_at', 'datetime')
            ->addIndex(['store_id'])
            ->save();
    }

    public function down()
    {
        $this->table('products')->drop();
    }
}
