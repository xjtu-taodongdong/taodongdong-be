<?php
declare (strict_types = 1);

namespace app;

use think\App;
use think\route\dispatch\Controller as ControllerDispatch;

/**
 * Service Dispatcher
 */
class ServiceDispatch extends ControllerDispatch
{
    protected $actionKey = 'action';
    protected $defaultController = 'Index';
    protected $defaultAction = 'index';

    public function init(App $app)
    {
        $this->app = $app;

        // 执行路由后置操作
        $this->doRouteAfter();

        // 从请求输入种获取控制器名和操作名
        [$controller, $action] = $this->getControllerActionFromParam();

        $this->controller = $controller;
        $this->actionName = $action;
        
        // 设置当前请求的控制器、操作
        $this->request
            ->setController($this->controller)
            ->setAction($this->actionName);
    }

    /**
     * 从请求输入种获取控制器名和操作名
     */
    protected function getControllerActionFromParam()
    {
        $actionParam = $this->request->param($this->actionKey) ?? '';
        $list = empty($actionParam) ? [] : explode ('.', $actionParam);
        $n = count($list);

        $controller = $this->defaultController;
        $action = $this->defaultAction;
        if ($n >= 1) {
            $action = array_pop($list);
        }
        if ($n >= 2) {
            $controller = array_pop($list);
        }
    
        return [$controller, $action];
    }
}
