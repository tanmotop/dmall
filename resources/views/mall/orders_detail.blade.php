@extends('layouts.app')

@section('styles')
<link href="/assets/css/order_detail.css" rel="stylesheet" />
<link href="/plugins/bootstrap/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
<style type="text/css">
    .sp-list-bottom ul li { line-height: 2 !important; }
</style>
@endsection

@section('content')
<div class="zc-body">
    <div class="body-top">
        <div style="margin-top: 5px;margin-left: 10px;" class="top-left">
            <a href="{{ url()->previous() }}">
                <i style="font-size: 22px;margin-left: 5px;color: #b3b3b3;" class="fa fa-chevron-left"></i>
            </a>
        </div>
        <div style="margin-top: 8px;margin-right: 10px;" class="top-right">
            <a href="{{ route('home') }}">
                <i style="font-size: 26px;margin-left: -5px;color: #b3b3b3;" class="fa fa-home"></i>
            </a>
        </div>
    </div>

    <div class="sp-list-top">
        <ul class="order-menu">
            <li data-part="detail"><span class="sp-list-active">订单信息</span></li>
            <li data-part="goods"><span>商品详情</span></li>
            <li data-part="express"><span>物流详情</span></li>
        </ul>
    </div>

    <div id="detail" class="order-part">
        <div class="zc-bottom">
            <ul>
                <li>状态：<input readonly type="text" value="{{ $order->status_text }}"></li>
                <li>订单编号：<input readonly type="text" value="{{ $order->sn }}"></li>
                <li>订单总价：<input readonly type="text" value="￥{{ $order->total_price }}"></li>
                <li>订单运费：<input readonly type="text" value="￥{{ $order->freight }}"></li>
                <li>PV值：<input readonly type="text" value="{{ $order->total_pv }}"></li>
                <li>收货人：<input readonly type="text" value="{{ $order->user_name }}"></li>
                <li>手机：<input readonly type="text" value="{{ $order->user_phone }}"></li>
                <li>收货地区：<input readonly type="text" value="{{ $order->user_province }} {{ $order->user_city }} {{ $order->user_area }}"></li>
                <li>详细地址：<input readonly type="text" value="{{ $order->user_address }}"></li>
                <li>下单时间：<input readonly type="text" value="{{ $order->created_at }}"></li>
                <li>支付时间：<input readonly type="text" value="{{ $order->created_at }}"></li>
            </ul>
        </div>
    </div>

    <div id="goods" class="order-part">
        @foreach($order->orderGoods as $ogItem)
            <div class="sp-list-bottom">
                <div class="up">
                    <h4>{{ $ogItem->goodsAttr->goods->name }}</h4>
                </div>
                <div class="down">
                    <div class="down-left">
                        <img src="/uploads/{{ $ogItem->goodsAttr->goods->logo }}"/>
                    </div>
                    <div class="down-right">
                        <ul>
                            <li><div style="float: right;">x{{ $ogItem->count }}</div><p>购买价：<em>￥{{ $ogItem->price }}</em></p></li>
                            <li><p>规格：<span class="active-kw">{{ $ogItem->goodsAttr->name }}</span></p></li>
                            <li><p>PV值：<i>{{ $ogItem->pv }}</i></p></li>
                            <li><p>产品编号：<i>{{ $ogItem->goodsAttr->goods->sn }}</i></p></li>
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div id="express" class="order-part">
        @if ($order->postid)
            <div class="zc-bottom">
                <ul>
                    <li onclick="get_post( this )" style="text-align: center;height: 36px;padding-top: 8px;" data-id="{{$order->id}}" class="get_post">点击获取</li>
                    <li>快递单号：<input readonly type="text" value="{{ $order->postid }}"></li>
                    <li style="border-bottom: none;">物流跟踪：</li>
                    <li>
                        <div style="margin-left: 7%;margin-right: 7%;" id="post">
                            <p><i class="fa fa-refresh"></i> 没有物流信息</p>
                        </div>
                    </li>
                </ul>
            </div>
        @else
            <div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>暂无物流信息</span></div>
        @endif
    </div>
</div>
<br/><br/>
@endsection

@section('scripts')
<script src="/plugins/bootstrap/js/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
    $('.order-menu li').on('click', function() {
        $('.order-menu li span').removeClass('sp-list-active');
        $(this).find('span').addClass('sp-list-active');
        var part = $(this).attr('data-part');
        $('.order-part').hide();
        $('#' + part).show();
    })
    /* 手动获取 */

    function get_post( li ) {

        var id = $( li ).attr( 'data-id' );

        var info = $( '.get_post' );

        info.html( '正在获取 <i class="fa fa-spinner fa-spin"></i>' );

        $.ajax({

            url: "{{ route('orders_query') }}",

            type: 'POST',

            data: {
                'id': id,
                '_token':'{{csrf_token()}}'
            },

            dataType: 'json',

            success: function ( json ){

                if( json != '' ){

                    if( json.code == 'OK' ){

                        var html = '';

                        var div = $( '#post' );

                        var flash = '';

                        var fa = '';

                        var result = json.list;

                        if( result ){

                            html += '<p>' +

                                '<i class="fa fa-truck"></i> ' +

                                '<span>' + json.name + '</span>  ' +

                                '<span>' + json.phone + '</span>' +

                                '</p>';

                            $.each( json.list, function ( k, v ){

                                if( k == 0 ){

                                    if( json.state == 3 ){

                                        flash = '';

                                        fa = 'fa-check';

                                    }else{

                                        flash = 'fa-spin';

                                        fa = 'fa-refresh';

                                    }

                                }else{

                                    flash = '';

                                    fa = 'fa-refresh';

                                }

                                html += '<p>' +

                                    '<i class="fa ' + fa + ' ' + flash + '"></i> ' +

                                    '<span style="color: #B58F62">' + v.time + '</span> ' +

                                    '<span>' + v.content + '</span>' +

                                    '</p>'

                            } );

                            div.html( html );

                            info.html( '获取成功！' );

                        }

                    }

                }

            }

        });

    }
</script>
@endsection