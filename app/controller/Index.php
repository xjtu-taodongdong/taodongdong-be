<?php
namespace app\controller;

use app\BaseController;
use think\facade\Db;

class Index extends BaseController
{
    public function index()
    {
        return '<script src="https://unpkg.com/axios@0.18.0/dist/axios.min.js"></script>欢迎来到淘东东';
    }

    public function hello()
    {
        return $this->data('world');
    }

    public function sudo()
    {
        $this->error(1000, '您没有该操作的权限', $this->input);
    }

    public function clearDataBase()
    {
        if (env('app.debug')) {
            Db::query('TRUNCATE `tokens`');
            Db::query('TRUNCATE `users`');
            Db::query('TRUNCATE `products`');
            Db::query('TRUNCATE `stores`');
            return $this->data('Clear OK');
        }
    }
}
