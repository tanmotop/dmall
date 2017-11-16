<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/10/25
 * Time: 11:33
 */

namespace App\Http\Controllers\User;


use App\Http\Requests\UserEditRequest;
use App\Models\RechargeLog;
use App\Models\User;
use App\Models\UserLevel;
use Illuminate\Http\Request;

class UserController
{
    /**
     * @var UserLevel
     */
    protected $userLevel;

    /**
     * UserController constructor.
     * @param UserLevel $userLevel
     */
    public function __construct(UserLevel $userLevel)
    {
        $this->userLevel = $userLevel;
    }

    /**
     * 用户中心
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function center()
    {
        $user = session()->get('auth_user');
        $user = User::find($user->id);
        $levels = $this->userLevel->getLevelNameArray();
        $title = '个人中心';
        $teamCount = (new User)->getMemberCount($user->id);
        $recharge = (new RechargeLog)->getMembersRecharge($user->id);

        return view('user/ucenter', compact('title', 'user', 'levels', 'teamCount', 'recharge'));
    }

    /**
     * 我的资料
     */
    public function info()
    {
        $user = session()->get('auth_user');
        $user = User::find($user->id);
        $title = '我的资料';

        return view('user/info', compact('title', 'user', 'teamCount'));
    }

    public function update(UserEditRequest $request)
    {
        $uid = $request->id;
        $user = User::find($uid);
        $user->realname = $request->realname;
        $user->phone = $request->phone;
        $user->wechat = $request->wechat;

        if (!empty($request->new_password) && !empty($request->old_password)) {
            if (md5($request->old_password) != $user->password) {
                return response(['code' => 10001, 'msg' => '旧密码错误'], 422);
            }

            $user->password = md5($request->new_password);
        }
        $user->save();

        return response()->json(['code' => 10000, 'msg' => '修改成功']);
    }

    public function avatar(Request $request)
    {
        $uid = $request->id;
        $path = $request->file('avatar')->store('avatars', 'avatar');

        $user = User::find($uid);
        $oldAvatar = $user->avatar;
        $user->avatar = $path;
        $user->save();

        $path = public_path('uploads/') . $oldAvatar;
        if (file_exists($path)) {
            @unlink($path);
        }

        return response()->redirectToRoute('ucenter');
    }
}