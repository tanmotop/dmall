@extends('layouts.app')

@section('styles')
	<link href="/assets/css/order_list.css" rel="stylesheet" />
	<link href="/plugins/bootstrap/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
	<style type="text/css">
        /* 加载动画 */
        .sk-spinner-double-bounce.sk-spinner {
            width: 40px;
            height: 40px;
            position: relative;
            margin: 0 auto;
        }
        .sk-spinner-double-bounce .sk-double-bounce1,
        .sk-spinner-double-bounce .sk-double-bounce2 {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: #1ab394;
            opacity: 0.6;
            position: absolute;
            top: 0;
            left: 0;
            -webkit-animation: sk-doubleBounce 2s infinite ease-in-out;
            animation: sk-doubleBounce 2s infinite ease-in-out;
        }

        .sk-spinner-double-bounce .sk-double-bounce2 {
            -webkit-animation-delay: -1s;
            animation-delay: -1s;
        }
        @-webkit-keyframes sk-doubleBounce {
            0%,
            100% {
                -webkit-transform: scale(0);
                transform: scale(0);
            }
            50% {
                -webkit-transform: scale(1);
                transform: scale(1);
            }
        }

        @keyframes sk-doubleBounce {
            0%,
            100% {
                -webkit-transform: scale(0);
                transform: scale(0);
            }
            50% {
                -webkit-transform: scale(1);
                transform: scale(1);
            }
        }
        .up-right input{
            margin-left: 8px;
            margin-right: 8px;
            border: none;
            border-right: 1px solid #BFBFBF;
            border-left: 1px solid #BFBFBF;
            /*border-radius: 3px;*/
            line-height: 2em;
            outline: none;
            width: 30%;
            /* margin-bottom: 10px; */
            font-size: 0.8em;
            color: #000;
            text-align: center;
            /*padding: 0;*/
        }
    </style>
@endsection

@section('content')
<div class="zc-body">
        <div class="body-top">
            <div style="margin-top: 5px;margin-left: 10px;" class="top-left">
                <a href="{{ route('mall') }}">
                    <i style="font-size: 22px;margin-left: 5px;color: #b3b3b3;" class="fa fa-chevron-left"></i>
                </a>
            </div>
            <div class="tdcymd-top-center">
                <input type="text" class="search-value" value="{{ $keyword }}" placeholder="订单号 / 姓名 / 手机 / 价格">
            </div>
            <div style="margin-top: 5px;margin-right: 10px;" class="top-right">
                <a class="search-btn">
                    <i style="font-size: 22px;color: #b3b3b3;" class="fa fa-search"></i>
                </a>
            </div>
        </div>
        <div class="sp-list-top">
            <ul>
                <li><span onclick="location = '{{ route('orders', ['status' => 0]) }}'" @if($status==0) class="sp-list-active" @endif>未发货</span></li>
                <li><span onclick="location = '{{ route('orders', ['status' => 1]) }}'" @if($status==1) class="sp-list-active" @endif>已发货</span></li>
                <li><span onclick="location = '{{ route('orders', ['status' => 2]) }}'" @if($status==2) class="sp-list-active" @endif>已完成</span></li>
                <li><span onclick="location = '{{ route('orders', ['status' => 3]) }}'" @if($status==3) class="sp-list-active" @endif>已取消</span></li>
            </ul>
        </div>

        <div id="order-list">
            @forelse ($orderList as $order)
                <div class="sp-list-bottom">
                    <div class="up">
                        <h4>订单号：{{ $order->sn }}</h4>
                        <div class="up-right-all"><span style="color: orange;">{{ $order->status_text }}</span></div>
                    </div>
                    <div class="down">
                        <div class="down-left">
                            @foreach($order->logos as $logo)
                                <img src="/uploads/{{ $logo }}">
                            @endforeach
                        </div>
                        <div class="down-right">
                            <ul>
                                <li class="sp-all"><p>订单金额：<em>￥{{ $order->total_price }}</em></p></li>
                                <li class="sp-all"><p>运费金额：<em>￥{{ $order->freight }}</em></p></li>
                                <li class="sp-all"><p>PV：<i>{{ $order->total_pv }}</i></p></li>
                                <li class="sp-all"><p>备注：<i>{{ $order->remarks }}</i></p></li>
                            </ul>
                        </div>
                    </div>
                    <div class="mysale-bottom">
                        <p>姓名：{{ $order->user_name }}</p>
                        <p>手机：{{ $order->user_phone }}</p>
                        <p>收货地区：{{ $order->user_province }} {{ $order->user_city }} {{ $order->user_area }}</p>
                        <p>详细地址：{{ $order->user_address }}</p>
                        <p>订单时间：{{ $order->created_at }}</p>
                    </div>
                    <div class="mysale-btn">
                        余额：{{ $order->money }}
                        @if ($order->status == 0)
                            <button onclick="cancel_order('{{ $order->id }}')" type="button" class="btn left-btn">取消订单</button>
                        @elseif ($order->status == 1)
                            <button onclick="order_express('{{ $order->id }}')" type="button" class="btn left-btn">查看物流</button>
                            <button onclick="confirm_receipt('{{ $order->id }}')" type="button" class="btn">确认收货</button>
                        @endif
                        @if ($order->status != 1)
                            <button onclick="order_detail('{{ $order->id }}')" type="button" class="btn">查看订单</button>
                        @endif
                    </div>
                </div>
            @empty
                <div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>暂无数据</span></div>
            @endforelse
        </div>
        @if ($orderList->currentPage() != $orderList->lastPage())
            <div align="center" onclick="getMoreGoods()" class="sp-list-bottom tips"><span>上拉/点击获取更多邀请码</span></div>
        @endif
    </div>
    <br/><br/>
    <div id="order_dialog"></div>
@endsection

@section('scripts')
<script type="text/javascript">
        /* 获取更多订单 */
        var flags_lock = true;
        $(document).ready(function() {
            $(window).scroll(function() {
                if (!flags_lock) return;
                if ($(document).scrollTop() >= $(document).height() - $(window).height()) {
                    flags_lock = false;
                    getMoreOrders();
                    setTimeout("unlock()",4000);
                }
            });
        });
        var currentPage = parseInt('{{ $orderList->currentPage() }}')
        var lastPage = parseInt('{{ $orderList->lastPage() }}')
        var status = parseInt('{{ $status }}')
        function unlock() {
            flags_lock = true;
        }
        function getMoreOrders()
        {
            var get_url = "{{ route('orders', ['status' => $status]) }}";
            if (currentPage == lastPage) {
                return false;
            }
            $('.tips span').html('正在获取 <i class="fa fa-spinner fa-spin"></i>')
            $.get(get_url, {
                page: currentPage + 1,
                dataType: 'json'
            }, function(json) {
                currentPage = json.current_page;
                var data = json.data;
                var html = '';
                for (var i in data) {
                    var item = data[i];
                    var imgs = '';
                    for (var j in item.logos) {
                        imgs += '<img src="/uploads/'+item.logos[j]+'">';
                    }
                    if(!item.remarks){
                        item.remarks='';
                    }
                    html += '<div class="sp-list-bottom">'
                        + '    <div class="up">'
                        + '        <h4>订单号：'+item.sn+'</h4>'
                        + '        <div class="up-right-all"><span style="color: orange;">'+item.status_text+'</span></div>'
                        + '    </div>'
                        + '    <div class="down">'
                        + '        <div class="down-left">'+imgs+'</div>'
                        + '        <div class="down-right">'
                        + '            <ul>'
                        + '                <li class="sp-all"><p>订单金额：<em>￥'+item.total_price+'</em></p></li>'
                        + '                <li class="sp-all"><p>运费金额：<em>￥'+item.freight+'</em></p></li>'
                        + '                <li class="sp-all"><p>PV：<i>'+item.total_pv+'</i></p></li>'
                        + '                <li class="sp-all"><p>备注：<i>'+item.remarks+'</i></p></li>'
                        + '            </ul>'
                        + '        </div>'
                        + '    </div>'
                        + '    <div class="mysale-bottom">'
                        + '        <p>姓名：'+item.user_name+'</p>'
                        + '        <p>手机：'+item.user_phone+'</p>'
                        + '        <p>收货地区：'+item.user_province+' '+item.user_city+' '+item.user_area+'</p>'
                        + '        <p>详细地址：'+item.user_address+'</p>'
                        + '        <p>订单时间：'+item.id+'</p>'
                        + '    </div>'
                        + '    <div class="mysale-btn">'
                        + '        <button onclick="cancel_order('+parseInt(item.id)+')" type="button" class="btn left-btn">取消订单</button>'
                        + '        <button onclick="order_detail('+parseInt(item.id)+')" type="button" class="btn">查看订单</button>'
                        + '    </div>'
                        + '</div>'
                };
                setTimeout(function() {
                    $('#order-list').append(html);
                    if (json.current_page == json.last_page) {
                        $('.tips').remove();
                        $('#order-list').append('<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有更多了</span></div>');
                    } else {
                        $('.tips span').html('上拉/点击获取更多邀请码');
                    }
                },500)

            });
        }

        /* 查看订单详细信息 */
        function order_detail(id) {
            window.location.href = '{{ route('orders_detail') }}?id='+id;
        }

        /* 查看订单物流信息 */
        function order_express(id) {
            window.location.href = '{{ route('orders_detail') }}?id='+id+'&tab=express';
        }

        /* 取消订单 */
        function cancel_order(id) {
            var html = '<div class="modal fade in" tabindex="-1" role="dialog" id="MyShare" aria-hidden="false" style="display: block;">' +
                    '<div class="modal-dialog">' +
                    '<div class="out-zx">' +
                    '<i style="color: red" class="fa fa-smile-o fa-5x"></i>' +
                    '<p>您确定要取消订单吗？</p>' +
                    '<div class="change-btn">' +
                    '<div id="remove"><button style="float: none;" type="button" class="left-btn btn-close">再想想</button>' +
                    '<button style="float: none;" type="button" class="right-btn btn-login">取消订单</button></div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="modal-back fade in"></div>';
            $( '#order_dialog' ).html( html );

            /* 关闭模态框 */
            $( '.btn-close' ).click( function () {
                $( '#order_dialog' ).html( '' );
            } );

            /* 取消订单 */
            $( '.btn-login' ).click( function () {
                $('#order_dialog').html('');
                $.post('{{ route('orders_cancel') }}', {
                    id: id,
                    _token: '{{ csrf_token() }}'
                }, function(json) {
                    if (json.code == 10000) {
                        alert('订单取消成功');
                    }
                    else if(json.code == 10001) {
                        alert('订单已发货');
                    }
                    else {
                        alert('订单取消失败');
                    }
                    location = location.href;
                })
            } );
        }

        /* 确认收货 */
        function confirm_receipt(id)
        {
            var html = '<div class="modal fade in" tabindex="-1" role="dialog" id="MyShare" aria-hidden="false" style="display: block;">' +
                    '<div class="modal-dialog">' +
                    '<div class="out-zx">' +
                    '<i style="color: grey" class="fa fa-smile-o fa-5x"></i><br><br>' +
                    '<p>请您务必在收到包裹之后进行确认收货！</p>' +
                    '<div class="change-btn">' +
                    '<button style="float: none;" type="button" class="left-btn btn-close">再想想</button>' +
                    '<button style="float: none;" type="button" class="right-btn btn-login">确认收货</button>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="modal-back fade in"></div>';
            $( '#order_dialog' ).html( html );

            /* 关闭模态框 */
            $( '.btn-close' ).click( function () {
                $( '#order_dialog' ).html( '' );
            } );

            /* 确认收货 */
            $( '.btn-login' ).click( function () {
                $('#order_dialog').html('');
                $.post('{{ route('orders_confirm') }}', {
                    id: id,
                    _token: '{{ csrf_token() }}'
                }, function(json) {
                    if (json.code == 10000) {
                        alert('确认收货成功');
                    } else {
                        alert('确认收货失败');
                    }
                    location = location.href;
                })
            });
        }
        $('.search-btn').on('click', function() {
            var val = $('.search-value').val()
            var url = window.location.href
            if (url.indexOf('?') != -1) {
                window.location = url + '&keyword=' + val
            } else {
                window.location = url + '?keyword=' + val
            }
        })
    </script>
@endsection