<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/10/30
 * Time: 21:46
 */

namespace App\Models;


use App\Services\CodeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RechargeLog extends Model
{
    protected $table = 'finance_recharge_log';

    protected $fillable = [
        'sn',
        'uid',
        'realname',
        'money',
        'money_pre',
        'money_after',
        'status',
        'way',
        'describe',
        'remark'
    ];

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

    /**
     * @param User $user
     * @param $money
     * @param $describe
     * @param null $remark
     * @param int $way
     */
    public function addLog(User $user, $money, $describe, $remark = null, $way = 1)
    {
        $this->create([
            'sn' => (new CodeService())->makeRechargeSn($user->id),
            'uid' => $user->id,
            'realname' => $user->realname,
            'money' => $money,
            'money_pre' => $user->money,
            'money_after' => $user->money + $money,
            'status' => 1,
            'way' => $way,
            'describe' => $describe . "￥{$money}",
            'remark' => $remark
        ]);
    }
}