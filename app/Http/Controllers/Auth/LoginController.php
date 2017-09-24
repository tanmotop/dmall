<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     * 登录页面
     * 
     * @return View
     */
    public function index()
    {
        return view('auth/login', ['title' => '登录']);
    }
}