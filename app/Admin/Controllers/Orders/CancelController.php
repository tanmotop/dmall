<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/2
 * Time: 15:10
 * Function: 已取消订单
 */

namespace App\Admin\Controllers\Orders;


use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\Region;
use Carbon\Carbon;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class CancelController extends Controller
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('已取消的订单');
            $content->body($this->grid());
        });
    }

    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('订单详情');

            $order = Orders::find($id);
            $content->row(function (Row $row) use ($order) {
                $regions = (new Region)->getAllIdNameArray();
                $row->column(12, function (Column $column) use ($order, $regions) {
                    $column->append(view('admin::orders.detail', compact('order', 'regions')));
                });
            });
            $content->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append(view('admin::orders.goods'));
                });
            });
            $content->row($this->form()->edit($id));
        });
    }

    protected function grid()
    {
        $grid = Admin::grid(Orders::class, function (Grid $grid) {
            $grid->sn('订单号');
            $grid->user_id('下单代理商')->display(function ($uid) {
                return substr(1000000 + (int)$uid, 1);
            });
            $grid->user_name('买家');
            $grid->user_phone('手机');
            $grid->total_price('订单总价');
            $grid->created_at('下单/支付时间');
            $grid->status('状态')->display(function ($status) {
                return '已取消';
            });
            $grid->post_way('配送方式')->display(function ($way) {
                return $way ==1 ? '快递配送' : '到店自提';
            });

            $grid->disableCreation();
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableDelete();
            });
        });

        $grid->model()->where('status', 3);

        return $grid;
    }

    protected function form()
    {
        $form = Admin::form(Orders::class, function (Form $form) {
            $form->tab('订单操作', function (Form $form) {
                $form->select('status', '订单状态')->options([
                    0 => '未发货',
                    1 => '已发货',
                    2 => '已完成',
                    3 => '已取消'
                ]);
                $form->text('postid', '快递单号');
            })->tab('物流详情', function (Form $form) {
                $form->display('postid', '快递单号');
            });
        });

        $form->saving(function (Form $form) {
            $status = $form->status;
            $order = $form->model();

            if ($status == 2 && $order->status != 2) {
                $order->canceled_at = null;
                $order->completed_at = Carbon::now();
                $order->save();
            }

            if ($status == 3 && $order->status != 3) {
                $order->completed_at = null;
                $order->canceled_at = Carbon::now();
                $order->save();
            }
        });

        return $form;
    }
}