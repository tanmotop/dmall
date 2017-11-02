<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Freight;

class Cart extends Model
{
    protected $fillable = ['user_id', 'goods_id', 'goods_attr_id', 'count'];

    /**
     * 添加商品到购物车
     */
    public function addToCart($uid, $attrs)
    {
        foreach ($attrs as $attrId => $count) {
            if ($row = $this->where('user_id', '=', $uid)->where('goods_attr_id', '=', $attrId)->first()) {
                $row->count = $row->count + $count;
                $row->save();
            } else {
                $goodsAttr = GoodsAttr::find($attrId);
                $this->create([
                    'user_id' => $uid,
                    'goods_id' => $goodsAttr->goods_id,
                    'goods_attr_id' => $attrId,
                    'count'   => $count,
                ]);
            }
        }

        return true;
    }

    /**
     * 更新购物车数量
     */
    public function checkGoodsAndUpdateCart($carts)
    {
        $flag = true;
        foreach($carts as $id => $count) {
            $row = $this->find($id);
            $goods = \App\Models\Goods::join('goods_attrs', 'goods.id', '=', 'goods_attrs.goods_id')
                ->where('goods.status', '=', 1)
                ->where('goods_attrs.id', '=', $row->attr_id)
                ->first(['goods_attrs.stock']);
            if ($count > $goods->stock) {
                $flag ?: $flag = false;
                $row->count = $goods->stock;
                $row->save();
            }
        }

        return $flag;
    }

    /**
     * 获取购物车的商品列表
     */
    public function getGoodsList($uid)
    {
        $fields = [
            'carts.id as cart_id',
            'carts.count',
            'goods.id as goods_id',
            'goods.logo',
            'goods.name',
            'goods.cat_id',
            'goods.sn',
            'goods_attrs.id as attr_id',
            'goods_attrs.pv',
            'goods_attrs.price',
            'goods_attrs.user_prices',
            'goods_attrs.name as attr_name',
            'goods_attrs.stock',
            'goods_attrs.weight',
        ];
        $goodsList = $this->join('goods_attrs', 'carts.goods_attr_id', '=', 'goods_attrs.id')
            ->join('goods', 'goods_attrs.goods_id', '=', 'goods.id')
            ->select($fields)
            ->get();

        if (($myLevel = session('auth_user')->level) == 0) {
            if (!session()->has('user_levels')) {
                (new UserLevel)->saveUserLevelsToSession();
            }
            $myLevel = session('user_levels')['first_level'];
        }
        
        $goodsList->each(function($item, $i) use ($myLevel) {
            $item->user_prices = json_decode($item->user_prices, true);
            $item->buy_price = $item->user_prices['level_'.$myLevel];
        });

        return $goodsList;
    }
}
