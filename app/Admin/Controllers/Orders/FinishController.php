<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/2
 * Time: 15:09
 * Function: 已完成订单
 */

namespace App\Admin\Controllers\Orders;


use App\Admin\Extensions\Exporter\ExcelExporter;
use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Orders;
use App\Models\Region;
use App\Models\User;
use Carbon\Carbon;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Table;

class FinishController extends Controller
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('已完成订单');
            $content->body($this->grid());
        });
    }

    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('订单详情');

            $order = Orders::find($id);
            $content->row(function (Row $row) use ($order) {
                $regions = (new Region)->getAllIdNameArray();
                $row->column(12, function (Column $column) use ($order, $regions) {
                    $column->append(view('admin::orders.detail', compact('order', 'regions')));
                });
            });
            $content->row(function (Row $row) use ($order) {
                $row->column(12, function (Column $column) use ($order) {
                    $column->append(view('admin::orders.goods', ['orderGoods' => $order->orderGoods]));
                });
            });
            $content->row($this->form()->edit($id));
        });
    }

    protected function grid()
    {
        $grid = Admin::grid(Orders::class, function (Grid $grid) {
            $grid->sn('订单号');
            $grid->column('商品')->expand(function () {
                $headers = ['商品名称', '商品图片', '商品规格', '商品数量', '商品单价'];

                $rows = $this->orderGoods->map(function ($item, $key) {
                    $src = env('APP_URL') . '/uploads/' . $item->goodsAttr->goods->logo;

                    $data = [
                        $item->goodsAttr->goods->name,
                        "<img src='{$src}' width='50' height='50'>",
                        $item->goodsAttr->name,
                        $item->count,
                        $item->goodsAttr->price
                    ];

                    return $data;
                });

                return (new Box('订单详情', (new Table($headers, $rows->all()))))->style('primary')->solid();
            }, '详情');
            $grid->user()->realname('下单代理商');
            $grid->user_name('买家');
            $grid->user_phone('手机');
            $grid->total_price('订单总价');
            $grid->created_at('下单/支付时间');
            $grid->status('状态')->display(function ($status) {
                return '已完成';
            });
            $grid->post_way('配送方式')->display(function ($way) {
                return $way ==1 ? '快递配送' : '到店自提';
            });
            $grid->courier()->name('快递')->display(function ($name) {
                return $name ?? '无';
            });
            $grid->money('余额');

            $grid->disableCreation();
            $grid->exporter($this->exporter($grid));
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableDelete();
            });
            $grid->filter(function (Grid\Filter $filter) {
                $couriers = (new Courier())->getIdNameArray();
                $filter->equal('courier_id', '快递')->select($couriers);
                $filter->between('created_at', '下单/支付时间')->date();
                $filter->equal('sn', '订单号');
                $filter->like('user_name', '买家');
                $filter->equal('user_phone', '手机');
                $filter->equal('total_price', '总价');
            });
        });

        $grid->model()->where('status', 2);

        return $grid;
    }

    protected function form()
    {
        $form = Admin::form(Orders::class, function (Form $form) {
            $form->tab('订单操作', function (Form $form) {
                $form->select('status', '订单状态')->options([
                    0 => '未发货',
                    1 => '已发货',
                    2 => '已完成',
                    3 => '已取消'
                ]);
                $form->text('postid', '快递单号');
            })->tab('物流详情', function (Form $form) {
                $form->display('postid', '快递单号');
            });
        });

        $form->saving(function (Form $form) {
            $status = $form->status;
            $order = $form->model();

            if ($status == 2 && $order->status != 2) {
                $order->canceled_at = null;
                $order->completed_at = Carbon::now();
                $order->save();
            }

            if ($status == 3 && $order->status != 3) {
                $order->completed_at = null;
                $order->canceled_at = Carbon::now();
                $order->save();
            }
        });

        return $form;
    }

    private function exporter(Grid $grid)
    {
        $header = ['业务单号', '下单代理商', '收件人姓名', '收件人手机', '收件省', '收件市', '收件区/县', '收件人地址', '品名', '数量', '备注'];

        ///
        return new ExcelExporter($grid, function (ExcelExporter $excelExporter) use ($header) {
            $excelExporter->setFilename('已完成订单');
            $excelExporter->setHeader($header);

            ///
            $excelExporter->model()->where('status', 2)->orderBy('created_at', 'desc');

            ///
            $regions = (new Region())->getAllIdNameArray();
            $excelExporter->rowHandle(function (array $item) use ($regions) {
                $count = 0;
                $goods = [];
                $user = User::find($item['user_id']);
                $order = Orders::find($item['id']);
                $order->orderGoods->map(function ($orderGoods) use (&$count, &$goods) {
                    $count += $orderGoods->count;
                    $goods[] = $orderGoods->goodsAttr->goods->name . '*' . $orderGoods->count;
                });

                $row[] = $item['sn'];
                $row[] = $user->realname . "({$user->userLevel->name})";
                $row[] = $item['user_name'];
                $row[] = $item['user_phone'];
                $row[] = $regions[$item['user_province']];
                $row[] = $regions[$item['user_city']];
                $row[] = $regions[$item['user_area']];
                $row[] = $item['user_address'];
                $row[] = implode('、', $goods);
                $row[] = $count;
                $row[] = $item['remarks'];

                return $row;
            });
        });
    }
}