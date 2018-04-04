<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/20
 * Time: 10:34
 * Function:邀代奖金
 */

namespace App\Admin\Controllers\Finances;


use App\Http\Controllers\Controller;
use App\Models\InviteBonus;
use App\Models\UserLevel;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class InviteController extends Controller
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('邀代');
            $content->description('奖金');
            $content->body($this->grid());
        });
    }

    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('编辑');
            $content->description('奖金规则');
            $content->body($this->form()->edit($id));
        });
    }

    protected function grid()
    {
        $levels = (new UserLevel)->getLevelNameArray();
        $grid = Admin::grid(InviteBonus::class, function (Grid $grid) use ($levels) {
            $grid->id('序号');
            $grid->rule('邀请规则')->display(function ($rule) use ($levels) {
                $ruleArr = explode(',', $rule);

                return implode(' > ', array_map(function ($v) use ($levels) {
                    return $levels[$v];
                }, $ruleArr));
            });

            $grid->bonus('奖金金额')->display(function ($bonus) {
                return '￥'. $bonus;
            });
        });

        $grid->disableFilter();
        $grid->disableExport();
        $grid->disableCreation();
        $grid->disableRowSelector();
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableDelete();
        });

        return $grid;
    }

    protected function form()
    {
        return Admin::form(InviteBonus::class, function (Form $form) {
            $form->currency('bonus', '奖金金额')->symbol('￥')->rules('required');

            $form->disableReset();
        });
    }
}