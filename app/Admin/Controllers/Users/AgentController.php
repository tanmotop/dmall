<?php

namespace App\Admin\Controllers\Users;

use App\Admin\Extensions\Exporter\ExcelExporter;
use App\Jobs\InviteUser;
use App\Models\RechargeLog;
use Carbon\Carbon;
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

            $content->header('编辑');
            $content->description('代理商');

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
            $grid->column('上级代理商')->display(function () {
                if ($this->parent_id > 0) {
                    $parent = User::where('id', $this->parent_id)->first();
                }

                return $this->parent_id > 0 ? $parent->realname : '不二大山';
            });
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
            $grid->exporter($this->exporter($grid));
        });

        $grid->exporter($this->exporter($grid));
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
            $form->number('money', '余额')->rules('required|regex:/^\d+(\.\d+)?$/', [
                'required' => '余额不能为空',
                'regex' => '余额不能为负数',
            ]);
            $form->display('recharge', '累计充值')->with(function () {
                $money = RechargeLog::where('uid', $this->id)->sum('money');
                return $money;
            });
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
            $form->switch('status', '激活状态')->states([
                'on' => ['value' => 1, 'text' => '激活', 'color' => 'primary'],
                'off' => ['value' => 0, 'text' => '未激活', 'color' => 'danger']
            ]);
            $form->ignore(['password', 'repasswd']);
            $form->image('avatar', '头像');
            
            $form->saving(function (Form $form) {
                ///
                $pwd = request('password');
                if (!empty($pwd)) {
                    $form->password = md5($pwd);
                }

                ///
                $user = $form->model();
                if ($user->status == 0 && $form->status == 'on') {
                    $this->dispatch(new InviteUser($user));
                    $user->actived_at = Carbon::now();
                }

                if ($user->level == User::$LEVEL_VIP && $form->level == User::$LEVEL_DEALER) {
                    $this->dispatch(new InviteUser($user));
                }
            });

            $form->saved(function (Form $form) {
                //
            });
        });
    }

    private function exporter(Grid $grid)
    {
        $header = ['代理商编号', '激活码', '用户名', '真实姓名', '身份证', '邮箱', '微信号', '手机号', '注册时间', '激活时间', '激活状态', '账户余额', '代理商等级'];
        return new ExcelExporter($grid, function (ExcelExporter $excelExporter) use ($header) {
            $excelExporter->setFilename('代理商列表');
            $excelExporter->setHeader($header);

            $userLevel = (new UserLevel())->getLevelNameArray();
            $excelExporter->rowHandle(function (array $item) use ($userLevel) {
                $row[] = $item['id'];
                $row[] = $item['invitation_code'];
                $row[] = $item['username'];
                $row[] = $item['realname'];
                $row[] = $item['id_card_num'];
                $row[] = $item['email'];
                $row[] = $item['wechat'];
                $row[] = $item['phone'];
                $row[] = $item['created_at'];
                $row[] = $item['actived_at'];
                $row[] = $item['status'] == 1 ? '已激活' : '未激活';
                $row[] = $item['money'];
                $row[] = $userLevel[$item['level']];

                return $row;
            });
        });
    }
}
