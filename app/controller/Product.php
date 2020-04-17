<?php
namespace app\controller;

use app\BaseController;
use app\Errors;
use app\model\Product as ModelProduct;
use app\model\Store as ModelStore;
use app\model\User as ModelUser;
use app\model\Token as ModelToken;
use think\Paginator;

class Product extends BaseController
{
    /**
     * 创建新商品
     */
    public function createProduct()
    {
        // $user = $this->getCurrentUserOrThrow();
        $store = $this->getCurrentStoreOrThrow();

        $productName = $this->input('product_name');
        $productPrice = $this->input('product_price');
        $productAmount = $this->input('product_amount');
        $productDescription = $this->input('product_description');

        $product = new ModelProduct();
        $product->store_id = $store->id;
        $product->product_name = $productName;
        $product->product_price = $productPrice;
        $product->product_amount = $productAmount;
        $product->product_description = $productDescription;
        $product->product_image = null;
        $product->save();

        return $this->data($product);
    }

    /**
     * 获取商品详情
     */
    public function getProductInfo()
    {
        $productId = $this->input('id');

        $product = ModelProduct::where('id', $productId)->find();
        return $this->data($product);
    }

    /**
     * 搜索商品
     */
    public function searchProducts()
    {
        $keywords = $this->input('keywords');
        $page = $this->input('page');
        $count = $this->input('count') ?: 30;

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        $products = ModelProduct::where('product_name', 'like', '%'.$keywords.'%')->paginate($count);
        return $this->data($products);
    }

    /**
     * 更新商品详情
     */
    public function modifyProduct()
    {
        $store = $this->getCurrentStoreOrThrow();

        $productId = $this->input('id');
        $productName = $this->input('product_name');
        $productPrice = $this->input('product_price');
        $productAmount = $this->input('product_amount');
        $productDescription = $this->input('product_description');

        $product = ModelProduct::where('id', $productId)->find();
        if (!$product) {
            $this->error(Errors::NO_SUCH_PRODUCT);
        }
        if ($product->store_id !== $store->id) {
            $this->error(Errors::NOT_OWNER_MERCHANT);
        }

        if ($productName) {
            $product->product_name = $productName;
        }
        if ($productPrice) {
            $product->product_price = $productPrice;
        }
        if ($productAmount) {
            $product->product_amount = $productAmount;
        }
        if ($productDescription) {
            $product->product_description = $productDescription;
        }
        $product->save();

        return $this->data($product);
    }
}
