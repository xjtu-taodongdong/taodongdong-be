<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class Order extends Migrator
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
        $this->table('orders')
            ->addColumn('product_id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'comment' => '商品ID'])
            ->addColumn('store_id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'comment' => '商铺ID'])
            ->addColumn('purchaser_user_id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'comment' => '买家用户ID'])
            ->addColumn('merchant_user_id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'comment' => '卖家用户ID'])
            ->addColumn('product_name', 'string', ['limit' => 255, 'comment' => '商品名称'])
            ->addColumn('product_price', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'comment' => '商品成交价格(以分为单位的整数)'])
            ->addColumn('product_amount', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'comment' => '商品成交数量'])
            ->addColumn('product_description', 'text', ['comment' => '商品描述'])
            ->addColumn('product_image', 'string', ['limit' => 255, 'comment' => '商品图片', 'null' => true])
            ->addColumn('order_amount', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'comment' => '订单数量'])
            ->addColumn('order_status', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'comment' => '订单状态'])
            ->addColumn('create_at', 'datetime')
            ->addColumn('update_at', 'datetime')
            ->addIndex(['product_id'])
            ->save();
    }

    public function down()
    {
        $this->table('orders')->drop();
    }
}
