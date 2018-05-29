<?php

namespace App\Jobs;

use App\Models\InviteBonus;
use App\Models\User;
use App\Models\UserBonus;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DelInviteUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $level;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user,$level)
    {
        $this->user = $user;
        $this->level = $level;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(InviteBonus $inviteBonus, UserBonus $userBonus)
    {
        /// 计算邀代奖金
        $parent = User::find($this->user->parent_id);
        $this->user->level=$this->level;
        $bonuses = $inviteBonus->getBonus($parent, $this->user);

        //获得用户激活时间
        $actived_at = date('Y-m-d',strtotime($this->user->actived_at));

        foreach ($bonuses as $userId => $money) {
            $userBonus->delInviteMoney($userId, $money, $actived_at);
        }
    }
}
