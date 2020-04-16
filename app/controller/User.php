<?php
namespace app\controller;

use app\BaseController;
use app\Errors;
use app\model\User as ModelUser;
use app\model\Token as ModelToken;

class User extends BaseController
{
    /**
     * 登录
     */
    public function login()
    {
        $username = $this->input('username');
        $password = $this->input('password');

        $user = ModelUser::where('username', $username)->find();
        if (!$user) {
            $this->error(Errors::NO_SUCH_USER);
        }

        if (!password_verify($password, $user->password)) {
            $this->error(Errors::PASSWORD_ERROR);
        }

        $bytes = openssl_random_pseudo_bytes(32);
        if ($bytes === false) {
            $this->error(Errors::CANT_CREATE_HASH);
        }

        $token = bin2hex($bytes);

        $tokenModel = new ModelToken();
        $tokenModel->token = $token;
        $tokenModel->username = $username;
        $tokenModel->save();

        return $this->data([
            'token' => $token
        ]);
    }

    /**
     * 登出
     */
    public function logout()
    {
        if ($this->token) {
            ModelToken::where('token', $this->token)->delete();
            return $this->data('登出成功');
        } else {
            return $this->data('您未登录');
        }
    }

    /**
     * 判断用户是否已经注册
     */
    public function isRegistered()
    {
        $username = $this->input('username');
        $existUser = ModelUser::where('username', $username)->find();
        return $this->data($existUser ? true : false);
    }

    /**
     * 注册
     */
    public function register()
    {
        $username = $this->input('username');
        $password = $this->input('password');
        $authority = $this->input('authority');

        if ($this->getCurrentUser() !== null) {
            $this->error(Errors::ALREADY_LOGIN);
        }

        $existUser = ModelUser::where('username', $username)->find();
        if ($existUser) {
            $this->error(Errors::ALREADY_REGISTERED);
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        if (!in_array($authority, [0, 1], true)) {
            $this->error(Errors::AUTHORITY_ERROR);
        }

        $user = new ModelUser();
        $user->username = $username;
        $user->password = $hash;
        $user->authority = $authority;
        $user->save();

        return $this->data('注册成功');
    }

    /**
     * 注册为商家
     */
    public function approveMerchant()
    {
        $user = $this->getCurrentUserOrThrow();

        if ($user->authority === 1) {
            $this->error(Errors::ALREADY_MERCHANT);
        }

        if ($user->authority !== 0) {
            $this->error(Errors::INVALID_AUTHORITY_TO_MERCHANT);
        }

        $user->authority = 1;
        $user->save();

        return $this->data('注册为商家成功');
    }

    /**
     * 获取当前登录用户的信息
     */
    public function getUserInfo()
    {
        $user = $this->getCurrentUser();
        if ($user) {
            return $this->data(
                $user->hidden(['password'])->toArray()
            );
        } else {
            return $this->data(null);
        }
    }
}
