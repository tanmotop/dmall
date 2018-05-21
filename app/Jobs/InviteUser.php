<?php

namespace App\Jobs;

use App\Models\InviteBonus;
use App\Models\RechargeLog;
use App\Models\User;
use App\Models\UserBonus;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class InviteUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    protected $user;

    /**
     * InviteUser constructor.
     * @param User $user 注册用户
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param InviteBonus $inviteBonus
     * @param UserBonus $userBonus
     * @param RechargeLog $rechargeLog
     */
    public function handle(InviteBonus $inviteBonus, UserBonus $userBonus)
    {
        /// 计算邀代奖金
        $parent = User::find($this->user->parent_id);

        $bonuses = $inviteBonus->getBonus($parent, $this->user);

        foreach ($bonuses as $userId => $money) {
            $userBonus->saveInviteMoney($userId, $money);

            ///取消自动添加邀带奖金到余额并且不生成充值记录

            //$user = User::find($userId);
            //$rechargeLog->addLog($user, $money, '邀代奖励');
            //$user->money += $money;
            //$user->save();
        }
    }
}
