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
use App\Models\User;
use App\Models\UserBonus;
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
            $grid->id('代理商编号');
            $grid->realname('姓名');
            $grid->avatar('头像')->image(config('filesystems.disks.admin.url') . '/', 60, 60);
            $grid->column('上级代理商')->display(function () {
                if ($this->parent_id > 0) {
                    $parent = User::where('id', $this->parent_id)->first();
                }

                return $this->parent_id > 0 ? $parent->realname : '不二大山';
            });
            $grid->column('实际销售额')->display(function () {
                $totalPrice = Orders::where([
                    ['user_id', $this->id],
                    ['status', 2]
                ])->sum('total_price');

                return $totalPrice;
            });
            $grid->column('级别差价')->display(function () {
                return $this->bonus()->sum('level_money');
            });
            $grid->column('邀代奖金')->display(function () {
                return $this->bonus()->sum('invite_money');
            });
            $grid->column('个人零售利润')->display(function () {
                return $this->bonus()->sum('retail_money');
            });
            $grid->column('个人业绩合计(PV值)')->display(function () {
                return $this->bonus()->sum('personal_pv');
            });
            $grid->column('团队业绩合计(PV值)')->display(function () {
                return $this->bonus()->sum('teams_pv');
            });
            $grid->column('团队业绩抽成')->display(function () {
                return 0;
            });

            $grid->exporter($this->exporter($grid));

            $grid->filter(function (Grid\Filter $filter) {
                $filter->disableIdFilter();
                $filter->equal('id', '代理商编号');
                $filter->like('realname', '真实姓名');
                $filter->where(function ($query) {
                    $query->whereHas('bonus', function ($query) {
                        $query->where('created_at', '>=', $this->input);
                    });
                }, '开始时间')->date();
                $filter->where(function ($query) {
                    $query->whereHas('bonus', function ($query) {
                        $query->where('created_at', '<=', $this->input);
                    });
                }, '结束时间')->date();
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