@extends('layouts.app')

@section('styles')
	<link href="/assets/css/order_detail.css" rel="stylesheet" />
    <link href="/plugins/bootstrap/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
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
        /* 时间控件 */
        .dropdown {
            position: relative;
        }
        .dropdown-toggle:focus {
            outline: 0;
        }
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            display: none;
            float: left;
            min-width: 160px;
            padding: 5px 0;
            margin: 2px 0 0;
            font-size: 14px;
            list-style: none;
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border: 1px solid rgba(0, 0, 0, 0.15);
            border-radius: 4px;
            -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
            background-clip: padding-box;
        }
        .dropdown-menu.pull-right {
            right: 0;
            left: auto;
        }
        .dropdown-menu .divider {
            height: 1px;
            margin: 9px 0;
            overflow: hidden;
            background-color: #e5e5e5;
        }
        .dropdown-menu > li > a {
            display: block;
            padding: 3px 20px;
            clear: both;
            font-weight: normal;
            line-height: 1.428571429;
            color: #333333;
            white-space: nowrap;
        }
        .dropdown-menu > li > a:hover,
        .dropdown-menu > li > a:focus {
            color: #ffffff;
            text-decoration: none;
            background-color: #428bca;
        }
        .dropdown-menu > .active > a,
        .dropdown-menu > .active > a:hover,
        .dropdown-menu > .active > a:focus {
            color: #ffffff;
            text-decoration: none;
            background-color: #428bca;
            outline: 0;
        }
        .dropdown-menu > .disabled > a,
        .dropdown-menu > .disabled > a:hover,
        .dropdown-menu > .disabled > a:focus {
            color: #999999;
        }
        .dropdown-menu > .disabled > a:hover,
        .dropdown-menu > .disabled > a:focus {
            text-decoration: none;
            cursor: not-allowed;
            background-color: transparent;
            background-image: none;
            filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
        }
        .open > .dropdown-menu {
            display: block;
        }
        .open > a {
            outline: 0;
        }
        .dropdown-header {
            display: block;
            padding: 3px 20px;
            font-size: 12px;
            line-height: 1.428571429;
            color: #999999;
        }
        .dropdown-backdrop {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 990;
        }
        .pull-right > .dropdown-menu {
            right: 0;
            left: auto;
        }
        .dropup .caret,
        .navbar-fixed-bottom .dropdown .caret {
            border-top: 0 dotted;
            border-bottom: 4px solid #000000;
            content: "";
        }
        .dropup .dropdown-menu,
        .navbar-fixed-bottom .dropdown .dropdown-menu {
            top: auto;
            bottom: 100%;
            margin-bottom: 1px;
        }
        @media (min-width: 768px) {
            .navbar-right .dropdown-menu {
                right: 0;
                left: auto;
            }
        }
    </style>
@endsection

@section('content')
	<div class="zc-body">
        <div class="body-top">
            <div style="margin-top: 5px;margin-left: 10px;" class="top-left">
                <a href="{{ route('finances') }}">
                    <i style="font-size: 22px;margin-left: 5px;color: #b3b3b3;" class="fa fa-chevron-left"></i>
                </a>
            </div>
            <div class="tdcymd-top-center">
                <input id="time" name="time" style="text-align: center" type="text" placeholder="请 选 择 时 间">
            </div>
            <div style="margin-top: 5px;margin-right: 10px;" class="top-right">
                <a onclick="goods_search( this );">
                    <i style="font-size: 22px;color: #b3b3b3;" class="fa fa-search"></i>
                </a>
            </div>
        </div>

        <div class="sp-list-top">
            <ul>
                <li style="width: 50%"><a href="{{ route('finances_flow_cost') }}">消费</a></li>
                <li style="width: 50%"><span class="sp-list-active">充值</span></li>
            </ul>
        </div>

        <div id="member_list">
            <div class="sk-spinner sk-spinner-double-bounce">
                <div class="sk-double-bounce1"></div>
                <div class="sk-double-bounce2"></div>
            </div>
        </div>
    </div>

    <br/><br/>
@endsection


@section('scripts')
	<script src="/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
	<script src="/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
    <script type="text/javascript">
        /* 定义总的加载的订单个数 */
        totalNumber = 0;
        /* 定义每次加载的订单个数 */
        singleNumber = 10; // （只能修改此处）
        /* 定义当前总共加载多少个 */
        eachNumber = 0;
        /* 缓存数据 */
        var data = [];
        /* 缓存网页数据 */
        var cache = [];
        /* 是否还能下拉 */
        status = 'true';

        /* 默认加载 */
        $( function () {
            $.ajax( {
                url: "{:U( 'Finance/ajaxGetFrData' )}",
                type: 'POST',
                dataType: 'json',
                success: function ( json ) {
                    if( json.code == 'success' ){
                        var html = '';
                        if( json.data != '' ){
                            data.push( json.data );
                            $.each( json.data, function ( k, v ){
                                totalNumber++;
                                if( k < singleNumber ){
                                    html += '<div class="zc-bottom">' +
                                            '<ul>' +
                                            '<li>类型：<input readonly type="text" value="账户充值"></li>' +
                                            '<li>充值：<input readonly type="text" value="￥' + v.recharge_money + '"></li>' +
                                            '<li>余额：<input readonly type="text" value="￥' + v.after_recharge_money + '"></li>' +
                                            '<li>时间：<input readonly type="text" value="' + v.add_time + '"></li>' +
                                            '<li>说明：<input style="color: orange" readonly type="text" value="' + v.recharge_mode + '￥' + v.recharge_money + '"></li>' +
                                            '</ul>' +
                                            '</div>';
                                    eachNumber++;
                                }
                            } );
                            if( totalNumber > eachNumber ){
                                html += '<div align="center" onclick="getNewGoods();" class="sp-list-bottom goods-last-notice"><span>上拉/点击获取更多充值记录</span></div>';
                            }else{
                                html += '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有更多充值记录了</span></div>';
                                status = 'false';
                            }
                        }else{
                            html = '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有相应的充值记录</span></div>';
                        }
                        $( '#member_list' ).html( html );
                    }
                }
            } );

            /* 默认选择当前月 */
            var time = new Date();
            var new_time = time.getFullYear() + '-' + (Number(time.getMonth()) + Number(1));
            $( '#time' ).val( new_time );
        } );

        /* 获取更多邀请码 */
        $(document).ready(function() {
            $(window).scroll(function() {
                //$(document).scrollTop() 获取垂直滚动的距离
                //$(document).scrollLeft() 这是获取水平滚动条的距离
                if ($(document).scrollTop() >= $(document).height() - $(window).height()) {
                    getNewGoods();
                }
            });
        });

        /* 获取新邀请码 */
        function getNewGoods() {
            var list = $( '#member_list' );
            var html = '';
            if( status == 'true' ){
                $( '.goods-last-notice span' ).html( '正在获取 <i class="fa fa-spinner fa-spin"></i>' );
                var minNumber = eachNumber;
                var maxNumber = Number(eachNumber) + Number( singleNumber );
                $.each( data[0], function ( k, v ){
                    if( ( minNumber <= k && k < maxNumber ) && k < totalNumber ){
                        html += '<div class="zc-bottom">' +
                                '<ul>' +
                                '<li>类型：<input readonly type="text" value="账户充值"></li>' +
                                '<li>充值：<input readonly type="text" value="￥' + v.recharge_money + '"></li>' +
                                '<li>余额：<input readonly type="text" value="￥' + v.after_recharge_money + '"></li>' +
                                '<li>时间：<input readonly type="text" value="' + v.add_time + '"></li>' +
                                '<li>说明：<input style="color: orange" readonly type="text" value="' + v.recharge_mode + '￥' + v.recharge_money + '"></li>' +
                                '</ul>' +
                                '</div>';
                        eachNumber++;
                    }
                } );
                if( totalNumber > eachNumber ){
                    html += '<div align="center" onclick="getNewGoods();" class="sp-list-bottom goods-last-notice"><span>上拉/点击获取更多充值记录</span></div>';
                    cache.push( html );
                }else{
                    html += '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有更多充值记录了</span></div>';
                    status = 'false';
                }
                list.children( '.goods-last-notice' ).remove();
                list.append( html );
            }
        }

        /* 搜索商品 */
        function goods_search( img ) {
            /* 获取关键字 */
            var val = $( img ).parent().prev().children().val();
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

            var list = $( '#member_list' );
            var spinner = '<div class="sk-spinner sk-spinner-double-bounce">' +
                    '<div class="sk-double-bounce1"></div>' +
                    '<div class="sk-double-bounce2"></div></div>';
            list.html( spinner );
            $.ajax( {
                url: "{:U( 'Finance/ajaxGetFrData' )}",
                type: 'POST',
                data: { 'time': val },
                dataType: 'json',
                success: function ( json ){
                    if( json.code == 'success' ){
                        var html = '';
                        if( json.data != '' ){
                            data.push( json.data );
                            $.each( json.data, function ( k, v ){
                                totalNumber++;
                                if( k < singleNumber ){
                                    html += '<div class="zc-bottom">' +
                                            '<ul>' +
                                            '<li>类型：<input readonly type="text" value="账户充值"></li>' +
                                            '<li>充值：<input readonly type="text" value="￥' + v.recharge_money + '"></li>' +
                                            '<li>余额：<input readonly type="text" value="￥' + v.after_recharge_money + '"></li>' +
                                            '<li>时间：<input readonly type="text" value="' + v.add_time + '"></li>' +
                                            '<li>说明：<input style="color: orange" readonly type="text" value="' + v.recharge_mode + '￥' + v.recharge_money + '"></li>' +
                                            '</ul>' +
                                            '</div>';
                                    eachNumber++;
                                }
                            } );
                            if( totalNumber > eachNumber ){
                                html += '<div align="center" onclick="getNewGoods();" class="sp-list-bottom goods-last-notice"><span>上拉/点击获取更多充值记录</span></div>';
                            }else{
                                html += '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有更多充值记录了</span></div>';
                                status = 'false';
                            }
                        }else{
                            html = '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有相应的充值记录</span></div>';
                        }
                        list.html( html );
                    }
                }
            } );
        }
        /* 时间控件 */
        $('#time').datetimepicker({
            language: 'zh-CN',
            format: 'yyyy-mm',
            initialDate: new Date(),
            autoclose: true,
            startView: 'year',
            minView:'year',
            maxView:'decade'
        });
    </script>
@endsection