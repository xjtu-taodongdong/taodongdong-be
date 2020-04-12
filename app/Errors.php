<?php
declare (strict_types = 1);

namespace app;

class Errors
{
    const NOT_LOGIN = 1000;

    const NO_SUCH_USER = 10000;
    const PASSWORD_ERROR = 10001;
    const CANT_CREATE_HASH = 10002;
    const ALREADY_LOGIN = 10003;
    const ALREADY_REGISTERED = 10004;
    const AUTHORITY_ERROR = 10005;

    const CODE_ERROR_MAP = [
        Errors::NOT_LOGIN => '您未登录',
        Errors::NO_SUCH_USER => '用户不存在',
        Errors::PASSWORD_ERROR => '密码错误',
        Errors::CANT_CREATE_HASH => '登录异常请重试',
        Errors::ALREADY_LOGIN => '您已登录',
        Errors::ALREADY_REGISTERED => '该用户名已经被注册',
        Errors::AUTHORITY_ERROR => '权限等级不正确',
    ];

    /**
     * 获取错误消息
     */
    public static function getErrorMessage(int $code)
    {
        return isset(static::CODE_ERROR_MAP[$code]) ? static::CODE_ERROR_MAP[$code] : '服务器异常';
    }
}
