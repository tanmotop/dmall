<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/3
 * Time: 11:50
 * Function:
 */

namespace App\Admin\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    protected $ordersModel;

    public function __construct(Orders $orders)
    {
        $this->ordersModel = $orders;
    }

    public function deliver(Request $request)
    {
        $orderId = $request->id;
        $postId = $request->postid;

        $res = $this->ordersModel->deliver($orderId, $postId);
        $data = $res ? ['status' => 1, 'message' => '操作成功'] : ['status' => 0, 'message' => '操作失败'];

        return response()->json($data);
    }
}