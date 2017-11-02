<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/2
 * Time: 10:41
 * Function: 订单全览
 */

namespace App\Admin\Controllers\Orders;


use App\Http\Controllers\Controller;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class OverviewController extends Controller
{
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('订单');
            $content->description('全览');

            $content->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append(view('admin::orders.overview'));
                });
            });
        });
    }
}