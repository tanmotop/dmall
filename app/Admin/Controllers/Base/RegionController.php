<?php

namespace App\Admin\Controllers\Base;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Tree;
use App\Models\Region;

class RegionController extends Controller
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
            $content->header('地区设置');
            $content->description('PS: 各地区变化较少，请勿随意更改');
            $content->a = 1;
            $content->body(Region::tree());
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

            $content->header('编辑快递公司');
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

            $content->header('添加快递公司');
            $content->description('添加');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = Admin::grid(Region::class, function (Grid $grid) {
            // 第一列显示id字段，并将这一列设置为可排序列
            $grid->id('ID')->sortable();
            $grid->name('地区名称');
            $grid->parent_id('上级id');
            $grid->level('地区类型');
        });

        $grid->perPages([10, 20]);
        $grid->disableExport();
        $grid->paginate(50);

        $grid->filter(function($filter){
            $filter->disableIdFilter(); // 去掉默认的id过滤器
            $filter->like('name', 'name'); // 在这里添加字段过滤器
        });

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Region::class, function (Form $form) {
            $form->display('id', '地区ID');
            $form->text('name', '地区标题')->rules('required');
           
            $form->saving(function (Form $form) {
                // 
            });

            $form->saved(function (Form $form) {
                //
            });
        });
    }
}
