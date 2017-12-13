<div class="box box-default">
    <!-- /.box-header -->
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <tr>
                    <th width="120px">状态</th>
                    <td></td>
                    <th width="120px"></th>
                    <td></td>
                </tr>
                <tr>
                    <th width="120px">订单编号</th>
                    <td>{{ $order->sn }}</td>
                    <th width="120px">收货地址</th>
                    <td>{{ $regions[$order->user_province] }} {{ $regions[$order->user_city] }} {{ $regions[$order->user_area] }}</td>
                </tr>
                <tr>
                    <th width="120px">订单总价</th>
                    <td>{{ $order->total_price }}</td>
                    <th width="120px">详细地址</th>
                    <td>{{ $order->user_address }}</td>
                </tr>
                <tr>
                    <th width="120px">客户备注</th>
                    <td>{{ $order->remarks }}</td>
                    <th width="120px">下单时间</th>
                    <td>{{ $order->created_at }}</td>
                </tr>
                <tr>
                    <th width="120px">收货人</th>
                    <td>{{ $order->user_name }}</td>
                    <th width="120px">支付时间</th>
                    <td>{{ $order->created_at }}</td>
                </tr>
                <tr>
                    <th width="120px">收货人手机</th>
                    <td>{{ $order->user_phone }}</td>
                    <th width="120px"></th>
                    <td></td>
                </tr>
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.box-body -->
</div>