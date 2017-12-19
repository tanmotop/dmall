<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/6
 * Time: 10:00
 * Function: 库存统计
 */

namespace App\Admin\Controllers\Data;


use App\Models\Config;
use App\Models\Goods;
use App\Models\GoodsAttr;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class StockController
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('商品库存');
            $content->description('统计');
            $content->body($this->grid());
        });
    }

    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('库存');
            $content->body($this->form()->edit($id));
        });
    }

    protected function grid()
    {
        $warning = Config::find('stock_warning')->value;
        $grid = Admin::grid(GoodsAttr::class, function (Grid $grid) use ($warning) {
            $grid->id('序号');
            $grid->goods()->name('商品名称');
            $grid->goods()->logo('商品LOGO')->image(env('APP_URL') . '/uploads/', 60, 60);
            $grid->name('商品属性');
            $grid->stock('商品库存');
            $grid->price('零售价')->display(function ($price) {
                return '￥' . $price;
            });
            $grid->column('stock_status', '库存状态')->display(function () use ($warning) {
                if ($this->stock <= $warning) {
                    $msg = '<span class="label label-warning">库存不足</span>';
                }
                else {
                    $msg = '<span class="label label-primary">库存充足</span>';
                }

                if ($this->stock == 0) {
                    $msg = '<span class="label label-danger">无库存</span>';
                }
                return $msg;
            });
            $grid->order_goods('产品销量')->display(function ($orderGoods) {
                return array_sum(array_column($orderGoods, 'count'));
            });

            $grid->tools(function (Grid\Tools $tools) {
                $tools->disableBatchActions();
            });

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableDelete();
            });

            $grid->disableRowSelector();
            $grid->disableCreation();
            $grid->disableExport();
        });

        return $grid;
    }

    protected function form()
    {
        $form = Admin::form(GoodsAttr::class, function (Form $form) {
            $form->display('name', '属性');
            $form->number('weight', '重量/g');
            $form->number('pv', 'PV值');
            $form->number('stock', '库存量');
            $form->number('price', '零售价');
            $form->json_number('user_prices', ['level_1' => '钻石级价', 'level_2' => '经销商价', 'level_3' => 'VIP价']);
        });

        $form->saving(function (Form $form) {
            $form->ignore(['level_3', 'level_2', 'level_1']);

            $form->user_prices = json_encode([
                'level_3' => $form->level_3,
                'level_2' => $form->level_2,
                'level_1' => $form->level_1,
            ]);
        });

        return $form;
    }
}