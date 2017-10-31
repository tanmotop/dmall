<?php

namespace App\Http\Controllers\Mall;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrdersController extends Controller
{
    public function index()
    {
        return view('mall/orders', ['title' => '我的订单']);
    }

    /**
     * 提交订单
     */
    public function prepare(Request $request)
    {
        $selCarts = session('carts_prepare');
        if (empty($selCarts)) {
            return redirect()->route('carts');
        }
        dd($selCarts);
    }
}
