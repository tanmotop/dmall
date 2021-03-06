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

class SoldoutController extends Controller
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
            $content->header('售完商品');
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
        $grid->disableCreation();
        $grid->paginate(10);

        $grid->filter(function($filter){
//            $filter->disableIdFilter(); // 去掉默认的id过滤器
            $filter->like('name', 'name'); // 在这里添加字段过滤器
        });

        $grid->actions(function ($actions) {
            // $actions->prepend('<a class="btn btn-link" href="">上架</a>');
        });

        $grid->model()->where('goods.status', '=', 2);


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
                $form->text('name', '商品标题')->rules('required');
                $form->text('short_name', '商品简称')->rules('required');
                $form->text('sn', '商品编号')->rules('required');
                $form->select('cat_id', '商品分类')->options((new GoodsCat)->getIdNameArray())->rules('required');
                $form->text('keywords', '商品关键字');
                $form->number('sort', '商品排序')->default(50)->placeholder('商品排序');
                $form->image('logo', '商品LOGO');
            })->tab('商品规格', function ($form) use ($userLevels) {
                $form->hasMany('attrs', '商品规格', function (Form\NestedForm $form) use ($userLevels)  {
                    $form->hidden('goods_id');
                    $form->text('name', '规则名称');
                    $form->text('stock', '库存');
                    $form->number('weight', '计费重量(克)');
                    $form->number('show_weight', '显示重量(克)');
                    $form->text('price', '零售价');
                    $form->number('pv', 'PV值');
                    $form->hidden('user_prices')->attribute(['data-up' => '1']);;
                    $priceArr = [];
                    foreach ($userLevels as $key => $level) {
//                        $priceArr['level_'.$level->level] = $level->name. '价';
                        $form->text('level_' . $level->level, $level->name . '价');
                    }
                    // $form->json_number('user_prices', ['level_1' => '钻石级价', 'level_2' => '经销商价', 'level_3' => 'VIP价']);

                    // $form->json_number('user_prices', $priceArr);
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
            });

            $form->saved(function (Form $form) {
            });
        });
    }

    // public function update()
    // {
    //     dd($_POST);
    // }
}
