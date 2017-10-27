<?php

namespace App\Admin\Controllers\Base;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\Freight;
use App\Models\Courier;
use App\Models\Region;

class FreightController extends Controller
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
            $content->header('快递公司');
            $content->description('公司列表');
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
        $grid = Admin::grid(Freight::class, function (Grid $grid) {
            // 第一列显示id字段，并将这一列设置为可排序列
            $grid->id('ID')->sortable();
            $grid->region()->name('省份');
            $grid->courier()->name('快递公司');
            $grid->norm_weight('标准重量')->display(function($value) { return $value . 'kg';});
            $grid->norm_price('正常运费')->display(function($value) { return '￥' . $value; });
            $grid->over_first_price('超重首重运费')->display(function($value) { return '￥' . $value . '/kg'; });
            $grid->over_next_price('超重续重运费')->display(function($value) { return '￥' . $value . '/kg'; });
            $grid->faraway('地区状态')->display(function($value) { return $value == 1 ? '偏远' : '正常'; });
            // $grid->created_at('创建时间');
            // $grid->updated_at('更新时间');
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
        return Admin::form(Freight::class, function (Form $form) {
            $form->display('id', '商品ID');
            $form->select('courier_id', '快递公司')->options((new Courier)->getIdNameArray())->rules('required');
            $form->select('region_id', '省份地区')->options((new Region)->getProvinceIdNameArray())->rules('required');
            $form->select('faraway', '地区状态')->options([
                '0' => '正常',
                '1' => '偏远'
            ]);
            $form->number('norm_weight', '标准重量');
            $form->number('norm_price', '正常运费');
            $form->number('over_first_price', '超重首重运费(￥/kg)');
            $form->number('over_next_price', '超重续费运费(￥/kg)');
           
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
