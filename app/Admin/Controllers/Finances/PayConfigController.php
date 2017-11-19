<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/17
 * Time: 17:22
 * Function:
 */

namespace App\Admin\Controllers\Finances;


use App\Http\Controllers\Controller;
use App\Models\PayConfig;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;

class PayConfigController extends Controller
{
    use ModelForm;

    public function index()
    {
        return redirect('/admin/finances/payConfig/1/edit');
    }

    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('支付');
            $content->description('设置');
            $content->body($this->form()->edit($id));
        });
    }

    protected function form()
    {
        return Admin::form(PayConfig::class, function (Form $form) {
            $form->html('<h4>银行信息设置</h4>');
            $form->text('bank_name', '银行名称');
            $form->text('bank_card', '银行卡号');
            $form->text('bank_user', '开户人');
            $form->text('bank_address', '开户地址');

            $form->divider();
            $form->html('<h4>支付宝信息设置</h4>');
            $form->text('alipay_name', '会员名');
            $form->text('alipay_phone', '手机号');
            $form->text('alipay_user', '开户人');

            $form->divider();
            $form->html('<h4>微信二维码设置</h4>');
            $form->image('wechat_qr_code', '二维码');

            $form->tools(function (Form\Tools $tools) {
                $tools->disableListButton();
                $tools->disableBackButton();
            });
        });
    }
}