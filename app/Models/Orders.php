<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/2
 * Time: 15:33
 * Function:
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';
    protected $guarded = [];

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
        }

        return false;
    }
}