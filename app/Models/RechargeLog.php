<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/10/30
 * Time: 21:46
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RechargeLog extends Model
{
    protected $table = 'finance_recharge_log';

    public function getMembersRecharge($uid)
    {
        $users = User::select('id')->where('parent_id', '=', $uid)->orWhere('id', '=', $uid)->get()->toArray();
        $ids = array_column($users, 'id');

        if (!empty($ids)) {
            $money = $this->whereIn('uid', $ids)->sum('money');
            return $money;
        }

        return 0;
    }
}