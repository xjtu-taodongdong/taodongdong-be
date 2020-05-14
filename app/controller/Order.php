<?php
namespace app\controller;

use app\BaseController;
use app\Errors;
use app\model\Order as ModelOrder;
use app\model\Product as ModelProduct;
use app\model\Store as ModelStore;
use app\model\User as ModelUser;
use app\model\Token as ModelToken;
use think\facade\Db;
use think\Paginator;

class Order extends BaseController
{
    /**
     * 创建订单
     */
    public function createOrder()
    {
        $user = $this->getCurrentUserOrThrow();

        $productId = $this->input('product_id');

        $product = ModelProduct::where('id', $productId)->find();
        if (!$product) {
            $this->error(Errors::NO_SUCH_PRODUCT);
        }

        $storeId = $product->store_id;
        $store = ModelStore::where('id', $storeId)->find();
        if (!$store) {
            $this->error(Errors::NO_SUCH_STORE);
        }

        $merchantUserId = $store->merchant_user_id;
        $purchaserUserId = $user->id;

        $order = new ModelOrder();
        $order->product_id = $productId;
        $order->store_id = $storeId;
        $order->purchaser_user_id = $purchaserUserId;
        $order->merchant_user_id = $merchantUserId;
        $order->product_name = $product->product_name;
        $order->product_price = $product->product_price;
        $order->product_amount = $product->product_amount;
        $order->product_description = $product->product_description;
        $order->product_image = $product->product_image;
        $order->order_status = ModelOrder::STATUS_UNPAID;
        $order->save();

        return $this->data($order);
    }

    /**
     * 支付订单
     */
    public function payOrder()
    {
        $user = $this->getCurrentUserOrThrow();

        $orderId = $this->input('order_id');

        /** @var ModelOrder */
        $order = ModelOrder::where('id', $orderId)->find();
        if (!$order) {
            $this->error(Errors::NO_SUCH_ORDER);
        }

        if ($order->order_status !== ModelOrder::STATUS_UNPAID) {
            $this->error(Errors::INVALID_STATUS);
        }

        // 这里不检查order是否是自己的 即允许帮别人付款
        $priceTotal = $order->product_price * $order->product_amount;
        if ($user->balance >= $priceTotal) {
            Db::transaction(function() use ($user, $priceTotal, $order) {
                $user->balance -= $priceTotal;
                $order->order_status = ModelOrder::STATUS_UNSENT;
                $user->save();
                $order->save();
            });
        } else {
            $this->error(Errors::NO_ENOUGH_MONEY);
        }

        return $this->data('支付成功');
    }

    /**
     * 发货
     */
    public function sendOrder()
    {
        $user = $this->getCurrentUserOrThrow();
        $store = $this->getCurrentStoreOrThrow();

        $orderId = $this->input('order_id');

        /** @var ModelOrder */
        $order = ModelOrder::where('id', $orderId)->find();
        if (!$order) {
            $this->error(Errors::NO_SUCH_ORDER);
        }

        if ($order->store_id !== $store->id) {
            $this->error(Errors::NOT_OWNER_MERCHANT);
        }
        if ($order->merchant_user_id !== $user->id) {
            $this->error(Errors::NOT_OWNER_MERCHANT);
        }

        if ($order->order_status !== ModelOrder::STATUS_UNSENT) {
            $this->error(Errors::INVALID_STATUS);
        }

        $order->order_status = ModelOrder::STATUS_UNDELIVERED;
        $order->save();

        return $this->data('发货成功');
    }

    /**
     * 确认订单
     */
    public function confirmOrder()
    {
        $user = $this->getCurrentUserOrThrow();

        $orderId = $this->input('order_id');

        /** @var ModelOrder */
        $order = ModelOrder::where('id', $orderId)->find();
        if (!$order) {
            $this->error(Errors::NO_SUCH_ORDER);
        }

        if ($order->purchaser_user_id !== $user->id) {
            $this->error(Errors::NOT_OWNER_PURCHASER);
        }

        $merchant = ModelUser::where('id', $order->merchant_user_id)->find();
        if (!$merchant) {
            $this->error(Errors::NO_SUCH_STORE);
        }

        if ($order->order_status !== ModelOrder::STATUS_UNDELIVERED) {
            $this->error(Errors::INVALID_STATUS);
        }

        $priceTotal = $order->product_price * $order->product_amount;
        Db::transaction(function() use ($merchant, $priceTotal, $order) {
            $merchant->balance += $priceTotal;
            $order->order_status = ModelOrder::STATUS_CONFIRMED;
            $merchant->save();
            $order->save();
        });

        return $this->data('确认成功');
    }

    /**
     * 获取商家的订单列表
     */
    public function getMerchantOrders()
    {
        $user = $this->getCurrentUserOrThrow();
        $this->getCurrentStoreOrThrow();

        $page = $this->input('page');
        $count = $this->input('count') ?: 30;

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        $orders = ModelOrder::where('merchant_user_id', $user->id)->paginate($count);
        return $this->data($orders);
    }

    /**
     * 获取客户的订单列表
     */
    public function getPurchaserOrders()
    {
        $user = $this->getCurrentUserOrThrow();

        $page = $this->input('page');
        $count = $this->input('count') ?: 30;

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        $orders = ModelOrder::where('purchaser_user_id', $user->id)->paginate($count);
        return $this->data($orders);
    }
}
