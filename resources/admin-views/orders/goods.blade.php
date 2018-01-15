<div class="box box-default">
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <tr>
                    <th>商品名称</th>
                    <th>商品图片</th>
                    <th>商品规格</th>
                    <th>商品数量</th>
                    <th>商品单价</th>
                    <th>备注</th>
                </tr>
                @foreach($orderGoods as $item)
                    <tr>
                        <td>{{ $item->goodsAttr->goods->name }}</td>
                        <td>
                            <img src='{{ env('APP_URL') . '/uploads/' . $item->goodsAttr->goods->logo }}' width='50' height='50'>
                        </td>
                        <td>{{ $item->goodsAttr->name }}</td>
                        <td>{{ $item->count }}</td>
                        <td>{{ $item->price }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>