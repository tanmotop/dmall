<?php

namespace App\Admin\Controllers\Users;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\UserLevel;
use App\Models\User;

class AgentController extends Controller
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
            $content->header('代理商列表');
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
        $levels = (new UserLevel)->getLevelNameArray();
        $grid = Admin::grid(User::class, function (Grid $grid) use ($levels) {
            // 第一列显示id字段，并将这一列设置为可排序列
            // $grid->id('ID');
            // $grid->level('等级');
            $grid->username('用户名');
            $grid->realname('真实姓名');
            $grid->invitation_code('激活码');
            $grid->level('等级')->display(function($level) use ($levels) {
                return $levels[$level];
            });
            $grid->id_card_num('身份证');
            $grid->wechat('微信');
            $grid->phone('手机号');
            $grid->created_at('注册时间');
            $grid->actived_at('激活时间');
            $grid->status('激活状态')->display(function($status) {
                return $status == 1 ? '已激活' : '未激活';
            });
        });

        $grid->perPages([10, 20]);
        // $grid->disableExport();
        $grid->disableCreation();
        $grid->paginate(10);

        $grid->filter(function($filter){
            $filter->disableIdFilter(); // 去掉默认的id过滤器
            $filter->equal('username', '用户名');
            $filter->like('realname', '真实姓名'); // 在这里添加字段过滤器
            // 设置created_at字段的范围查询
            $filter->equal('status', '激活状态')->select([
                1 => '已激活',
                0 => '未激活',
            ]);
            $filter->between('created_at', '注册时间')->datetime();
            $filter->between('actived_at', '激活时间')->datetime();
        });

        $grid->model()->orderBy('id', 'asc');

        $grid->actions(function ($actions) {
            $actions->disableDelete();
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
        return Admin::form(User::class, function (Form $form) {
            $form->display('id', '用户编号');
            $form->display('invitation_code', '邀请码');
            $form->display('username', '用户名称');
            $form->display('created_at', '注册时间');
            $form->display('actived_at', '激活时间');
            $form->password('password', '密码');
            $form->password('repasswd', '确认密码')->rules('same:password', [
                'same' => '两次密码不一致',
            ]);
            $form->select('level', '代理级别')->options((new UserLevel)->getLevelNameArray())->rules('required');
            $form->text('realname', '真实姓名')->rules('required', [
                'required' => '真实姓名不能为空'
            ]);
            $form->text('id_card_num', '身份证')->rules('required', [
                'required' => '身份证不能为空'
            ]);
            $form->text('wechat', '微信号')->rules('required', [
                'required' => '微信号不能为空'
            ]);
            $form->text('phone', '手机号')->rules('required', [
                'required' => '手机号不能为空'
            ]);
            
            $form->saving(function (Form $form) {
                // if ($form->repasswd != $form->password) {
                //     $error = new MessageBag(['repasswd' => '两次密码不一致']);
                //     return back()->with(compact('error'));
                // }
                // unset($form->repasswd);
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
