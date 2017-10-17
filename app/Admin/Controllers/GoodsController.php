<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use App\Models\Goods;
use Encore\Admin\Layout\Content;

class GoodsController extends Controller
{
    public function sale()
    {
        return Admin::content(function (Content $content) {
            $content->header('在售商品');
            $content->description('商品列表');
            $content->body($this->grid()->render());
        });
    }

    public function grid()
    {
        $grid = Admin::grid(Goods::class, function (Grid $grid) {
            // 第一列显示id字段，并将这一列设置为可排序列
            $grid->id('ID')->sortable();
            $grid->sn('商品编号');
            $grid->name('商品名称');
            $grid->shor_name('商品简称');
            $grid->keywords('关键词');
            $grid->cat_id('商品分类');
            $grid->status('商品状态');
            $grid->created_at('创建时间');
            $grid->updated_at('更新时间');
        });

        $grid->perPages([10, 20]);
        $grid->disableExport();
        $grid->paginate(10);

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('name', 'name');
        });

        return $grid;
    }

    public function down()
    {
        echo '仓储商品';
    }

    public function soldout()
    {
        echo '售完商品';
    }

    public function cats()
    {
        echo '商品分类';
    }
}