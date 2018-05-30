<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserLevel;

class LoginController extends Controller
{
    /**
     * 登录页面
     * 
     * @return View
     */
    public function index()
    {
        if (request()->has('relogin')) {
            session()->forget('auth_user');
            return redirect(route('login'));
        }
        if (session('auth_user')) {
            return redirect()->back();
        }
        return view('auth/login', ['title' => '登录']);
    }

    /**
     * 登录
     *
     * @param Request $request
     * @return int
     */
    public function submit(Request $request)
    {
    	$username = $request->username;
    	$password = $request->password;

        $user = User::where('username', '=', $username)->first();
        if(!$user) {
            return 0;
        }
        if (md5($password) == $user->password) {
            unset($user->password);
            session(['auth_user' => $user]);
            (new UserLevel)->saveUserLevelsToSession();
            session()->save();
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * 退出登录
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel()
    {
       session()->forget('auth_user');
       session()->forget('user_levels');

       return redirect()->route('login');
    }
}