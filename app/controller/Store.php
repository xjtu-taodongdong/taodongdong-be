<?php
namespace app\controller;

use app\BaseController;
use app\Errors;
use app\model\Store as ModelStore;
use app\model\Product as ModelProduct;
use think\Paginator;

class Store extends BaseController
{
    /**
     * 创建商铺
     */
    public function setUpStore()
    {
        $user = $this->getCurrentUserOrThrow();

        $exist = $this->getCurrentStore();
        if ($exist) {
            $this->error(Errors::ALREADY_HAVE_STORE);
        }

        $storeName = $this->input('store_name');
        $store = new ModelStore();
        $store->merchant_user_id = $user->id;
        $store->store_name = $storeName;
        $store->save();

        return $this->data('创建商铺成功');
    }

    /**
     * 获取我的商铺信息
     */
    public function getMyStoreInfo()
    {
        $user = $this->getCurrentUserOrThrow();

        if ($user->authority !== 1) {
            $this->error(Errors::NOT_MERCHANT);
        }

        $store = $this->getCurrentStoreOrThrow();

        return $this->data($store);
    }

    /**
     * 获取特定商铺下的商品（分页）
     */
    public function getAllProducts()
    {
        $storeId = $this->input('store_id');
        $page = $this->input('page');
        $count = $this->input('count') ?: 30;

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $products = ModelProduct::where('store_id', $storeId)->paginate($count);
        return $this->data($products);
    }
}
