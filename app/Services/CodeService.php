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
     * 生成激活码
     * @access public
     * @param $namespace
     * @return string
     */
    public function makeInviteCode($namespace = null)
    {
        return $this->generateCode();

        $guid = '';
        $uid = uniqid("", true);

        $data = $namespace;
        $data .= $_SERVER ['REQUEST_TIME'];     // 请求那一刻的时间戳
        $data .= $_SERVER ['HTTP_USER_AGENT'];  // 获取访问者在用什么操作系统
        $data .= $_SERVER ['SERVER_ADDR'];      // 服务器IP
        $data .= $_SERVER ['SERVER_PORT'];      // 端口号
        $data .= $_SERVER ['REMOTE_ADDR'];      // 远程IP
        $data .= $_SERVER ['REMOTE_PORT'];      // 端口信息

        for ($i = 0; $i < 10; $i++) {
            $data .= $this->code();
            mt_srand((double)microtime() * 1000000);
            $tmp1 = date('Ymd') . str_pad(mt_rand(1, 99999), 8, '0', STR_PAD_LEFT);
            $tmp2 = date('Ymd') . str_pad(mt_rand(1, 99999), 8, '0', STR_PAD_LEFT);
            if ($tmp1 > $tmp2) {
                $hash1 = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
                $guid .= $hash1{rand(0, 31)};
            } else {
                $hash2 = strtolower(hash('ripemd128', $uid . $guid . md5($data)));
                $guid .= $hash2{rand(0, 31)};
            }
        }
        return $guid;
    }

    /**
     * 生成随机字符串
     * @access public
     * @param $type
     * @param $length
     * @return string
     */
    private function code($type = 0, $length = 10)
    {
        if ($type == 1) {
            $len = 10;
        } else if ($type == 2) {
            $len = 33;
        } else {
            $len = 56;
        }
        $str = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            while (true) {
                $s = $str{rand(0, $len - 1)};
                if (substr_count($code, $s) == 0) {
                    $code .= $s;
                    break;
                }
            }
        }
        return $code;
    }

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