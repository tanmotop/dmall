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
                <input type="text" placeholder="订单号 / 姓名 / 手机 / 价格">
            </div>
            <div style="margin-top: 5px;margin-right: 10px;" class="top-right">
                <a onclick="goods_search( this )">
                    <i style="font-size: 22px;color: #b3b3b3;" class="fa fa-search"></i>
                </a>
            </div>
        </div>
        <div class="sp-list-top">
            <ul>
                <li><span onclick="select_cat( this, 'Y', 1 )" class="sp-list-active">未发货</span></li>
                <li><span onclick="select_cat( this, 'Y', 2 )">已发货</span></li>
                <li><span onclick="select_cat( this, 'Y', 3 )">已完成</span></li>
                <li><span onclick="select_cat( this, 'N', 0 )">已取消</span></li>
            </ul>
        </div>

        <div id="goods-list">
            <div class="sk-spinner sk-spinner-double-bounce">
                <div class="sk-double-bounce1"></div>
                <div class="sk-double-bounce2"></div>
            </div>
        </div>
    </div>
    <br/><br/>
    <div id="order_dialog"></div>
@endsection

@section('scripts')
<script type="text/javascript">
        /* 商品图片路径 */
        var viewPath = "/";
        /* 定义总的加载的订单个数 */
        totalNumber = 0;
        /* 定义每次加载的订单个数 */
        singleNumber = 5; // （只能修改此处）
        /* 定义当前总共加载多少个 */
        eachNumber = 0;
        /* 缓存数据 */
        var data = [];
        /* 缓存网页数据 */
        var cache = [];
        /* 是否还能下拉 */
        status = 'true';
        /* 订单状态 */
        o_status = 'Y';
        /* 快递状态 */
        p_status = 1;
        /* 是否搜索 */
        o_search = 'false';

        /* 默认加载未发货订单 */
        $( function () {
            $.ajax( {
                url: "{:U( 'Order/getLst' )}",
                type: 'POST',
                data: { 'status': 'Y', 'type': 1 },
                dataType: 'json',
                success: function ( json ) {
                    if( json.code == 'success' ){
                        var html = '';
                        if( json.data != '' ){
                            data.push( json.data );
                            $.each( json.data, function ( k, v ){
                                totalNumber++;
                                if( k < singleNumber ){
                                    html += '<div class="sp-list-bottom">' +
                                            '<div class="up">' +
                                            '<h4>订单号：' + v.order_sn +'</h4>' +
                                            '<div class="up-right-all">' +
                                            '<span style="color: orange;">未发货</span>' +
                                            '</div>' +
                                            '</div>' +
                                            '<div class="down">' +
                                            '<div class="down-left">';
                                    if( v.logo != '' ){
                                        var logoNumber = 0;
                                        $.each( v.logo, function ( k1, v1 ){
                                            logoNumber++;
                                            html += '<img src="' + v1 + '">';
                                        } );
                                        if( logoNumber < 3 ){
                                            html += '<img><img>';
                                        }
                                    }

                                    html += '</div>' +
                                            '<div class="down-right">' +
                                            '<ul>' +
                                            '<li class="sp-all">' +
                                            '<p>订单金额：<em>￥' + v.total_price + '</em></p>' +
                                            '</li>' +
                                            '<li class="sp-all">' +
                                            '<p>运费金额：<em>￥' + v.insure + '</em></p>' +
                                            '</li>' +
                                            '<li class="sp-all">' +
                                            '<p>PV：<i>' + v.total_pv + '</i></p>' +
                                            '</li>' +
                                            '<li class="sp-all">' +
                                            '<p>备注：<i>' + v.remarks + '</i></p>' +
                                            '</li>' +
                                            '</ul>' +
                                            '</div>' +
                                            '</div>' +
                                            '<div class="mysale-bottom">' +
                                            '<p>姓名：' + v.shr_name + '</p>' +
                                            '<p>手机：' + v.shr_tel + '</p>' +
                                            '<p>收货地区：' + v.area + '</p>' +
                                            '<p>详细地址：' + v.shr_address + '</p>' +
                                            '<p>订单时间：' + v.add_time + '</p>' +
                                            '</div>' +
                                            '<div class="mysale-btn">' +
                                            '<button onclick="cancel_order( ' + v.id + ' )" type="button" class="btn left-btn">取消订单</button>' +
                                            '<button onclick="order_detail( ' + v.id + ' )" type="button" class="btn">查看订单</button>' +
                                            '</div>' +
                                            '</div>';
                                    eachNumber++;
                                }
                            } );
                            if( totalNumber > eachNumber ){
                                html += '<div align="center" onclick="getNewGoods();" class="sp-list-bottom goods-last-notice"><span>上拉/点击获取更多订单</span></div>';
                            }else{
                                html += '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有更多订单了</span></div>';
                                status = 'false';
                            }
                        }else{
                            html = '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有相应的订单</span></div>';
                        }
                        $( '#goods-list' ).html( html );
                    }
                }
            } );
        } );

        /* 选择状态加载相应订单 */
        function select_cat( span, order_status, post_status ) {
            /* 定义总的加载的订单个数 */
            totalNumber = 0;
            /* 定义当前总共加载多少个 */
            eachNumber = 0;
            /* 缓存数据 */
            data = [];
            /* 缓存网页数据 */
            cache = [];
            /* 是否还能下拉 */
            status = 'true';
            /* 订单状态 */
            o_status = order_status;
            /* 快递状态 */
            p_status = post_status;
            /* 是否搜索 */
            o_search = 'false';

            $( '.sp-list-top' ).find( 'li span' ).removeClass( 'sp-list-active' );
            $( span ).addClass( 'sp-list-active' );
            var list = $( '#goods-list' );
            var spinner = '<div class="sk-spinner sk-spinner-double-bounce">' +
                    '<div class="sk-double-bounce1"></div>' +
                    '<div class="sk-double-bounce2"></div></div>';
            list.html( spinner );
            $.ajax( {
                url: "{:U( 'Order/getLst' )}",
                type: 'POST',
                data: { 'status': order_status, 'type': post_status },
                dataType: 'json',
                success: function ( json ) {
                    if( json.code == 'success' ){
                        var html = '';
                        if( json.data != '' ){
                            data.push( json.data );
                            $.each( json.data, function ( k, v ){
                                totalNumber++;
                                if( k < singleNumber ){
                                    var span = '';
                                    var btn1 = '';
                                    var btn2 = '';
                                    if( o_status == 'Y' ){
                                        switch( p_status ){
                                            case 1:
                                                span = '<span style="color: orange;">未发货</span>';
                                                btn1 = '<button onclick="cancel_order( ' + v.id + ' )" type="button" class="btn left-btn">取消订单</button>';
                                                btn2 = '<button onclick="order_detail( ' + v.id + ' )" type="button" class="btn">查看订单</button>';
                                                break;
                                            case 2:
                                                span = '<span>已发货</span>';
                                                btn1 = '<button onclick="order_express( ' + v.id + ' )" type="button" class="btn left-btn">查看物流</button>';
                                                btn2 = '<button onclick="confirm_receipt( ' + v.id + ' )" type="button" class="btn">确认收货</button>';
                                                break;
                                            case 3:
                                                span = '<span style="color: green;">已完成</span>';
                                                btn1 = '<button onclick="order_express( ' + v.id + ' )" type="button" class="btn left-btn">查看物流</button>';
                                                btn2 = '<button onclick="order_detail( ' + v.id + ' )" type="button" class="btn">查看订单</button>';
                                                break;
                                        }
                                    }else{
                                        span = '<span style="color: red;">已取消</span>';
                                        btn2 = '<button onclick="order_detail( ' + v.id + ' )" type="button" class="btn">查看订单</button>';
                                    }
                                    html += '<div class="sp-list-bottom">' +
                                            '<div class="up">' +
                                            '<h4>订单号：' + v.order_sn +'</h4>' +
                                            '<div class="up-right-all">' +
                                            span +
                                            '</div>' +
                                            '</div>' +
                                            '<div class="down">' +
                                            '<div class="down-left">';
                                    if( v.logo != '' ){
                                        var logoNumber = 0;
                                        $.each( v.logo, function ( k1, v1 ){
                                            logoNumber++;
                                            html += '<img src="' + v1 + '">';
                                        } );
                                        if( logoNumber < 3 ){
                                            html += '<img><img>';
                                        }
                                    }

                                    html += '</div>' +
                                            '<div class="down-right">' +
                                            '<ul>' +
                                            '<li class="sp-all">' +
                                            '<p>订单金额：<em>￥' + v.total_price + '</em></p>' +
                                            '</li>' +
                                            '<li class="sp-all">' +
                                            '<p>运费金额：<em>￥' + v.insure + '</em></p>' +
                                            '</li>' +
                                            '<li class="sp-all">' +
                                            '<p>PV：<i>' + v.total_pv + '</i></p>' +
                                            '</li>' +
                                            '<li class="sp-all">' +
                                            '<p>备注：<i>' + v.remarks + '</i></p>' +
                                            '</li>' +
                                            '</ul>' +
                                            '</div>' +
                                            '</div>' +
                                            '<div class="mysale-bottom">' +
                                            '<p>姓名：' + v.shr_name + '</p>' +
                                            '<p>手机：' + v.shr_tel + '</p>' +
                                            '<p>收货地区：' + v.area + '</p>' +
                                            '<p>详细地址：' + v.shr_address + '</p>' +
                                            '<p>订单时间：' + v.add_time + '</p>' +
                                            '</div>' +
                                            '<div class="mysale-btn">' +
                                            btn1 +
                                            btn2 +
                                            '</div>' +
                                            '</div>';
                                    eachNumber++;
                                }
                            } );
                            if( totalNumber > eachNumber ){
                                html += '<div align="center" onclick="getNewGoods();" class="sp-list-bottom goods-last-notice"><span>上拉/点击获取更多订单</span></div>';
                            }else{
                                html += '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有更多订单了</span></div>';
                                status = 'false';
                            }
                        }else{
                            html = '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有相应的订单</span></div>';
                        }
                        list.html( html );
                    }
                }
            } );
        }

        /* 获取更多订单 */
        $(document).ready(function() {
            $(window).scroll(function() {
                //$(document).scrollTop() 获取垂直滚动的距离
                //$(document).scrollLeft() 这是获取水平滚动条的距离
                if ($(document).scrollTop() >= $(document).height() - $(window).height()) {
                    if( o_search == 'false' ){
                        getNewGoods();
                    }else{
                        getNewSearch();
                    }
                }
            });
        });

        /* 获取新订单 */
        function getNewGoods() {
            var list = $( '#goods-list' );
            var html = '';
            if( status == 'true' ){
                $( '.goods-last-notice span' ).html( '正在获取 <i class="fa fa-spinner fa-spin"></i>' );
                var minNumber = eachNumber;
                var maxNumber = Number(eachNumber) + Number( singleNumber );
                $.each( data[0], function ( k, v ){
                    if( ( minNumber <= k && k < maxNumber ) && k < totalNumber ){
                        var span = '';
                        var btn1 = '';
                        var btn2 = '';
                        if( o_status == 'Y' ){
                            switch( p_status ){
                                case 1:
                                    span = '<span style="color: orange;">未发货</span>';
                                    btn1 = '<button onclick="cancel_order( ' + v.id + ' )" type="button" class="btn left-btn">取消订单</button>';
                                    btn2 = '<button onclick="order_detail( ' + v.id + ' )" type="button" class="btn">查看订单</button>';
                                    break;
                                case 2:
                                    span = '<span>已发货</span>';
                                    btn1 = '<button onclick="order_express( ' + v.id + ' )" type="button" class="btn left-btn">查看物流</button>';
                                    btn2 = '<button onclick="confirm_receipt( ' + v.id + ' )" type="button" class="btn">确认收货</button>';
                                    break;
                                case 3:
                                    span = '<span style="color: green;">已完成</span>';
                                    btn1 = '<button onclick="order_express( ' + v.id + ' )" type="button" class="btn left-btn">查看物流</button>';
                                    btn2 = '<button onclick="order_detail( ' + v.id + ' )" type="button" class="btn">查看订单</button>';
                                    break;
                            }
                        }else{
                            span = '<span style="color: red;">已取消</span>';
                            btn2 = '<button onclick="order_detail( ' + v.id + ' )" type="button" class="btn">查看订单</button>';
                        }
                        html += '<div class="sp-list-bottom">' +
                                '<div class="up">' +
                                '<h4>订单号：' + v.order_sn +'</h4>' +
                                '<div class="up-right-all">' +
                                span +
                                '</div>' +
                                '</div>' +
                                '<div class="down">' +
                                '<div class="down-left">';
                        if( v.logo != '' ){
                            var logoNumber = 0;
                            $.each( v.logo, function ( k1, v1 ){
                                logoNumber++;
                                html += '<img src="' + v1 + '">';
                            } );
                            if( logoNumber < 3 ){
                                html += '<img><img>';
                            }
                        }

                        html += '</div>' +
                                '<div class="down-right">' +
                                '<ul>' +
                                '<li class="sp-all">' +
                                '<p>订单金额：<em>￥' + v.total_price + '</em></p>' +
                                '</li>' +
                                '<li class="sp-all">' +
                                '<p>运费金额：<em>￥' + v.insure + '</em></p>' +
                                '</li>' +
                                '<li class="sp-all">' +
                                '<p>PV：<i>' + v.total_pv + '</i></p>' +
                                '</li>' +
                                '<li class="sp-all">' +
                                '<p>备注：<i>' + v.remarks + '</i></p>' +
                                '</li>' +
                                '</ul>' +
                                '</div>' +
                                '</div>' +
                                '<div class="mysale-bottom">' +
                                '<p>姓名：' + v.shr_name + '</p>' +
                                '<p>手机：' + v.shr_tel + '</p>' +
                                '<p>收货地区：' + v.area + '</p>' +
                                '<p>详细地址：' + v.shr_address + '</p>' +
                                '<p>订单时间：' + v.add_time + '</p>' +
                                '</div>' +
                                '<div class="mysale-btn">' +
                                btn1 +
                                btn2 +
                                '</div>' +
                                '</div>';
                        eachNumber++;
                    }
                } );
                if( totalNumber > eachNumber ){
                    html += '<div align="center" onclick="getNewGoods();" class="sp-list-bottom goods-last-notice"><span>上拉/点击获取更多订单</span></div>';
                    cache.push( html );
                }else{
                    html += '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有更多订单了</span></div>';
                    status = 'false';
                }
                list.children( '.goods-last-notice' ).remove();
                list.append( html );
            }
        }

        /* 搜索订单 */
        function goods_search( a ) {
            /* 获取关键字 */
            var val = $( a ).parent().prev().children().val();
            /* 定义总的加载的商品个数 */
            totalNumber = 0;
            /* 定义当前总共加载多少个 */
            eachNumber = 0;
            /* 缓存数据 */
            data = [];
            /* 缓存网页数据 */
            cache = [];
            /* 是否还能下拉 */
            status = 'true';
            /* 是否搜索 */
            o_search = 'true';

            $( '.sp-list-top' ).find( 'li span' ).removeClass( 'sp-list-active' );

            var list = $( '#goods-list' );
            var spinner = '<div class="sk-spinner sk-spinner-double-bounce">' +
                    '<div class="sk-double-bounce1"></div>' +
                    '<div class="sk-double-bounce2"></div></div>';
            list.html( spinner );
            $.ajax( {
                url: "{:U( 'Order/getLst' )}",
                type: 'POST',
                data: { 'keywords': val },
                dataType: 'json',
                success: function ( json ){
                    if( json.code == 'success' ){
                        var html = '';
                        if( json.data != '' ){
                            data.push( json.data );
                            $.each( json.data, function ( k, v ){
                                totalNumber++;
                                if( k < singleNumber ){
                                    var span = '';
                                    var btn1 = '';
                                    var btn2 = '';
                                    if( v.order_status == 'Y' ){
                                        switch( v.post_status ){
                                            case '1':
                                                span = '<span style="color: orange;">未发货</span>';
                                                btn1 = '<button onclick="cancel_order( ' + v.id + ' )" type="button" class="btn left-btn">取消订单</button>';
                                                btn2 = '<button onclick="order_detail( ' + v.id + ' )" type="button" class="btn">查看订单</button>';
                                                break;
                                            case '2':
                                                span = '<span>已发货</span>';
                                                btn1 = '<button onclick="order_express( ' + v.id + ' )" type="button" class="btn left-btn">查看物流</button>';
                                                btn2 = '<button onclick="confirm_receipt( ' + v.id + ' )" type="button" class="btn">确认收货</button>';
                                                break;
                                            case '3':
                                                span = '<span style="color: green;">已完成</span>';
                                                btn1 = '<button onclick="order_express( ' + v.id + ' )" type="button" class="btn left-btn">查看物流</button>';
                                                btn2 = '<button onclick="order_detail( ' + v.id + ' )" type="button" class="btn">查看订单</button>';
                                                break;
                                        }
                                    }else{
                                        span = '<span style="color: red;">已取消</span>';
                                        btn2 = '<button onclick="order_detail( ' + v.id + ' )" type="button" class="btn">查看订单</button>';
                                    }
                                    html += '<div class="sp-list-bottom">' +
                                            '<div class="up">' +
                                            '<h4>订单号：' + v.order_sn +'</h4>' +
                                            '<div class="up-right-all">' +
                                            span +
                                            '</div>' +
                                            '</div>' +
                                            '<div class="down">' +
                                            '<div class="down-left">';
                                    if( v.logo != '' ){
                                        var logoNumber = 0;
                                        $.each( v.logo, function ( k1, v1 ){
                                            logoNumber++;
                                            html += '<img src="' + v1 + '">';
                                        } );
                                        if( logoNumber < 3 ){
                                            html += '<img><img>';
                                        }
                                    }

                                    html += '</div>' +
                                            '<div class="down-right">' +
                                            '<ul>' +
                                            '<li class="sp-all">' +
                                            '<p>订单金额：<em>￥' + v.total_price + '</em></p>' +
                                            '</li>' +
                                            '<li class="sp-all">' +
                                            '<p>运费金额：<em>￥' + v.insure + '</em></p>' +
                                            '</li>' +
                                            '<li class="sp-all">' +
                                            '<p>PV：<i>' + v.total_pv + '</i></p>' +
                                            '</li>' +
                                            '<li class="sp-all">' +
                                            '<p>备注：<i>' + v.remarks + '</i></p>' +
                                            '</li>' +
                                            '</ul>' +
                                            '</div>' +
                                            '</div>' +
                                            '<div class="mysale-bottom">' +
                                            '<p>姓名：' + v.shr_name + '</p>' +
                                            '<p>手机：' + v.shr_tel + '</p>' +
                                            '<p>收货地区：' + v.area + '</p>' +
                                            '<p>详细地址：' + v.shr_address + '</p>' +
                                            '<p>订单时间：' + v.add_time + '</p>' +
                                            '</div>' +
                                            '<div class="mysale-btn">' +
                                            btn1 +
                                            btn2 +
                                            '</div>' +
                                            '</div>';
                                    eachNumber++;
                                }
                            } );
                            if( totalNumber > eachNumber ){
                                html += '<div align="center" onclick="getNewSearch();" class="sp-list-bottom goods-last-notice"><span>上拉/点击获取更多订单</span></div>';
                            }else{
                                html += '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有更多订单了</span></div>';
                                status = 'false';
                            }
                        }else{
                            html = '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有相应的订单</span></div>';
                        }
                        list.html( html );
                    }
                }
            } );
        }

        /* 获取搜索新订单 */
        function getNewSearch() {
            var list = $( '#goods-list' );
            var html = '';
            if( status == 'true' ){
                $( '.goods-last-notice span' ).html( '正在获取 <i class="fa fa-spinner fa-spin"></i>' );
                var minNumber = eachNumber;
                var maxNumber = Number(eachNumber) + Number( singleNumber );
                $.each( data[0], function ( k, v ){
                    if( ( minNumber <= k && k < maxNumber ) && k < totalNumber ){
                        var span = '';
                        var btn1 = '';
                        var btn2 = '';
                        if( v.order_status == 'Y' ){
                            switch( v.post_status ){
                                case '1':
                                    span = '<span style="color: orange;">未发货</span>';
                                    btn1 = '<button onclick="cancel_order( ' + v.id + ' )" type="button" class="btn left-btn">取消订单</button>';
                                    btn2 = '<button onclick="order_detail( ' + v.id + ' )" type="button" class="btn">查看订单</button>';
                                    break;
                                case '2':
                                    span = '<span>已发货</span>';
                                    btn1 = '<button onclick="order_express( ' + v.id + ' )" type="button" class="btn left-btn">查看物流</button>';
                                    btn2 = '<button onclick="confirm_receipt( ' + v.id + ' )" type="button" class="btn">确认收货</button>';
                                    break;
                                case '3':
                                    span = '<span style="color: green;">已完成</span>';
                                    btn1 = '<button onclick="order_express( ' + v.id + ' )" type="button" class="btn left-btn">查看物流</button>';
                                    btn2 = '<button onclick="order_detail( ' + v.id + ' )" type="button" class="btn">查看订单</button>';
                                    break;
                            }
                        }else{
                            span = '<span style="color: red;">已取消</span>';
                            btn2 = '<button onclick="order_detail( ' + v.id + ' )" type="button" class="btn">查看订单</button>';
                        }
                        html += '<div class="sp-list-bottom">' +
                                '<div class="up">' +
                                '<h4>订单号：' + v.order_sn +'</h4>' +
                                '<div class="up-right-all">' +
                                span +
                                '</div>' +
                                '</div>' +
                                '<div class="down">' +
                                '<div class="down-left">';
                        if( v.logo != '' ){
                            var logoNumber = 0;
                            $.each( v.logo, function ( k1, v1 ){
                                logoNumber++;
                                html += '<img src="' + v1 + '">';
                            } );
                            if( logoNumber < 3 ){
                                html += '<img><img>';
                            }
                        }

                        html += '</div>' +
                                '<div class="down-right">' +
                                '<ul>' +
                                '<li class="sp-all">' +
                                '<p>订单金额：<em>￥' + v.total_price + '</em></p>' +
                                '</li>' +
                                '<li class="sp-all">' +
                                '<p>运费金额：<em>￥' + v.insure + '</em></p>' +
                                '</li>' +
                                '<li class="sp-all">' +
                                '<p>PV：<i>' + v.total_pv + '</i></p>' +
                                '</li>' +
                                '<li class="sp-all">' +
                                '<p>备注：<i>' + v.remarks + '</i></p>' +
                                '</li>' +
                                '</ul>' +
                                '</div>' +
                                '</div>' +
                                '<div class="mysale-bottom">' +
                                '<p>姓名：' + v.shr_name + '</p>' +
                                '<p>手机：' + v.shr_tel + '</p>' +
                                '<p>收货地区：' + v.area + '</p>' +
                                '<p>详细地址：' + v.shr_address + '</p>' +
                                '<p>订单时间：' + v.add_time + '</p>' +
                                '</div>' +
                                '<div class="mysale-btn">' +
                                btn1 +
                                btn2 +
                                '</div>' +
                                '</div>';
                        eachNumber++;
                    }
                } );
                if( totalNumber > eachNumber ){
                    html += '<div align="center" onclick="getNewGoods();" class="sp-list-bottom goods-last-notice"><span>上拉/点击获取更多订单</span></div>';
                    cache.push( html );
                }else{
                    html += '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有更多订单了</span></div>';
                    status = 'false';
                }
                list.children( '.goods-last-notice' ).remove();
                list.append( html );
            }
        }

        /* 查看订单详细信息 */
        function order_detail( id ) {
            window.location.href = "{:U('Order/order_detail', '', false)}/detail/detail/id/"+id;
        }

        /* 查看订单物流信息 */
        function order_express( id ) {
            window.location.href = "{:U('Order/order_detail', '', false)}/detail/express/id/"+id;
        }

        /* 取消订单 */
        function cancel_order( id ) {
            var html = '<div class="modal fade in" tabindex="-1" role="dialog" id="MyShare" aria-hidden="false" style="display: block;">' +
                    '<div class="modal-dialog">' +
                    '<div class="out-zx">' +
                    '<i style="color: red" class="fa fa-exclamation-circle fa-5x"></i>' +
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
                $( '#remove' ).html( '取消中 <i class="fa fa-spinner fa-spin"></i>' );
                window.location.href = "{:U('Order/cancel_order', '', false)}/id/"+id;
            } );
        }

        /* 确认收货 */
        function confirm_receipt( id ){
            var html = '<div class="modal fade in" tabindex="-1" role="dialog" id="MyShare" aria-hidden="false" style="display: block;">' +
                    '<div class="modal-dialog">' +
                    '<div class="out-zx">' +
                    '<img src="__PUBLIC__/Home/default/img/smile@3x.png"><br/>' +
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
                window.location.href = "{:U('Order/confirm_receipt', '', false)}/id/"+id;
            } );
        }
    </script>
@endsection