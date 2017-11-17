<?php

namespace App\Admin\Controllers\Users;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\UserLevel;

class LevelController extends Controller
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
            $content->header('等级列表');
//            $content->description('分类列表');
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

            $content->header('编辑分类');
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

            $content->header('添加分类');
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
        $grid = Admin::grid(UserLevel::class, function (Grid $grid) {
            // 第一列显示id字段，并将这一列设置为可排序列
//            $grid->id('ID');
            $grid->level('等级');
            $grid->name('等级名称');
            $grid->upgrade_condition('升级说明');
            $grid->first_amount('首充金额');
            $grid->second_amount('再充金额');
            $grid->upgrade_way('升级方式')->display(function ($way) {
                return $way == 1 ? '手动' : '自动';
            });
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

        $grid->model()->orderBy('level', 'asc');

        // $grid->actions(function ($actions) {
        //     $actions->prepend('<a class="btn btn-link" href="">上架</a>');
        // });

        // $grid->model()->where('goods.status', '=', 0);

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(UserLevel::class, function (Form $form) {
            $form->display('id', '分类ID');
            $form->text('name', '等级名称')->rules('required');
            $form->number('level', '等级')->rules('required|min:0');
            $form->number('first_amount', '首充金额')->rules('required');
            $form->radio('upgrade_way', '升级模式')->options([
                1 => '手动',
                2 => '自动'
            ]);
            $form->number('second_amount', '再充金额')->rules('required');  
            $form->textarea('upgrade_condition', '升级说明')->rules('required');

            // $form->number('sort', '分类排序')->default(50)->placeholder('分类排序');
            
            $form->saving(function (Form $form) {
                // $form->name = '商品3_x';
            });

            $form->saved(function (Form $form) {
                //
            });
        });
    }

    // public function update()
    // {
    //     dd($_POST);
    // }
}
