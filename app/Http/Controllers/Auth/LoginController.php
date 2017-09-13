<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function home()
    {
        return view('home', ['title' => '登录']);
    }
}