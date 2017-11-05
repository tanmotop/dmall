<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Region;
use App\Models\OrderGoods;

class Orders extends Model
{
    protected $table = 'orders';
    protected $guarded = [];

    public static $status = [
        0 => '未发货',
        1 => '已发货',
        2 => '交易完成',
        3 => '已取消',
    ];

    public function orderGoods()
    {
        return $this->hasMany(OrderGoods::class, 'order_id');
    }

    public function generate($data)
    {
        $data['user_id'] = session('auth_user')->id;
        $data['sn'] = sprintf('SH%s%s%s', date('YmdHi'), $data['user_id'], rand(10, 99));
        if ($order = $this->create($data)) {
            // 更新商品订单信息
            (new \App\Models\OrderGoods)->createOrderGoods($order['id']);
            // 更新用户账户表，并删除购物车记录
            $user = \App\Models\User::find($data['user_id']);
            $user->money = $user->money - $data['total_price'];
            $user->save();
            // 清空session
            session()->forget('carts_prepare');

            return true;
        }

        return false;
    }

    /**
     * 获取订单列表
     */
    public function getMyOrderList($uid, $status)
    {
        $orderList = $this->where('user_id', '=', $uid)
            ->where('status', '=', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(3);
        $orderList->each(function($item, $i) {
            $item->user_province = Region::find($item->user_province)->name;
            $item->user_city = Region::find($item->user_city)->name;
            $item->user_area = Region::find($item->user_area)->name;
            $logos = [];
            foreach($item->orderGoods as $ogItem) {
                $logos[] = $ogItem->goodsAttr->goods->logo;
            }
            $item->logos = $logos;
            // 订单状态：0:未发货; 1:已发货; 2:交易完成; 3已取消
            $item->status_text = self::$status[$item->status];
        });

        return $orderList;
    }

    /**
     * 取消订单
     */
    public function cancelOrder($uid, $orderId)
    {
        $order = $this->find($orderId);

        // 金额回退
        $user = \App\Models\User::find($uid);
        $user->money = $user->money + $order->total_price;
        $user->save();

        // 库存恢复
        $order->orderGoods->each(function($item, $i) {
            $attr = $item->goodsAttr;
            $attr->stock = $attr->stock + $item->count;
            $attr->save();
        });

        // 修改订单状态
        $order->status = 3;
        $order->canceled_at = \Carbon\Carbon::now();
        $order->save();

        return true;
    }

    /**
     * 确认收货
     */
    public function  confirmOrder($orderId)
    {
        $order = $this->find($orderId);
        $order->status = 2;
        $order->completed_at = \Carbon\Carbon::now();
        $order->save();

        return true;
    }
}