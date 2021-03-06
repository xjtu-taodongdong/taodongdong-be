<?php
namespace app\controller;

use app\BaseController;
use app\Errors;
use app\model\Product as ModelProduct;
use app\model\Store as ModelStore;
use app\model\User as ModelUser;
use app\model\Token as ModelToken;
use think\facade\Filesystem;
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
        if ($product) {
            $storeId = $product->store_id;
            $store = ModelStore::where('id', $storeId)->find();
            if ($store) {
                $merchantUserId = $store->merchant_user_id;
            } else {
                $merchantUserId = 0;
            }
            $product->merchant_user_id = $merchantUserId;
        }
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
        $products = ModelProduct::where('product_name', 'like', '%'.$keywords.'%')
            ->where('product_amount', '>', 0)
            ->paginate($count);
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

    /**
     * 上传商品图片
     */
    public function uploadImage()
    {
        // 检查登录
        $store = $this->getCurrentStoreOrThrow();

        $productId = $this->input('id');

        // 检查商品存在并且是自己的商品
        $product = ModelProduct::where('id', $productId)->find();
        if (!$product) {
            $this->error(Errors::NO_SUCH_PRODUCT);
        }
        if ($product->store_id !== $store->id) {
            $this->error(Errors::NOT_OWNER_MERCHANT);
        }

        // 检查图片
        $file = $this->file();
        if (!$file) {
            // 没有上传文件
            return $this->error(Errors::NO_INPUT_FILE);
        }
        $ext = $file->extension();
        if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
            // 不是图片
            return $this->error(Errors::NOT_IMAGE);
        }

        $name = Filesystem::putFile('image', $file);
        $url = 'https://taodongdong.ddltech.top/uploads/' . $name;

        // 保存到数据库
        $product->product_image = $url;
        $product->save();

        return $this->data([
            'url' => $url,
        ]);
    }

    /**
     * 下架商品
     */
    public function removeProduct()
    {
        // 检查登录
        $store = $this->getCurrentStoreOrThrow();

        $productId = $this->input('id');

        // 检查商品存在并且是自己的商品
        $product = ModelProduct::where('id', $productId)->find();
        if (!$product) {
            $this->error(Errors::NO_SUCH_PRODUCT);
        }
        if ($product->store_id !== $store->id) {
            $this->error(Errors::NOT_OWNER_MERCHANT);
        }

        $product->delete();
        return $this->data('下架成功');
    }
}
