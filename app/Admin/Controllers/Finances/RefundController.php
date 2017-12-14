<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/10/31
 * Time: 16:00
 */

namespace App\Admin\Controllers\Finances;


use App\Admin\Extensions\Exporter\ExcelExporter;
use App\Http\Controllers\Controller;
use App\Models\RefundLog;
use App\Models\User;
use App\Services\CodeService;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class RefundController extends Controller
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('退款记录');
            $content->description('退款');
            $content->body($this->grid());
        });
    }

    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('后台退款');
            $content->description('退款');
            $content->body($this->form());
        });
    }

    protected function grid()
    {
        $grid = Admin::grid(RefundLog::class, function (Grid $grid) {
            $grid->sn('退款单号');
            $grid->uid('代理商编号');
            $grid->realname('姓名');
            $grid->money('退款金额');
            $grid->money_pre('退款前金额');
            $grid->money_after('退款后金额');
            $grid->created_at('退款时间');
            $grid->remark('备注');

            $grid->disableActions();
            $grid->disableRowSelector();
            $grid->exporter($this->exporter($grid));
        });

        $grid->filter(function (Grid\Filter $filter) {
            $filter->equal('sn', '退款单号');
            $filter->equal('uid', '代理商编号');
            $filter->like('realname', '姓名');
            $filter->between('created_at', '退款时间')->datetime();
        });

        ///
        $grid->model()->orderBy('created_at', 'desc');
        $grid->perPages([10, 20]);
        $grid->paginate(10);

        ///
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

        return Admin::form(RefundLog::class, function (Form $form) use ($users) {
            $form->number('money', '退款金额')->rules('required|numeric|min:0.1', [
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

            $form->tools(function (Form\Tools $tools) {
                $tools->disableListButton();
            });

            $form->saving(function (Form $form) {
                $user = User::find($form->uid);
                $form->realname = $user->realname;
                $form->money_pre = $user->money;
                $form->money_after = $form->money + $form->money_pre;
                $form->sn = (new CodeService)->makeRefundSn($form->uid);

                $user->money = $form->money_after;
                $user->save();
            });
        });
    }

    private function exporter(Grid $grid)
    {
        $header = ['退款单号', '姓名', '退款金额', '退款前余额', '退款后余额', '退款时间', '备注'];
        return new ExcelExporter($grid, function (ExcelExporter $excelExporter) use ($header) {
            $excelExporter->setFilename('退款记录');
            $excelExporter->setHeader($header);

            $excelExporter->rowHandle(function (array $item) {
                $row[] = $item['sn'];
                $row[] = $item['uid'] . '-' . $item['realname'];
                $row[] = $item['money'];
                $row[] = $item['money_pre'];
                $row[] = $item['money_after'];
                $row[] = $item['created_at'];
                $row[] = $item['remark'];

                return $row;
            });
        });
    }
}