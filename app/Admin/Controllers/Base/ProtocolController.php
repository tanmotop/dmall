<?php

namespace App\Admin\Controllers\Base;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\RegProtocol;

class ProtocolController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return redirect('/admin/protocol/1/edit');
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id = 1)
    {
        $id = 1;
        return Admin::content(function (Content $content) {

            $content->header('用户注册协议');
            $content->description('编辑');

            $content->body($this->form()->edit(1));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return redirect('');
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(RegProtocol::class, function (Form $form) {
            $form->text('name', '协议名称')->rules('required');
            $form->editor('content', '协议内容');
           
            $form->saving(function (Form $form) {
                //
            }); 

            $form->saved(function (Form $form) {
                //
            });
        });
    }
}
