<?php

namespace App\Jobs;

use App\Models\Orders;
use App\Models\User;
use App\Models\UserBonus;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ConfirmOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Orders
     */
    protected $order;

    /**
     * ConfirmOrder constructor.
     * @param Orders $order
     */
    public function __construct(Orders $order)
    {
        //
        $this->order = $order;
    }

    /**
     * @param UserBonus $userBonus
     */
    public function handle(UserBonus $userBonus)
    {
        $order = $this->order;
        $user = $order->user;

        /// 计算个人PV
        $userBonus->savePersonalPv($order->user_id, $order->total_pv);

        /// 计算实际销售额
        $userBonus->saveActualSales($order->user_id, $order->total_money);

        /// 计算级别差价、计算个人零售利润
        $levelMoney = 0;
        $retailMoney = 0;
        $parent = User::find($user->parent_id);
        foreach ($order->orderGoods as $orderGoods) {
            $count = $orderGoods->count;
            $userPrices = json_decode($orderGoods->goodsAttr->user_prices, true);

            /// 计算级别差价
            if ($user->level == User::$LEVEL_VIP && $parent) {
                $diff = $userPrices['level_' . $user->level] - $userPrices['level_' . $parent->level];
                $levelMoney += $diff * $count;
            }

            /// 计算零售利润
            $profit = $orderGoods->goodsAttr->price - $userPrices['level_' . $user->level];
            $retailMoney += $profit * $count;
        }

        ///
        if ($levelMoney > 0) {
            $userBonus->saveLevelMoney($user->parent_id, $levelMoney);
        }

        ///
        if ($retailMoney > 0) {
            $userBonus->saveRetailMoney($user->id, $retailMoney);
        }

        /// 计算团队PV
        $this->saveTeamsPv($user, $order->total_pv, $userBonus);
    }

    /**
     * @param User $user
     * @param $pv
     * @param UserBonus $userBonus
     */
    protected function saveTeamsPv(User $user, $pv, UserBonus $userBonus)
    {
        if ($user) {
            $userBonus->saveTeamsPv($user->id, $pv);
        }

        if ($user->parent_id > 0) {
            $parent = User::find($user->parent_id);
            if ($parent) {
                $this->saveTeamsPv($parent, $pv, $userBonus);
            }
        }
    }
}
