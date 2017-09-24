<?php

namespace App\Http\Controllers\Mall;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartsController extends Controller
{
    public function index()
    {
        return view('mall/carts', ['title' => '我的购物车']);
    }
}
