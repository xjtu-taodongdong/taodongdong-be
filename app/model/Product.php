<?php
namespace app\model;

use think\Model;

/**
 * @property int $store_id
 * @property string $product_name
 * @property int $product_price
 * @property int $product_amount
 * @property string $product_description
 * @property string $product_image
 */
class Product extends Model
{
    // 数据表名
    protected $table = 'products';
    // 主键
    protected $pk = 'id';
    // 创建时间字段
    protected $createTime = 'create_at';
    // 更新时间字段
    protected $updateTime = 'update_at';
}
