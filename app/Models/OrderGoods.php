<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OrderGoods extends Model
{
    protected $table = 'order_goods';

    protected $guarded = [];

    public function goodsAttr()
    {
        return $this->hasOne(\App\Models\GoodsAttr::class, 'id', 'attr_id');
    }

    /**
     * 生成订单的商品信息
     */
    public function createOrderGoods($orderId)
    {
        $myBuyLevel = (new \App\Models\UserLevel)->getMyBuyLevel();
        $selCarts = session('carts_prepare');
        foreach ($selCarts as $cartId => $count) {
            $cart = \App\Models\Cart::find($cartId);
            $attr = \App\Models\GoodsAttr::find($cart->attr_id);
            $attr->stock = $attr->stock - $count; // 更新库存
            $attr->save();
            $userPrices = json_decode($attr->user_prices, true);
            $myPrice = $userPrices['level_' . $myBuyLevel];
            $cart->delete(); // 删除购物车记录

            $data = [
                'order_id' => $orderId,
                'attr_id'  => $attr->id,
                'price'    => $myPrice,
                'pv'       => $attr->pv,
                'count'    => $count,
            ];
            $this->create($data);
        }
    }
}