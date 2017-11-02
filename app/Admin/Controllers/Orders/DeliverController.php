<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/2
 * Time: 15:14
 * Function: 待发货订单
 */

namespace App\Admin\Controllers\Orders;


use App\Admin\Extensions\OneKeyDeliver;
use App\Http\Controllers\Controller;
use App\Models\Orders;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class DeliverController extends Controller
{
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('待发货订单');
            $content->body($this->grid());
        });
    }

    public function grid()
    {
        $grid = Admin::grid(Orders::class, function (Grid $grid) {
            $grid->sn('订单号');
            $grid->user_id('下单代理商');
            $grid->user_name('买家');
            $grid->user_phone('手机');
            $grid->total_price('订单总价');
            $grid->created_at('下单/支付时间');
            $grid->post_way('配送方式')->display(function ($way) {
                return $way ==1 ? '快递配送' : '到店自提';
            });

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $row = $actions->row;
                $actions->prepend(new OneKeyDeliver($row->id, $row->sn));
            });
        });

        $grid->model()->where('status', 0);

        return $grid;
    }
}