<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/9/24
 * Time: 9:41
 */

namespace App\Services;
use App\Models\User;
use App\Models\UserCode;

class CodeService
{
    /**
     * 生成邀请码
     * 
     * @return string 邀请码
     */
    public function generateCode()
    {
        $user = session('auth_user');
        $invitedCount = User::where('parent_id', '=', $user->id)->count();
        $str = md5($user->id . '_' . $invitedCount . '_' . time() . rand(100, 999));

        $code = strtoupper(mb_substr($str, 0, 10));

        if (UserCode::where('code', '=', $code)->count()) {
            $code = $this->generateCode();
        }

        return $code;
    }
}