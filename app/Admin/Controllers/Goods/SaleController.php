<?php

namespace App\Admin\Controllers\Goods;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\Goods;
use App\Models\GoodsCat;

class SaleController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('在售商品');
            $content->description('商品列表');
            $content->body($this->grid()->render());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('编辑商品');
            $content->description('编辑');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('添加商品');
            $content->description('添加');

            $content->body($this->form());
        });
    }

    // public function store()
    // {
    //     dd($this->form());
    // }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = Admin::grid(Goods::class, function (Grid $grid) {
            // 第一列显示id字段，并将这一列设置为可排序列
            $grid->id('ID')->sortable();
            $grid->logo('商品LOGO')->display(function ($logo) {
                $src = env('APP_URL') . '/uploads/' . $logo;
                return "<img src='{$src}' width='50' height='50'>";
            });
            $grid->sn('商品编号');
            $grid->name('商品名称');
            $grid->short_name('商品简称');
            $grid->keywords('关键词');
            // $grid->cat_id('商品分类');
            $grid->cat()->name('商品分类');
            $grid->status('商品状态');
            $grid->created_at('创建时间');
            $grid->updated_at('更新时间');
        });

        $grid->perPages([10, 20]);
        $grid->disableExport();
        $grid->paginate(10);

        $grid->filter(function($filter){
            $filter->disableIdFilter(); // 去掉默认的id过滤器
            $filter->like('name', 'name'); // 在这里添加字段过滤器
        });

        $grid->actions(function ($actions) {
            $actions->prepend('<a class="btn btn-link" href="">下架</a>');
        });

        $grid->model()->where('goods.status', '=', 1);


        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $userLevels = \App\Models\UserLevel::where('level', '>', 0)->orderBy('level', 'desc')->get();
        return Admin::form(Goods::class, function (Form $form) use ($userLevels) {

            $form->tab('商品基本信息', function (Form $form) {
                $form->display('id', '商品ID');
                $form->text('name', '商品标题')->rules('required');
                $form->text('short_name', '商品简称')->rules('required');
                $form->text('sn', '商品编号')->rules('required');
                $form->select('cat_id', '商品分类')->options((new GoodsCat)->getIdNameArray())->rules('required');
                $form->text('keywords', '商品关键字');
                $form->number('sort', '商品排序')->default(50)->placeholder('商品排序');
                $form->image('logo', '商品LOGO');
                // $form->display('created_at', '创建时间');
                // $form->display('updated_at', '更新时间');
            })->tab('商品规格', function ($form) use ($userLevels) {
                $form->hasMany('attrs', '商品规格', function (Form\NestedForm $form) use ($userLevels)  {
                    $form->hidden('goods_id');
                    $form->text('name', '规则名称');
                    $form->text('stock', '库存');
                    $form->text('weight', '规则重量');
                    $form->text('price', '零售价');
                    $form->text('pv', 'PV值');
                    $form->hidden('user_prices');
                    foreach ($userLevels as $key => $level) {         
                        $form->text('level_' . $level->level, $level->name . '价');
                    }
                });
            });

            $form->saving(function (Form $form) use ($userLevels) {
                $attrs = $form->attrs;
                foreach ($attrs as $key => $attr) {
                    foreach ($userLevels as $level) {
                        $k = 'level_' . $level->level;
                        $prices[$k] = $attr[$k];
                        unset($attrs[$key][$k]);
                    }
                    $attrs[$key]['user_prices'] = json_encode($prices);
                }
                $form->attrs = $attrs;
                // \DB::connection()->enableQueryLog();  
            });

            $form->saved(function (Form $form) {
                //
                // $log = \DB::getQueryLog();
                // dd($log);   //打印sql语句
            });
        });
    }

    // public function update()
    // {
    //     dd($_POST);
    // }
}
