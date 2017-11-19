<?php

namespace App\Admin\Controllers\Service;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\Material;
use App\Models\MaterialType;

class MaterialController extends Controller
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
            $content->header('培训资料');
            $content->description('资料列表');
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
        $grid = Admin::grid(Material::class, function (Grid $grid) {
            // 第一列显示id字段，并将这一列设置为可排序列
            $grid->id('序号')->sortable();
            $grid->title('标题');
            $grid->sort('排序')->sortable();
            $grid->attach('有无附件')->display(function ($status) {
                return $status == '' ? '无显示' : '有附件';
            });
            $grid->status('状态')->display(function ($status) {
                return $status == 1 ? '显示' : '隐藏';
            });
            $grid->created_at('添加时间');
        });

        $grid->perPages([10, 20]);
        $grid->disableExport();
        $grid->paginate(10);

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
        $types = (new MaterialType)->getIdNameArray();
        return Admin::form(Material::class, function (Form $form) use ($types) {
            $form->select('type_id', '分类')->options($types)->rules('required');
            $form->text('title', '标题')->rules('required');
            // $form->editor('content', '资料内容');
            $form->number('sort', '排序')->default(50)->placeholder('排序');
            // 添加文件删除按钮
            $form->file('attach', '附件')->removable();
            $form->radio('status', '分类状态')->options([1 => '显示', 0 => '隐藏'])->default(1);
        });
    }

    // public function update()
    // {
    //     dd($_POST);
    // }
}
