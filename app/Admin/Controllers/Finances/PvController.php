<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/19
 * Time: 21:37
 * Function: 业绩抽成
 */

namespace App\Admin\Controllers\Finances;


use App\Models\Pv;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class PvController
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('业绩');
            $content->description('抽成');
            $content->body($this->grid());
        });
    }

    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('添加');
            $content->header('业绩抽成');
            $content->body($this->form());
        });
    }

    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('编辑');
            $content->header('业绩抽成');
            $content->body($this->form()->edit($id));
        });
    }

    protected function grid()
    {
        $grid = Admin::grid(Pv::class, function (Grid $grid) {
            $grid->id('序号');
            $grid->target_pv('目标值')->display(function ($pv) {
                return $pv . '万';
            });
            $grid->percent('抽成返点')->display(function ($percent) {
                return $percent . '%';
            });
            $grid->describe('说明');
        });

        $grid->disableExport();
        $grid->disableFilter();

        return $grid;
    }

    protected function form()
    {
        $form = Admin::form(Pv::class, function (Form $form) {
            $form->number('target_pv', '目标值(万)')->rules('required');
            $form->rate('percent', '抽成返点')->rules('required');
            $form->textarea('describe', '说明')->rules('required');
        });

        return $form;
    }
}