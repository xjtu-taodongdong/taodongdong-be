<?php
namespace app\model;

use think\Model;

/**
 * @property string $username
 * @property string $password
 * @property int $authority
 */
class User extends Model
{
    // 数据表名
    protected $table = 'users';
    // 主键
    protected $pk = 'id';
    // 创建时间字段
    protected $createTime = 'create_at';
    // 更新时间字段
    protected $updateTime = 'update_at';
}
