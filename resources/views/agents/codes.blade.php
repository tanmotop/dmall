@extends('layouts.app')

@section('styles')
	<link href="/assets/css/yqmcx.css" rel="stylesheet" />
	<link href="/plugins/bootstrap/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
	<style type="text/css">
		/* 加载动画 */
		.sk-spinner-double-bounce.sk-spinner {width:40px;height:40px;position:relative;margin:0 auto;}
		.sk-spinner-double-bounce .sk-double-bounce1,.sk-spinner-double-bounce .sk-double-bounce2 {width:100%;height:100%;border-radius:50%;background-color:#1ab394;opacity:0.6;position:absolute;top:0;left:0;-webkit-animation:sk-doubleBounce 2s infinite ease-in-out;animation:sk-doubleBounce 2s infinite ease-in-out;}
		.sk-spinner-double-bounce .sk-double-bounce2 {-webkit-animation-delay:-1s;animation-delay:-1s;}
		@-webkit-keyframes sk-doubleBounce {
			0%,100% {-webkit-transform:scale(0);transform:scale(0);}
			50% {-webkit-transform:scale(1);transform:scale(1);}
		}
		@keyframes sk-doubleBounce {
			0%,100% {-webkit-transform:scale(0);transform:scale(0);}
			50% {-webkit-transform:scale(1);transform:scale(1);}
		}
		.up-right input {margin-left:8px;margin-right:8px;border:none;border-right:1px solid #BFBFBF;border-left:1px solid #BFBFBF;/*border-radius:3px;*/line-height:2em;outline:none;width:30%;/* margin-bottom:10px;*/font-size:0.8em;color:#000;text-align:center;/*padding:0;*/}
	</style>
@endsection

@section('content')
	<div class="zc-body">
        <div class="body-top">
            <div style="margin-top: 5px;margin-left: 10px;" class="top-left">
                <a href="{{ route('agents') }}">
                    <i style="font-size: 22px;margin-left: 5px;color: #b3b3b3;" class="fa fa-chevron-left"></i>
                </a>
            </div>
            <div style="margin-left: 20px;" class="yqmcx-top-center">
                <ul>
                    <li><span onclick="select_cat( this, 'all' )" class="active-color">全部</span></li>
                    <li><span onclick="select_cat( this, 'Y' )">已使用</span></li>
                    <li><span onclick="select_cat( this, 'N' )">未使用</span></li>
                </ul>
            </div>
            <div style="margin-top: 8px;margin-right: 10px;" class="top-right">
                <a href="{{ route('home') }}">
                    <i style="font-size: 26px;margin-left: -5px;color: #b3b3b3;" class="fa fa-home"></i>
                </a>
            </div>
        </div>
        <div id="code_list">
        	@foreach($codes as $code)
        		<div class="yqmcx-bottom">
	                <p>
	                	邀请码： {{ $code->code }} <div class="line"></div>
	                	@if ($code->stat == 1)
						    <span style="color: green;">{{ $code->use_uname }}</span>
						@elseif ($code->stat == 2)
						    <span style="color: red;">已失效</span>
						@else
						    <span class="wsy-color">未使用</span>
						@endif
	                </p>
	                <p>编号：{{ $code->create_uid }}</p>
	                <p>发放时间：{{ $code->created_at }} </p>
	                <p>有效时间：{{ $code->expired_at }} </p>
	            </div>
        	@endforeach
            {{-- <div class="sk-spinner sk-spinner-double-bounce">
                <div class="sk-double-bounce1"></div>
                <div class="sk-double-bounce2"></div>
            </div> --}}
        </div>
    </div>
    <br/><br/>
@endsection

@section('scripts')
	<script type="text/javascript">
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

	    /* 默认加载未发货订单 */
	    $( function () {
	        $.ajax( {
	            url: "{:U( 'Member/ajaxGetCode' )}",
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
	                                html += '<div class="yqmcx-bottom">' +
	                                        '<p>邀请码：' + v.invitation_code + '</p>' +
	                                        '<div class="line"></div> ' +
	                                        v.span +
	                                        '<p>编号：' + v.create_member_id + '</p>' +
	                                        '<p>发放时间：' + v.add_time + '</p>' +
	                                        '<p>有效时间：' + v.life_time + '</p>' +
	                                        '</div>';
	                                eachNumber++;
	                            }
	                        } );
	                        if( totalNumber > eachNumber ){
	                            html += '<div align="center" onclick="getNewGoods();" class="sp-list-bottom goods-last-notice"><span>上拉/点击获取更多邀请码</span></div>';
	                        }else{
	                            html += '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有更多邀请码了</span></div>';
	                            status = 'false';
	                        }
	                    }else{
	                        html = '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有相应的邀请码</span></div>';
	                    }
	                    $( '#code_list' ).html( html );
	                }
	            }
	        } );
	    } );


	    /* 选择激活码状态 */
	    function select_cat( span, type ) {
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

	        $( '.yqmcx-top-center' ).find( 'li span' ).removeClass( 'active-color' );
	        $( span ).addClass( 'active-color' );

	        var list = $( '#code_list' );
	        var spinner = '<div class="sk-spinner sk-spinner-double-bounce">' +
	                '<div class="sk-double-bounce1"></div>' +
	                '<div class="sk-double-bounce2"></div></div>';
	        list.html( spinner );
	        $.ajax( {
	            url: "{:U( 'Member/ajaxGetCode' )}",
	            type: 'POST',
	            data: { 'type': type },
	            dataType: 'json',
	            success: function ( json ) {
	                if( json.code == 'success' ){
	                    var html = '';
	                    if( json.data != '' ){
	                        data.push( json.data );
	                        $.each( json.data, function ( k, v ){
	                            totalNumber++;
	                            if( k < singleNumber ){
	                                html += '<div class="yqmcx-bottom">' +
	                                        '<p>邀请码：' + v.invitation_code + '</p>' +
	                                        '<div class="line"></div> ' +
	                                        v.span +
	                                        '<p>编号：' + v.create_member_id + '</p>' +
	                                        '<p>发放时间：' + v.add_time + '</p>' +
	                                        '<p>有效时间：' + v.life_time + '</p>' +
	                                        '</div>';
	                                eachNumber++;
	                            }
	                        } );
	                        if( totalNumber > eachNumber ){
	                            html += '<div align="center" onclick="getNewGoods();" class="sp-list-bottom goods-last-notice"><span>上拉/点击获取更多邀请码</span></div>';
	                        }else{
	                            html += '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有更多邀请码了</span></div>';
	                            status = 'false';
	                        }
	                    }else{
	                        html = '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有相应的邀请码</span></div>';
	                    }
	                    list.html( html );
	                }
	            }
	        } );
	    }

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
	        var list = $( '#code_list' );
	        var html = '';
	        if( status == 'true' ){
	            $( '.goods-last-notice span' ).html( '正在获取 <i class="fa fa-spinner fa-spin"></i>' );
	            var minNumber = eachNumber;
	            var maxNumber = Number(eachNumber) + Number( singleNumber );
	            $.each( data[0], function ( k, v ){
	                if( ( minNumber <= k && k < maxNumber ) && k < totalNumber ){
	                    html += '<div class="yqmcx-bottom">' +
	                            '<p>邀请码：' + v.invitation_code + '</p>' +
	                            '<div class="line"></div> ' +
	                            v.span +
	                            '<p>编号：' + v.create_member_id + '</p>' +
	                            '<p>发放时间：' + v.add_time + '</p>' +
	                            '<p>有效时间：' + v.life_time + '</p>' +
	                            '</div>';
	                    eachNumber++;
	                }
	            } );
	            if( totalNumber > eachNumber ){
	                html += '<div align="center" onclick="getNewGoods();" class="sp-list-bottom goods-last-notice"><span>上拉/点击获取更多邀请码</span></div>';
	                cache.push( html );
	            }else{
	                html += '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有更多邀请码了</span></div>';
	                status = 'false';
	            }
	            list.children( '.goods-last-notice' ).remove();
	            list.append( html );
	        }
	    }
	</script>
@endsection


