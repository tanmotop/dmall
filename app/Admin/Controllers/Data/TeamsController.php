<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/20
 * Time: 15:25
 * Function:
 */

namespace App\Admin\Controllers\Data;


use App\Admin\Extensions\Exporter\ExcelExporter;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLevel;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Database\Eloquent\Builder;

class TeamsController extends Controller
{
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('团队消费');
            $content->description('排行');
            $content->body($this->grid());
        });
    }

    protected function grid()
    {
        $grid = Admin::grid(User::class, function (Grid $grid) {
            $levels = (new UserLevel())->getLevelNameArray();
            $grid->id('代理商编号');
            $grid->realname('姓名');
            $grid->phone('手机');
            $grid->level('代理商等级')->display(function ($level) use ($levels) {
                return $levels[$level];
            });
            $grid->column('订单量')->display(function () {
                return 0;
            });
            $grid->column('消费金额')->display(function () {
                return 0;
            });
            $grid->column('总PV值')->display(function () {
                return 0;
            });

            $grid->exporter($this->exporter($grid));
            $grid->filter(function (Grid\Filter $filter) {
                $userLevel = (new UserLevel())->getLevelNameArray();
                $filter->disableIdFilter();
                $filter->equal('realname', '姓名');
                $filter->equal('phone', '手机');

                $filter->where(function (Builder $query) {
                    $query->whereHas('orders', function (Builder $query) {
                        $query->where('created_at', '>=', $this->input);
                    });
                }, '开始时间')->date();

                $filter->where(function (Builder $query) {
                    $query->whereHas('orders', function (Builder $query) {
                        $query->where('created_at', '<=', $this->input);
                    });
                }, '结束时间')->date();


                $filter->equal('level', '代理商等级')->select($userLevel);
                $filter->where(function (Builder $query) {
                    if ($this->input != 0) {
                        $query->where('parent_id', '<>', 0);
                    }
                    else {
                        $query->where('parent_id', 0);
                    }
                }, '代理商类型')->select([
                    0 => '顶级代理商',
                    1 => '非顶级代理商'
                ]);
            });
        });

        $grid->disableRowSelector();
        $grid->disableActions();
        $grid->disableCreation();

        return $grid;
    }

    private function exporter(Grid $grid)
    {
        $header = ['代理商编号', '姓名', '手机', '代理商等级', '订单量', '消费金额', '总PV值'];

        return new ExcelExporter($grid, function (ExcelExporter $excelExporter) use ($header) {
            $excelExporter->setFilename('团队消费排行');
            $excelExporter->setHeader($header);

            $userLevel = (new UserLevel())->getLevelNameArray();
            $excelExporter->rowHandle(function (array $item) use ($userLevel) {
                $row[] = $item['id'];
                $row[] = $item['realname'];
                $row[] = $item['phone'];
                $row[] = $userLevel[$item['level']];
                $row[] = 0;
                $row[] = 0;
                $row[] = 0;

                return $row;
            });
        });
    }
}