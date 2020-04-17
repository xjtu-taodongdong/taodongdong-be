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

    const ALREADY_MERCHANT = 11000;
    const INVALID_AUTHORITY_TO_MERCHANT = 11001;

    const ALREADY_HAVE_STORE = 12000;
    const NOT_MERCHANT = 12001;
    const NO_STORE = 12002;

    const NO_SUCH_PRODUCT = 13000;
    const NO_SUCH_STORE = 13001;
    const NO_SUCH_ORDER = 13002;
    const NOT_OWNER_MERCHANT = 13003;
    const NOT_OWNER_PURCHASER = 13004;
    const INVALID_STATUS = 13005;

    const CODE_ERROR_MAP = [
        Errors::NOT_LOGIN => '您未登录',
        Errors::NO_SUCH_USER => '用户不存在，无法登录',
        Errors::PASSWORD_ERROR => '密码错误',
        Errors::CANT_CREATE_HASH => '登录异常请重试',
        Errors::ALREADY_LOGIN => '无法注册，因为您已登录',
        Errors::ALREADY_REGISTERED => '无法注册，因为该用户名已经被注册',
        Errors::AUTHORITY_ERROR => '无法注册因为权限等级不正确',
        Errors::ALREADY_MERCHANT => '您已经是商家了，无法注册为商家',
        Errors::INVALID_AUTHORITY_TO_MERCHANT => '您的权限等级无法注册为商家',
        Errors::ALREADY_HAVE_STORE => '您已经拥有了一个店铺',
        Errors::NOT_MERCHANT => '您不是商家，没有商铺',
        Errors::NO_STORE => '您还没有商铺',
        Errors::NO_SUCH_PRODUCT => '没有找到该商品',
        Errors::NO_SUCH_STORE => '没有找到这家店铺',
        Errors::NO_SUCH_ORDER => '没有找到这条订单',
        Errors::NOT_OWNER_MERCHANT => '您不是这件商品的店家',
        Errors::NOT_OWNER_PURCHASER => '您不是这件商品的买家',
        Errors::INVALID_STATUS => '当前订单状态不能执行该操作',
    ];

    /**
     * 获取错误消息
     */
    public static function getErrorMessage(int $code)
    {
        return isset(static::CODE_ERROR_MAP[$code]) ? static::CODE_ERROR_MAP[$code] : '服务器异常';
    }
}
