<?php

namespace App\Http\Controllers\Mall;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Http\Requests\OrderSubmitRequest;
use App\Models\Orders;

class OrdersController extends Controller
{
    private $cartModel;
    private $orderModel;

    public function __construct(Cart $cart, Orders $order)
    {
        $this->cartModel  = $cart;
        $this->orderModel = $order;
    }

    public function index()
    {
        return view('mall/orders', ['title' => '我的订单']);
    }

    /**
     * 提交订单
     */
    public function prepare(Request $request)
    {
        if (empty(session('carts_prepare'))) {
            return redirect()->route('carts');
        }
        $totalInfo = $this->cartModel->getSelectGoodsTotalInfo();

        return view('mall/orders_prepare', [
            'title'     => '订单确认',
            'totalInfo' => $totalInfo,
        ]);
    }

    /**
     * 提交订单
     */
    public function submit(OrderSubmitRequest $request)
    {
        $data = $request->all();
        $totalInfo = $this->cartModel->getSelectGoodsTotalInfo();
        // 判断订单价格是否变动
        if ($totalInfo['price'] != $request->total_price - $request->freight) {
            return ['code' => 10002, 'msg' => '订单价格异常，请重新下单'];
        }
        // 判断用户账户金额
        $user = \App\Models\User::find(session('auth_user')->id);
        if ($user->money < $request->total_price) {
            return ['code' => 10003, 'msg' => '账户余额不足'];
        }
        // 生成下单
        if ($this->orderModel->generate($data)) {
            
        }

        return ['code' => 10000, 'msg'  => '下单成功'];
    }
}
