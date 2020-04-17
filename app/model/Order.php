<?php
namespace app\model;

use think\Model;

/**
 * @property int $product_id
 * @property int $store_id
 * @property int $purchaser_user_id
 * @property int $merchant_user_id
 * @property string $product_name
 * @property int $product_price
 * @property int $product_amount
 * @property string $product_description
 * @property string $product_image
 * @property int $order_status
 */
class Order extends Model
{
    // 数据表名
    protected $table = 'orders';
    // 主键
    protected $pk = 'id';
    // 创建时间字段
    protected $createTime = 'create_at';
    // 更新时间字段
    protected $updateTime = 'update_at';

    const STATUS_UNPAID = 1;
    const STATUS_UNSENT = 2;
    const STATUS_UNDELIVERED = 3;
    const STATUS_CONFIRMED = 4;
}
