<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/10/25
 * Time: 11:33
 */

namespace App\Http\Controllers\User;


use App\Models\User;
use Illuminate\Http\Request;

class UserController
{
    /**
     * 用户中心
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function center()
    {
        return view('user/ucenter', ['title' => '个人中心']);
    }

    /**
     * 我的资料
     */
    public function info()
    {
        $user = session()->get('auth_user');
        $user = User::find($user->id);

        return view('user/info', ['title' => '我的资料', 'user' => $user]);
    }

    public function update(Request $request)
    {

    }
}