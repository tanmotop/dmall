<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/12/19
 * Time: 14:48
 * Function:
 */

namespace App\Admin\Controllers\Base;


use App\Http\Controllers\Controller;
use App\Models\Config;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;

class StockController extends Controller
{
    use ModelForm;

    public function index()
    {
        return redirect('/admin/base/stock/stock_warning/edit');
    }

    public function edit($key)
    {
        return Admin::content(function (Content $content) use ($key) {
            $content->header('库存预警设置');
            $content->body($this->form()->edit($key));
        });
    }

    protected function form()
    {
        return Admin::form(Config::class, function (Form $form) {
            $form->number('value', '库存预警');
            $form->disableReset();
            $form->tools(function (Form\Tools $tools) {
                $tools->disableBackButton();
                $tools->disableListButton();
            });
        });
    }
}