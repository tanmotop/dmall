<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (session('auth_user')->status == 0) {
                return redirect()->route('home_unactive');
            }

            return $next($request);
        })->except('unactive');
    }

    //
    public function home()
    {
        return view('home', ['title' => '团队管理系统']);
    }

    public function unactive()
    {
        return view('home_unactive', ['title' => '团队管理系统']);
    }

    /**
     * 代理商管理页面
     */
    public function agents()
    {
        return view('agents/index', ['title' => '代理商管理']);
    }

    /**
     * 财务管理页面
     */
    public function finances()
    {
        return view('finances/index', ['title' => '财务管理']);
    }

    /**
     * 商品管理页面
     */
    public function mall()
    {
        return view('mall/index', ['title' => '商品管理']);
    }

    /**
     * 客服中心管理页面
     */
    public function service()
    {
        return view('service/index', ['title' => '客服中心']);
    }
}
