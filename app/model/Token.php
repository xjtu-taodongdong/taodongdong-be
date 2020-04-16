<?php
namespace app\model;

use think\Model;

/**
 * @property string $token
 * @property string $username
 */
class Token extends Model
{
    // 数据表名
    protected $table = 'tokens';
    // 主键
    protected $pk = 'id';
    // 创建时间字段
    protected $createTime = 'create_at';
    // 更新时间字段
    protected $updateTime = 'update_at';
}
