<?php
declare (strict_types = 1);

namespace app;

use app\model\Store;
use app\model\Token;
use app\model\User;
use think\App;
use think\exception\ValidateException;
use think\Validate;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    protected $token;
    protected $data;
    protected $currentUserCacheLoaded = false;
    protected $currentUserCache;
    protected $currentStoreCacheLoaded = false;
    protected $currentStoreCache;
    protected $debugMap = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
        $this->token = $this->request->param('token');
        $this->data = $this->request->param('data');
    }

    /**
     * 获取输入
     */
    protected function input(string $key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            return null;
        }
    }

    /**
     * 获取当前登录的用户
     * @return \app\model\User
     */
    protected function getCurrentUser()
    {
        // $this->debug('cacheLoaded', $this->currentUserCacheLoaded);
        // $this->debug('cache', $this->currentUserCache);
        if (!$this->currentUserCacheLoaded) {
            $this->currentUserCacheLoaded = true;
            $this->currentUserCache = null;

            $tokenModel = Token::where('token', $this->token)->find();
            // $this->debug('token', $tokenModel);
            if ($tokenModel) {
                $username = $tokenModel->username;
                $user = User::where('username', $username)->find();
                // $this->debug('user', $user);
                if ($user) {
                    $this->currentUserCache = $user;
                }
            }
        }
        return $this->currentUserCache;
    }

    /**
     * 获取当前登录的用户或抛出错误
     * @return \app\model\User
     */
    protected function getCurrentUserOrThrow()
    {
        $user = $this->getCurrentUser();
        if ($user) {
            return $user;
        } else {
            $this->error(Errors::NOT_LOGIN);
        }
    }

    /**
     * 获取当前登录用户的店铺
     * @return \app\model\Store
     */
    protected function getCurrentStore()
    {
        if (!$this->currentStoreCacheLoaded) {
            $this->currentStoreCacheLoaded = true;
            $this->currentStoreCache = null;

            $user = $this->getCurrentUser();
            if ($user) {
                $store = Store::where('merchant_user_id', $user->id)->find();
                if ($store) {
                    $this->currentStoreCache = $store;
                }
            }
        }
        return $this->currentStoreCache;
    }

    /**
     * 获取当前登录用户的店铺或抛出错误
     * @return \app\model\Store
     */
    protected function getCurrentStoreOrThrow()
    {
        $store = $this->getCurrentStore();
        if ($store) {
            return $store;
        } else {
            $this->error(Errors::NO_STORE);
        }
    }

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }

    protected function debug($key, $value)
    {
        $this->debugMap[$key] = $value;
    }

    public function data($data)
    {
        if (env('app.debug') && count($this->debugMap) > 0) {
            return json([
                'code' => 0,
                'data' => $data,
                'debug' => $this->debugMap,
            ]);
        } else {
            return json([
                'code' => 0,
                'data' => $data,
            ]);
        }
    }

    public function error($code = -1, $message = null, $data = null)
    {
        $finalMessage = $message ? $message : getErrorMessage($code);
        throw new ApiException($code, $finalMessage, $data);
    }
}
