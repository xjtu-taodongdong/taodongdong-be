<?php
// 应用公共文件

use app\Errors;

/**
 * 根据code获取错误消息
 */
function getErrorMessage(int $code)
{
    return Errors::getErrorMessage($code);
}
