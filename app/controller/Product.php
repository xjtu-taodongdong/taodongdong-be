<?php
namespace app\controller;

use app\BaseController;
use app\Errors;
use app\model\Product as ModelProduct;
use app\model\Store as ModelStore;
use app\model\User as ModelUser;
use app\model\Token as ModelToken;

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
    }
}
