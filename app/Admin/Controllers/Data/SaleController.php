<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/6
 * Time: 10:10
 * Function: 销售统计
 */

namespace App\Admin\Controllers\Data;


use App\Admin\Extensions\Exporter\ExcelExporter;
use App\Models\GoodsAttr;
use App\Models\OrderGoods;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class SaleController
{
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('销售统计');
            $content->body($this->grid());
        });
    }

    protected function grid()
    {
        $grid = Admin::grid(GoodsAttr::class, function (Grid $grid) {
            $totalCount = OrderGoods::all()->sum('count');
            $totalPrice = OrderGoods::all()->sum('price');
            $grid->goods()->name('商品名称');
            $grid->goods()->logo('商品LOGO')->image(env('APP_URL') . '/uploads/', 60, 60);
            $grid->order_goods('交易量')->display(function ($orderGoods) {
                return array_sum(array_column($orderGoods, 'count'));
            });
            $grid->column('交易额')->display(function () {
                return array_sum(array_column($this->order_goods, 'price'));
            });
            $grid->column('交易量比例')->display(function () use ($totalCount) {
                $percent = $totalCount ? array_sum(array_column($this->order_goods, 'count')) / $totalCount * 100 : 0;
                return round($percent, 2) . '%';
            });
            $grid->column('交易额比例')->display(function () use ($totalPrice) {
                $percent = $totalPrice ? array_sum(array_column($this->order_goods, 'price')) / $totalPrice * 100 : 0;
                return round($percent, 2) . '%';
            });

            $grid->disableCreation();
            $grid->disableActions();
            $grid->disableRowSelector();
            $grid->exporter($this->exporter($grid));
        });

        return $grid;
    }

    private function exporter(Grid $grid)
    {
        $header = ['商品名称', '交易量', '交易额', '交易量比例', '交易额比例'];

        return new ExcelExporter($grid, function (ExcelExporter $excelExporter) use ($header) {
            $excelExporter->setFilename('销售统计');
            $excelExporter->setHeader($header);

            $totalCount = OrderGoods::all()->sum('count');
            $totalPrice = OrderGoods::all()->sum('price');
            $excelExporter->rowHandle(function (array $item) use ($totalCount, $totalPrice) {
                $orderGoods = OrderGoods::where('attr_id', $item['id'])->get()->toArray();
                $count = array_sum(array_column($orderGoods, 'count'));
                $price = array_sum(array_column($orderGoods, 'price'));

                $row[] = $item['name'];
                $row[] = array_sum(array_column($orderGoods, 'count'));
                $row[] = array_sum(array_column($orderGoods, 'price'));
                $row[] = $totalCount ? round($count / $totalCount * 100, 2) . '%' : '0%';
                $row[] = $totalPrice ? round($price / $totalPrice * 100, 2) . '%' : '0%';

                return $row;
            });
        });
    }
}