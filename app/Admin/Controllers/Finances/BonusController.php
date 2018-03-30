<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/20
 * Time: 14:46
 * Function:
 */

namespace App\Admin\Controllers\Finances;


use App\Admin\Extensions\Exporter\ExcelExporter;
use App\Models\Orders;
use App\Models\Pv;
use App\Models\User;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class BonusController
{
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('奖金查询');
            $content->body($this->grid());
        });
    }

    protected function grid()
    {
        $grid = Admin::grid(User::class, function (Grid $grid) {
            $pvModel = new Pv();
            $pvConf = $pvModel->getPvConf();
            $grid->rows(function (&$item, $key) use ($pvModel, $pvConf) {
                $user = User::find($item->id);
                $item->column('actual_sales', $user->bonus()->sum('actual_sales'));
                $item->column('level_money', $user->bonus()->sum('level_money'));
                $item->column('invite_money', $user->bonus()->sum('invite_money'));
                $item->column('retail_money', $user->bonus()->sum('retail_money'));
                $item->column('teams_pv', $user->bonus()->sum('teams_pv'));
                $item->column('personal_pv', $user->bonus()->sum('personal_pv'));
                $item->column('personal_pv_bonus', $pvModel->getBonus($pvConf, $item->personal_pv));
                $item->column('teams_pv_bonus', $pvModel->getBonus($pvConf, $item->teams_pv));
                $item->column('total_bonus', $item->personal_pv_bonus + $item->invite_money + $item->level_money);
            });
            $grid->id('代理商编号');
            $grid->realname('姓名');
            $grid->avatar('头像')->image(config('filesystems.disks.admin.url') . '/', 60, 60);
            $grid->column('上级代理商')->display(function () {
                if ($this->parent_id > 0) {
                    $parent = User::where('id', $this->parent_id)->first();
                }

                return $this->parent_id > 0 ? $parent->realname : '不二大山';
            });
            $grid->actual_sales('实际销售额');
            $grid->level_money('级别差价');
            $grid->invite_money('邀代奖金');
            $grid->retail_money('个人零售利润');
            $grid->personal_pv('个人业绩合计(PV值)');
            $grid->personal_pv_bonus('个人业绩抽成');
            $grid->teams_pv('团队业绩合计(PV值)');
            $grid->teams_pv_bonus('团队业绩抽成');
            $grid->total_bonus('总奖金');

            $grid->exporter($this->exporter($grid));

            $grid->filter(function (Grid\Filter $filter) {
                $filter->disableIdFilter();
                $filter->equal('id', '代理商编号');
                $filter->like('realname', '真实姓名');
                $filter->between('bonus.created_at', '时间')->date();
            });
        });

        $grid->disableRowSelector();
        $grid->disableCreation();
        $grid->disableActions();

        return $grid;
    }

    private function exporter(Grid $grid)
    {
        $header = ['代理商编号', '真实姓名', '团队业绩', '个人业绩', '级别差收入', '邀请代理商收入', '奖金收入', '个人零售利润', '团队业绩抽成'];

        return new ExcelExporter($grid, function (ExcelExporter $excelExporter) use ($header) {
            $excelExporter->setFilename('奖金查询');
            $excelExporter->setHeader($header);

            $excelExporter->rowHandle(function (array $item) {
                $user = User::find($item['id']);
                $row[] = $item['id'];
                $row[] = $item['realname'];
                $row[] = $user->bonus->sum('teams_pv');
                $row[] = $user->bonus->sum('personal_pv');
                $row[] = $user->bonus->sum('level_money');
                $row[] = $user->bonus->sum('invite_money');
                $row[] = 0;
                $row[] = $user->bonus->sum('retail_money');
                $row[] = 0;

                return $row;
            });
        });
    }
}