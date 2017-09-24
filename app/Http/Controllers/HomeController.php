<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function home()
    {
        return view('home', ['title' => '团队管理系统']);
    }
<<<<<<< HEAD

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
        return view('service');
    }
=======
>>>>>>> 131b41fefd889b59f059b26d68a8d11a40db6831
}
