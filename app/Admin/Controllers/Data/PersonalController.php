<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/6
 * Time: 10:11
 * Function: 个人销售统计
 */

namespace App\Admin\Controllers\Data;


use App\Models\User;
use App\Models\UserLevel;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class PersonalController
{
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('个人消费');
            $content->description('排行');
            $content->body($this->grid());
        });
    }

    protected function grid()
    {
        $grid = Admin::grid(User::class, function (Grid $grid) {
            $levels = (new UserLevel)->getLevelNameArray();
            $grid->id('代理商编号');
            $grid->realname('姓名');
            $grid->phone('手机');
            $grid->level('代理商等级')->display(function ($level) use ($levels) {
                return $levels[$level];
            });
            $grid->orders('订单量')->display(function ($orders) {
                return count($orders);
            });
            $grid->column('消费金额')->display(function () {
                return array_sum(array_column($this->orders, 'total_price'));
            });
            $grid->column('总PV值')->display(function () {
                return array_sum(array_column($this->orders, 'total_pv'));
            });
        });

        $grid->disableRowSelector();
        $grid->disableActions();
        $grid->disableCreation();

        return $grid;
    }
}