<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/10/30
 * Time: 21:15
 */

namespace App\Admin\Controllers\Finances;


use App\Admin\Extensions\Exporter\ExcelExporter;
use App\Http\Controllers\Controller;
use App\Models\RechargeLog;
use App\Models\User;
use App\Services\CodeService;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class RechargeController extends Controller
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('充值记录');
            $content->description('充值');
            $content->body($this->grid());
        });
    }

    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('管理员充值');
            $content->description('充值');
            $content->body($this->form());
        });
    }

    protected function grid()
    {
        $grid = Admin::grid(RechargeLog::class, function (Grid $grid) {
            $grid->sn('充值单号');
            $grid->uid('代理商编号');
            $grid->realname('姓名');
            $grid->money('充值金额');
            $grid->money_pre('充值前金额');
            $grid->money_after('充值后金额');
            $grid->created_at('充值时间');
            $grid->status('充值状态')->display(function($status) {
                return $status == 1 ? '充值成功' : '充值失败';
            });
            $grid->describe('充值说明');
            $grid->remark('备注');
            $grid->disableActions();
            $grid->disableRowSelector();
            $grid->exporter($this->exporter($grid));
        });

        ///
        $grid->filter(function (Grid\Filter $filter) {
            $filter->equal('sn', '充值单号');
            $filter->like('realname', '姓名');
            $filter->equal('status', '充值状态')->select([
                1 => '充值成功',
                2 => '充值失败'
            ]);

            $filter->equal('way', '充值方式')->select([
                1 => '管理员充值',
                2 => '在线充值'
            ]);

            $filter->between('created_at', '充值时间')->datetime();
        });

        ///
        $grid->model()->orderBy('created_at', 'desc');
        $grid->perPages([10, 20]);
        $grid->paginate(10);

        return $grid;
    }

    protected function form()
    {
        $users = [];
        $status = [
            0 => '未激活',
            1 => '已激活'
        ];

        foreach (User::all() as $user) {
            $users[$user->id] = "{$user->id} {$user->realname} {$user->phone} {$status[$user->status]}";
        }

        ///
        return Admin::form(RechargeLog::class, function (Form $form) use ($users) {
            $form->number('money', '充值金额')->rules('required|numeric|min:0.1', [
                'min' => '请输入大于0的金额'
            ]);
            $form->select('uid', '选择代理商')->options($users)->rules('required', [
                'required' => '请选择代理商'
            ]);
            $form->textarea('remark', '备注')->rows(5);
            $form->hidden('sn');
            $form->hidden('realname');
            $form->hidden('money_pre');
            $form->hidden('money_after');
            $form->hidden('describe');

            $form->tools(function (Form\Tools $tools) {
                $tools->disableListButton();
            });

            $form->saving(function (Form $form) {
                $user = User::find($form->uid);
                $form->realname = $user->realname;
                $form->money_pre = $user->money;
                $form->money_after = $form->money + $form->money_pre;
                $form->describe = "管理员充值￥{$form->money}";
                $form->sn = (new CodeService)->makeRechargeSn($form->uid);

                $user->money = $form->money_after;
                $user->save();
            });
        });
    }

    private function exporter(Grid $grid)
    {
        $header = ['充值单号', '姓名', '充值金额', '充值前金额', '充值后余额', '充值时间', '充值状态', '充值说明', '备注'];
        return new ExcelExporter($grid, function (ExcelExporter $excelExporter) use ($header) {
            $excelExporter->setFilename('充值记录');
            $excelExporter->setHeader($header);

            $excelExporter->rowHandle(function (array $item) {
                $row[] = $item['sn'];
                $row[] = $item['uid'] . '-' . $item['realname'];
                $row[] = $item['money'];
                $row[] = $item['money_pre'];
                $row[] = $item['money_after'];
                $row[] = $item['created_at'];
                $row[] = $item['status'] == 1 ? '支付成功' : '支付失败';
                $row[] = $item['describe'];
                $row[] = $item['remark'];

                return $row;
            });
        });
    }
}