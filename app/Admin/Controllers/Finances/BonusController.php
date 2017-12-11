<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/20
 * Time: 14:46
 * Function:
 */

namespace App\Admin\Controllers\Finances;


use App\Models\Orders;
use App\Models\User;
use App\Models\UserBonus;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class BonusController
{
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('奖金');
            $content->description('查询');
            $content->body($this->grid());
        });
    }

    protected function grid()
    {
        $grid = Admin::grid(User::class, function (Grid $grid) {
            $grid->id('代理商编号');
            $grid->realname('姓名');
            $grid->avatar('头像')->image(config('filesystems.disks.admin.url') . '/', 60, 60);
            $grid->column('上级代理商')->display(function () {
                if ($this->parent_id > 0) {
                    $parent = User::where('id', $this->parent_id)->first();
                }

                return $this->parent_id > 0 ? $parent->realname : '顶级代理商';
            });
            $grid->column('实际销售额')->display(function () {
                $totalPrice = Orders::where([
                    ['user_id', $this->id],
                    ['status', 2]
                ])->sum('total_price');

                return $totalPrice;
            });
            $grid->column('级别差价')->display(function () {
                return $this->bonus()->sum('level_money');
            });
            $grid->column('邀代奖金')->display(function () {
                return $this->bonus()->sum('invite_money');
            });
            $grid->column('个人零售利润')->display(function () {
                return $this->bonus()->sum('retail_money');
            });
            $grid->column('个人业绩合计(PV值)')->display(function () {
                return $this->bonus()->sum('personal_pv');
            });
            $grid->column('团队业绩合计(PV值)')->display(function () {
                return $this->bonus()->sum('teams_pv');
            });
        });

        $grid->disableRowSelector();
        $grid->disableCreation();
        $grid->disableActions();

        return $grid;
    }
}