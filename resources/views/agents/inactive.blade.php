@extends('layouts.app')

@section('styles')
	<link href="/assets/css/wjhdls.css" rel="stylesheet" />
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
	<div class="wjhdls-body">
	    <div class="body-top">
	        <div style="margin-top: 5px;margin-left: 10px;" class="top-left">
	            <a href="{{ route('agents') }}">
	                <i style="font-size: 22px;margin-left: 5px;color: #b3b3b3;" class="fa fa-chevron-left"></i>
	            </a>
	        </div>
	        <div style="margin-top: 8px;margin-right: 10px;" class="top-right">
	            <a href="{{ route('home') }}">
	                <i style="font-size: 26px;margin-left: -5px;color: #b3b3b3;" class="fa fa-home"></i>
	            </a>
	        </div>
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
            url: "{:U( 'Member/ajaxGetUam' )}",
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
                                html += '<div class="wjhdls-bottom">' +
                                        '<div class="wjhdls-bottom-left">' +
                                        '<h4>' + v.real_name + '</h4>' +
                                        '<p>编号：' + v.id + '</p>' +
                                        '<p>邀请码：' + v.invitation_code + '</p>' +
                                        '<p>注册时间：' + v.register_time + '</p>' +
                                        '</div>' +
                                        '<div class="wjhdls-bottom-right">' +
                                        '</div>' +
                                        '</div>';
                                // '<button onclick="activation( ' + v.id + ' );" type="button" class="jh-btn">激活</button>' +
                                eachNumber++;
                            }
                        } );
                        if( totalNumber > eachNumber ){
                            html += '<div align="center" onclick="getNewGoods();" class="sp-list-bottom goods-last-notice"><span>上拉/点击获取更多成员</span></div>';
                        }else{
                            html += '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有更多成员了</span></div>';
                            status = 'false';
                        }
                    }else{
                        html = '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有相应的成员</span></div>';
                    }
                    $( '#member_list' ).html( html );
                }
            }
        } );
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
                    html += '<div class="wjhdls-bottom">' +
                            '<div class="wjhdls-bottom-left">' +
                            '<h4>' + v.real_name + '</h4>' +
                            '<p>编号：' + v.id + '</p>' +
                            '<p>邀请码：' + v.invitation_code + '</p>' +
                            '<p>注册时间：' + v.register_time + '</p>' +
                            '</div>' +
                            '<div class="wjhdls-bottom-right">' +
                            '</div>' +
                            '</div>';
                    // '<button onclick="activation( ' + v.id + ' );" type="button" class="jh-btn">激活</button>' +
                    eachNumber++;
                }
            } );
            if( totalNumber > eachNumber ){
                html += '<div align="center" onclick="getNewGoods();" class="sp-list-bottom goods-last-notice"><span>上拉/点击获取更多成员</span></div>';
                cache.push( html );
            }else{
                html += '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有更多成员了</span></div>';
                status = 'false';
            }
            list.children( '.goods-last-notice' ).remove();
            list.append( html );
        }
    }

    /* 前往激活 */
    /*function activation( id ) {
        window.location.href = "{:U('/', '', false)}/id/"+id;
    }*/
</script>
@endsection

