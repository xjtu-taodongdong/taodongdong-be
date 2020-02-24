<?php
namespace app\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return '<script src="https://unpkg.com/axios@0.18.0/dist/axios.min.js"></script>欢迎来到淘东东';
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }
}
