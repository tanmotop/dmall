<?php

namespace App\Http\Controllers\Mall;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;

class CartsController extends Controller
{
    private $cartModel;

    public function __construct(Cart $cart)
    {
        $this->cartModel = $cart;
    }

    /**
     * 购物车
     */
    public function index(Request $request)
    {
        $uid = session('auth_user');
        $goodsList = $this->cartModel->getGoodsList($uid);
        return view('mall/carts', [
            'title'     => '我的购物车',
            'goodsList' => $goodsList,
        ]);
    }

    /**
     * 删除购物车的商品
     */
    public function del(Request $request)
    {
        if (Cart::where('id', '=', $request->cart_id)->delete()) {
            return ['code' => 10000];
        } else {
            return ['code' => 10001];
        }
    }

    /**
     * 购物车提交订单前的处理
     */
    public function prepare(Request $request)
    {
        $selectCarts = json_decode($request->selectGoods, true);
        $flag = $this->cartModel->checkGoodsAndUpdateCart($selectCarts); // 更新购物车数量
        if ($flag) {
            session(['carts_prepare' => $selectCarts]);
            return ['code' => 10000];
        } else {
            return ['code' => 10001];
        }
    }
}
