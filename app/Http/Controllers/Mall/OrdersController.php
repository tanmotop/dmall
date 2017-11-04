<?php

namespace App\Http\Controllers\Mall;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;

class OrdersController extends Controller
{
    private $cartModel;

    public function __construct(Cart $cart)
    {
        $this->cartModel = $cart;
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

    public function submit()
    {

    }
}
