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
use Illuminate\Support\Facades\DB;

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

            return true;
        }

        return false;
    }

    public function overview()
    {
        $select = DB::raw('count(*) as count,sum(`total_price`) as money,avg(`total_price`) as avg');

        $today = $this->select($select)->whereBetween('completed_at', [
            date('Y-m-d 00:00:00'),
            date('Y-m-d 23:59:59')
        ])->first();

        $yesterday = $this->select($select)->whereBetween('completed_at', [
            date('Y-m-d 00:00:00', strtotime('-1 day')),
            date('Y-m-d 23:59:59', strtotime('-1 day'))
        ])->first();

        $week = $this->select($select)->whereBetween('completed_at', [
            date('Y-m-d 00:00:00', strtotime('-1 week')),
            date('Y-m-d 23:59:59', strtotime('-1 day'))
        ])->first();

        $month = $this->select($select)->whereBetween('completed_at', [
            date('Y-m-d 00:00:00', strtotime('-1 month')),
            date('Y-m-d 23:59:59', strtotime('-1 day'))
        ])->first();

        $success = $this->select($select)->where('status', 2)->first();
        $fail = $this->select($select)->where('status', 3)->first();
        $all = $this->select($select)->where('status', 2)
            ->orWhere('status', 3)->first();

        return [
            'today' => [
                'avg' => $today->avg ?? 0,
                'count' => $today->count ?? 0,
                'money' => $today->money ?? 0,
            ],
            'yesterday' => [
                'avg' => $yesterday->avg ?? 0,
                'count' => $yesterday->count ?? 0,
                'money' => $yesterday->money ?? 0,
            ],
            'week' => [
                'avg' => $week->avg ?? 0,
                'count' => $week->count ?? 0,
                'money' => $week->money ?? 0,
            ],
            'month' => [
                'avg' => $month->avg ?? 0,
                'count' => $month->count ?? 0,
                'money' => $month->money ?? 0,
            ],
            'success' => [
                'avg' => $success->avg ?? 0,
                'count' => $success->count ?? 0,
                'money' => $success->money ?? 0,
            ],
            'fail' => [
                'avg' => $fail->avg ?? 0,
                'count' => $fail->count ?? 0,
                'money' => $fail->money ?? 0,
            ],
            'all' => [
                'avg' => $all->avg ?? 0,
                'count' => $all->count ?? 0,
                'money' => $all->money ?? 0,
            ]
        ];
    }

    /**
     * @param $orderId
     * @param $postId
     * @return bool
     */
    public function deliver($orderId, $postId)
    {
//        $order = $this->find($orderId);
//        $order->postid = $postId;
//        $order->status = 1;

        return $this->where('id', $orderId)->update(['postid' => $postId, 'status' => 1]);
    }
}