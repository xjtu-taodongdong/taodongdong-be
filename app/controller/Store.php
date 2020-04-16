<?php
namespace app\controller;

use app\BaseController;
use app\Errors;
use app\model\Store as ModelStore;
use app\model\User as ModelUser;
use app\model\Token as ModelToken;

class Store extends BaseController
{
    /**
     * 创建商铺
     */
    public function setUpStore()
    {
        $user = $this->getCurrentUserOrThrow();

        $exist = ModelStore::where('merchant_user_id', $user->id)->find();
        if ($exist) {
            $this->error(Errors::ALREADY_HAVE_STORE);
        }

        $storeName = $this->input('storeName');
        $store = new ModelStore();
        $store->merchant_user_id = $user->id;
        $store->store_name = $storeName;
        $store->save();

        return $this->data('创建商铺成功');
    }
}
