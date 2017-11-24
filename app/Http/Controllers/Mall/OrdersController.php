<?php

namespace App\Http\Controllers\Mall;

use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Http\Requests\OrderSubmitRequest;
use App\Models\Orders;
use App\Models\CustomerAddress;

class OrdersController extends Controller
{
    private $cartModel;
    private $orderModel;
    private $addressModel;

    public function __construct(Cart $cart, Orders $order, CustomerAddress $address)
    {
        $this->cartModel  = $cart;
        $this->orderModel = $order;
        $this->addressModel = $address;
    }

    /**
     * 订单列表
     */
    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $uid = session('auth_user')->id;
        $status = $request->input('status', 0);
        $orderList = $this->orderModel->getMyOrderList($uid, $status, $keyword);

        // 获取更多/分页数据 => 直接返回json
        if ($request->has('dataType') && $request->dataType == 'json') {
            return $orderList;
        }

        return view('mall/orders', [
            'title' => '我的订单',
            'orderList' => $orderList,
            'status' => $status,
            'keyword' => $keyword,
        ]);
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
        $totalInfo = $this->cartModel->getSelectGoodsTotalInfo();
        // 判断订单价格是否变动
        if ($totalInfo['price'] != $request->total_price - $request->freight) {
            return ['code' => 10002, 'msg' => '订单价格异常，请重新下单'];
        }
        // 判断用户账户金额
        $user = User::find(session('auth_user')->id);
        if ($user->money < $request->total_price) {
            return ['code' => 10003, 'msg' => '账户余额不足'];
        }
        // 生成下单
        $data = $request->all();
        if ($data['save_address']) {
            $this->addressModel->saveAddress($data);
        }
        unset($data['save_address']);
        if ($this->orderModel->generate($data)) {
            return ['code' => 10000, 'msg'  => '下单成功'];
        } else {
            return ['code' => 10001, 'msg'  => '下单失败'];
        }
    }

    /**
     * 取消订单
     */
    public function cancel(Request $request)
    {
        $id = $request->id;
        $uid = session('auth_user')->id;

        return ['code' => $this->orderModel->cancelOrder($uid, $id)];
    }

    /**
     * 确认收货
     */
    public function confirm(Request $request)
    {
        $id = $request->id;

        if ($this->orderModel->confirmOrder($id)) {
            return ['code' => 10000];
        } else {
            return ['code' => 10001];
        }
    }

    /**
     * 订单详情
     */
    public function detail(Request $request)
    {
        $tab = $request->input('tab', '');
        $id  = $request->id;

        $order = $this->orderModel->find($id);
        $order->status_text = Orders::$status[$order->status];
        $order->user_province = Region::find($order->user_province)->name;
        $order->user_city = Region::find($order->user_city)->name;
        $order->user_area = Region::find($order->user_area)->name;

        return view('mall/orders_detail', [
            'title' => '订单详情',
            'order' => $order,
            'tab'   => $tab,
        ]);
    }
}
