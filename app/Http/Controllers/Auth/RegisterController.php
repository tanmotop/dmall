<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth/register', ['title' => '注册']);
    }

    public function contract()
    {
    	return view('auth/contract', ['title' => '注册协议']);
    }
}