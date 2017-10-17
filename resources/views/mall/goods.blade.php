@extends('layouts.app')

@section('styles')
	<link href="/assets/css/sp.css" rel="stylesheet" />
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
        #scroller {
            position: absolute;
            z-index: 1;
            -webkit-tap-highlight-color: rgba(0,0,0,0);
            height: 3.05em;
            -webkit-transform: translateZ(0);
            -moz-transform: translateZ(0);
            -ms-transform: translateZ(0);
            -o-transform: translateZ(0);
            transform: translateZ(0);
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-text-size-adjust: none;
            -moz-text-size-adjust: none;
            -ms-text-size-adjust: none;
            -o-text-size-adjust: none;
            text-size-adjust: none;
        width: {$caNumber};
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
                <input type="text" placeholder="商品名称 / 商品编号">
            </div>
            <div style="margin-top: 5px;margin-right: 10px;" class="top-right">
                <a onclick="goods_search( this );">
                    <i style="font-size: 22px;color: #b3b3b3;" class="fa fa-search"></i>
                </a>
            </div>
        </div>
        <div class="sp-list-top" id="wrapper">
            <div id="scroller">
                <ul>
                    <li><span onclick="select_cat( this, 'all' )" class="sp-list-active">全部</span></li>
                    @foreach($cats as $cat)
                        <li><span onclick="select_cat( this, {{ $cat->id }} )">{{ $cat->name}}</span></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <form id="cart_form" action="{:U('Cart/add')}" method="post">
            <div id="goods-list">
                <div class="sk-spinner sk-spinner-double-bounce">
                    <div class="sk-double-bounce1"></div>
                    <div class="sk-double-bounce2"></div>
                </div>
            </div>
        </form>
    </div>
    <br/><br/><br/><br/>
    <footer>
        <div class="footer-left">
            <div class="left-p">
                <p>已选：<em class="total-number">0</em>件</p>
                <p>合计：<em>￥</em><em class="total-price">0</em></p>
            </div>
        </div>
        <div class="footer-right">
            <button style="line-height: 0;" class="cart-btn" type="button">加入购物车</button>
        </div>
    </footer>
@endsection

@section('scripts')
	<script type="text/javascript" src="/plugins/iscroll/iscroll.js"></script>
    <script type="text/javascript">
        var myScroll;
        function loaded () {
            myScroll = new IScroll('#wrapper', { eventPassthrough: true, scrollX: true, scrollY: false, preventDefault: false });
        }
    </script>
    <script type="text/javascript">
        /* 商品图片路径 */
        var viewPath = "/";
        /* 定义总的加载的商品个数 */
        totalNumber = 0;
        /* 定义每次加载的商品个数 */
        singleNumber = 5; // （只能修改此处）
        /* 定义当前总共加载多少个 */
        eachNumber = 0;
        /* 缓存数据 */
        var data = [];
        /* 缓存网页数据 */
        var cache = [];
        /* 是否还能下拉 */
        status = 'true';
        /* 当前分类 */
        catgory_id = 'all';

        /* 默认加载所有商品 */
        $( function () {
            loaded();
            //setScrollerW();
            $.ajax( {
                url: "{{ route('goods_list') }}",
                type: 'GET',
                data: { 'cat_id': 'all' },
                dataType: 'json',
                success: function ( json ) {
                    if( json.code == 'success' ){
                        var html = '';
                        if( json.data != '' ){
                            data.push( json.data );
                            $.each( json.data, function ( k, v ){
                                console.log(k,v)
                                totalNumber++;
                                if( k < singleNumber ){
                                    var goods = v.id + '-' + v.goods_attr_id;
                                    html += '<div class="sp-list-bottom">' +
                                            '<div class="up">' +
                                            '<div class="zh-check">' +
                                            '<input name="cart[' + goods + '][check]" value="Y" onclick="add_goods( this, ' + v.level_price + ' )" type="checkbox" class="chk_1" id="check_a' + eachNumber + '">' +
                                            '<label for="check_a' + eachNumber + '"></label>' +
                                            '</div>' +
                                            '<h4>' + v.name + '</h4>' +
                                            '<div class="up-right">' +
                                            '<img onclick="reduce_number( this, ' + v.level_price + ' );" src="/assets/img/goods_del.png">' +
                                            '<input name="cart[' + goods + '][number]" data-number="1" onchange="change_number( this, ' + v.goods_number + ', ' + v.level_price + ' );" type="number" value="1"/>' +
                                            '<img onclick="add_number( this, ' + v.goods_number + ', ' + v.level_price + ' );" src="/assets/img/goods_add.png">' +
                                            '</div>' +
                                            '</div>' +
                                            '<input type="hidden" name="cart[' + goods + '][pv]" value="' + v.pv + '"/>' +
                                            '<input type="hidden" name="cart[' + goods + '][price]" value="' + v.level_price + '"/>' +
                                            '<div class="down">' +
                                            '<div class="down-left">' +
                                            '<img src="' + v.mid_logo + '">' +
                                            '</div>' +
                                            '<div class="down-right">' +
                                            '<ul>';
                                    $.each( v.m_price, function ( k2, v2 ){
                                        html += '<li><p>' + v2.level_name + '价：￥' + v2.price + '</p></li>';
                                    } );
                                    html += '<li><p>零售价：<em><del>￥' + v.goods_price + '</del></em></p></li>' +
                                            '<li><p>购买价：<em>￥' + v.level_price + '</em></p></li>';
                                    if( v.gaData != '' ){
                                        $.each( v.gaData, function ( k1, v1 ){
                                            html += '<li><p>' + v1.attr_name + '：' + v1.attr_value + '</p></li>';
                                        } );
                                    }

                                    html += '<li><p>PV值：<em>' + v.goods_pv + '</em></p></li>' +
                                            '<li><p>库存：<i>' + v.goods_number + '</i></p></li>' +
                                            '<li><p>产品编号：<i>' + v.goods_sn + '</i></p></li>' +
                                            '</ul></div></div></div>';
                                    eachNumber++;
                                }
                            } );
                            if( totalNumber > eachNumber ){
                                html += '<div align="center" onclick="getNewGoods();" class="sp-list-bottom goods-last-notice"><span>上拉/点击获取更多商品</span></div>';
                            }else{
                                html += '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有更多商品了</span></div>';
                                status = 'false';
                            }
                        }else{
                            html = '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有相应的商品</span></div>';
                        }
                        $( '#goods-list' ).html( html );
                    }
                }
            } );
        } );


        /* 选择商品分类加载相应商品 */
        function select_cat( span, id ) {
            /* 当前分类 */
            catgory_id = id;
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

            /* 清空价格与数量 */
            $( '.total-price' ).text( '0' );
            $( '.total-number' ).text( '0' );

            $( '.sp-list-top' ).find( 'li span' ).removeClass( 'sp-list-active' );
            $( span ).addClass( 'sp-list-active' );
            var list = $( '#goods-list' );
            var spinner = '<div class="sk-spinner sk-spinner-double-bounce">' +
                    '<div class="sk-double-bounce1"></div>' +
                    '<div class="sk-double-bounce2"></div></div>';
            list.html( spinner );
            $.ajax( {
                url: "{{ route('goods_list') }}",
                type: 'GET',
                data: { 'cat_id': id },
                dataType: 'json',
                success: function ( json ) {
                    if( json.code == 'success' ){
                        var html = '';
                        if( json.data != '' ){
                            data.push( json.data );
                            $.each( json.data, function ( k, v ){
                                totalNumber++;
                                if( k < singleNumber ){
                                    var goods = v.id + '-' + v.goods_attr_id;
                                    html += '<div class="sp-list-bottom">' +
                                            '<div class="up">' +
                                            '<div class="zh-check">' +
                                            '<input name="cart[' + goods + '][check]" value="Y" onclick="add_goods( this, ' + v.level_price + ' )" type="checkbox" class="chk_1" id="check_a' + eachNumber + '">' +
                                            '<label for="check_a' + eachNumber + '"></label>' +
                                            '</div>' +
                                            '<h4>' + v.goods_name + '</h4>' +
                                            '<div class="up-right">' +
                                            '<img onclick="reduce_number( this, ' + v.level_price + ' );" src="/assets/img/goods_del.png">' +
                                            '<input name="cart[' + goods + '][number]" data-number="1" onchange="change_number( this, ' + v.goods_number + ', ' + v.level_price + ' );" type="number" value="1"/>' +
                                            '<img onclick="add_number( this, ' + v.goods_number + ', ' + v.level_price + ' );" src="/assets/img/goods_add.png">' +
                                            '</div>' +
                                            '</div>' +
                                            '<input type="hidden" name="cart[' + goods + '][pv]" value="' + v.goods_pv + '"/>' +
                                            '<input type="hidden" name="cart[' + goods + '][price]" value="' + v.level_price + '"/>' +
                                            '<div class="down">' +
                                            '<div class="down-left">' +
                                            '<img src="' + v.mid_logo + '">' +
                                            '</div>' +
                                            '<div class="down-right">' +
                                            '<ul>';
                                    $.each( v.m_price, function ( k2, v2 ){
                                        html += '<li><p>' + v2.level_name + '价：￥' + v2.price + '</p></li>';
                                    } );
                                    html += '<li><p>零售价：<em><del>￥' + v.goods_price + '</del></em></p></li>' +
                                            '<li><p>购买价：<em>￥' + v.level_price + '</em></p></li>';
                                    if( v.gaData != '' ){
                                        $.each( v.gaData, function ( k1, v1 ){
                                            html += '<li><p>' + v1.attr_name + '：' + v1.attr_value + '</p></li>';
                                        } );
                                    }

                                    html += '<li><p>PV值：<em>' + v.goods_pv + '</em></p></li>' +
                                            '<li><p>库存：<i>' + v.goods_number + '</i></p></li>' +
                                            '<li><p>产品编号：<i>' + v.goods_sn + '</i></p></li>' +
                                            '</ul></div></div></div>';
                                    eachNumber++;
                                }
                            } );
                            if( totalNumber > eachNumber ){
                                html += '<div align="center" onclick="getNewGoods();" class="sp-list-bottom goods-last-notice"><span>上拉/点击获取更多商品</span></div>';
                            }else{
                                html += '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有更多商品了</span></div>';
                                status = 'false';
                            }
                        }else{
                            html = '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有相应的商品</span></div>';
                        }
                        list.html( html );
                    }
                }
            } );
        }

        /* 获取更多商品 */
        $(document).ready(function() {
            $(window).scroll(function() {
                //$(document).scrollTop() 获取垂直滚动的距离
                //$(document).scrollLeft() 这是获取水平滚动条的距离
                if ($(document).scrollTop() >= $(document).height() - $(window).height()) {
                    getNewGoods();
                }
            });
        });

        /* 获取新商品 */
        function getNewGoods() {
            var list = $( '#goods-list' );
            var html = '';
            if( status == 'true' ){
                $( '.goods-last-notice span' ).html( '正在获取 <i class="fa fa-spinner fa-spin"></i>' );
                var minNumber = eachNumber;
                var maxNumber = Number(eachNumber) + Number( singleNumber );
                $.each( data[0], function ( k, v ){
                    if( ( minNumber <= k && k < maxNumber ) && k < totalNumber ){
                        var goods = v.id + '-' + v.goods_attr_id;
                        html += '<div class="sp-list-bottom">' +
                                '<div class="up">' +
                                '<div class="zh-check">' +
                                '<input name="cart[' + goods + '][check]" value="Y" onclick="add_goods( this, ' + v.level_price + ' )" type="checkbox" class="chk_1" id="check_a' + eachNumber + '">' +
                                '<label for="check_a' + eachNumber + '"></label>' +
                                '</div>' +
                                '<h4>' + v.goods_name + '</h4>' +
                                '<div class="up-right">' +
                                '<img onclick="reduce_number( this, ' + v.level_price + ' );" src="/assets/img/goods_del.png">' +
                                '<input name="cart[' + goods + '][number]" data-number="1" onchange="change_number( this, ' + v.goods_number + ', ' + v.level_price + ' );" type="number" value="1"/>' +
                                '<img onclick="add_number( this, ' + v.goods_number + ', ' + v.level_price + ' );" src="/assets/img/goods_add.png">' +
                                '</div>' +
                                '</div>' +
                                '<input type="hidden" name="cart[' + goods + '][pv]" value="' + v.goods_pv + '"/>' +
                                '<input type="hidden" name="cart[' + goods + '][price]" value="' + v.level_price + '"/>' +
                                '<div class="down">' +
                                '<div class="down-left">' +
                                '<img src="' + v.mid_logo + '">' +
                                '</div>' +
                                '<div class="down-right">' +
                                '<ul>';
                        $.each( v.m_price, function ( k2, v2 ){
                            html += '<li><p>' + v2.level_name + '价：￥' + v2.price + '</p></li>';
                        } );
                        html += '<li><p>零售价：<em><del>￥' + v.goods_price + '</del></em></p></li>' +
                                '<li><p>购买价：<em>￥' + v.level_price + '</em></p></li>';
                        if( v.gaData != '' ){
                            $.each( v.gaData, function ( k1, v1 ){
                                html += '<li><p>' + v1.attr_name + '：' + v1.attr_value + '</p></li>';
                            } );
                        }

                        html += '<li><p>PV值：<em>' + v.goods_pv + '</em></p></li>' +
                                '<li><p>库存：<i>' + v.goods_number + '</i></p></li>' +
                                '<li><p>产品编号：<i>' + v.goods_sn + '</i></p></li>' +
                                '</ul></div></div></div>';
                        eachNumber++;
                    }
                } );
                if( totalNumber > eachNumber ){
                    html += '<div align="center" onclick="getNewGoods();" class="sp-list-bottom goods-last-notice"><span>上拉/点击获取更多商品</span></div>';
                    cache.push( html );
                }else{
                    html += '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有更多商品了</span></div>';
                    status = 'false';
                }
                list.children( '.goods-last-notice' ).remove();
                list.append( html );
            }
        }

        /* 逐个减少商品数量 */
        function reduce_number( img, price ){
            var total_price = $( '.total-price' );
            var total_number = $( '.total-number' );
            var input = $( img ).next();
            var val = $( input ).val();
            var i_status = $( img ).parent().prev().prev().children( 'input' ).is( ':checked' );
            if( val > 1 ){
                if( i_status ){
                    total_price.text( Number( total_price.text() ) - Number( price ) );
                    total_number.text( Number( total_number.text() ) - Number( 1 ) );
                }
                $( input ).val( val - 1 );
            }
            input.attr( 'data-number', $( input ).val() );
        }

        /* 逐个增加商品数量 */
        function add_number( img, goods_number, price ){
            var total_price = $( '.total-price' );
            var total_number = $( '.total-number' );
            var input = $( img ).prev();
            var val = $( input ).val();
            var i_status = $( img ).parent().prev().prev().children( 'input' ).is( ':checked' );
            if( val < goods_number ){
                if( i_status ){
                    total_price.text( Number( total_price.text() ) + Number( price ) );
                    total_number.text( Number( total_number.text() ) + Number( 1 ) );
                }
                $( input ).val( Number(val) + Number(1) );
            }
            input.attr( 'data-number', $( input ).val() );
        }

        /* 手动输入商品数量 */
        function change_number( input, goods_number, price ) {
            var total_price = $( '.total-price' );
            var total_number = $( '.total-number' );
            var val = $( input ).val();
            var old_number = $( input ).attr( 'data-number' );
            var i_status = $( input ).parent().prev().prev().children( 'input' ).is( ':checked' );
            if( val == '' || val < 1 ){
                $( input ).val( 1 );
            }
            if( val > goods_number ){
                $( input ).val( goods_number );
            }
            var new_number = Number( $( input ).val() ) - Number( old_number );
            var new_price = price * new_number;
            if( i_status ){
                total_price.text( Number( total_price.text() ) + Number( new_price ) );
                total_number.text( Number( total_number.text() ) + Number( new_number ) );
            }
            $( input ).attr( 'data-number', $( input ).val() );
        }

        /* 搜索商品 */
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

            /* 清空价格与数量 */
            $( '.total-price' ).text( '0' );
            $( '.total-number' ).text( '0' );

            $( '.sp-list-top' ).find( 'li span' ).removeClass( 'sp-list-active' );

            var list = $( '#goods-list' );
            var spinner = '<div class="sk-spinner sk-spinner-double-bounce">' +
                    '<div class="sk-double-bounce1"></div>' +
                    '<div class="sk-double-bounce2"></div></div>';
            list.html( spinner );
            $.ajax( {
                url: "{{ route('goods_list') }}",
                type: 'GET',
                data: { 'cat_id': 'all', 'keywords': val },
                dataType: 'json',
                success: function ( json ){
                    if( json.code == 'success' ){
                        var html = '';
                        if( json.data != '' ){
                            data.push( json.data );
                            $.each( json.data, function ( k, v ){
                                totalNumber++;
                                if( k < singleNumber ){
                                    var goods = v.id + '-' + v.goods_attr_id;
                                    html += '<div class="sp-list-bottom">' +
                                            '<div class="up">' +
                                            '<div class="zh-check">' +
                                            '<input name="cart[' + goods + '][check]" value="Y" onclick="add_goods( this, ' + v.level_price + ' )" type="checkbox" class="chk_1" id="check_a' + eachNumber + '">' +
                                            '<label for="check_a' + eachNumber + '"></label>' +
                                            '</div>' +
                                            '<h4>' + v.goods_name + '</h4>' +
                                            '<div class="up-right">' +
                                            '<img onclick="reduce_number( this, ' + v.level_price + ' );" src="/assets/img/goods_del.png">' +
                                            '<input name="cart[' + goods + '][number]" data-number="1" onchange="change_number( this, ' + v.goods_number + ', ' + v.level_price + ' );" type="number" value="1"/>' +
                                            '<img onclick="add_number( this, ' + v.goods_number + ', ' + v.level_price + ' );" src="/assets/img/goods_add.png">' +
                                            '</div>' +
                                            '</div>' +
                                            '<input type="hidden" name="cart[' + goods + '][pv]" value="' + v.goods_pv + '"/>' +
                                            '<input type="hidden" name="cart[' + goods + '][price]" value="' + v.level_price + '"/>' +
                                            '<div class="down">' +
                                            '<div class="down-left">' +
                                            '<img src="' + v.mid_logo + '">' +
                                            '</div>' +
                                            '<div class="down-right">' +
                                            '<ul>';
                                    $.each( v.m_price, function ( k2, v2 ){
                                        html += '<li><p>' + v2.level_name + '价：￥' + v2.price + '</p></li>';
                                    } );
                                    html += '<li><p>零售价：<em><del>￥' + v.goods_price + '</del></em></p></li>' +
                                            '<li><p>购买价：<em>￥' + v.level_price + '</em></p></li>';
                                    if( v.gaData != '' ){
                                        $.each( v.gaData, function ( k1, v1 ){
                                            html += '<li><p>' + v1.attr_name + '：' + v1.attr_value + '</p></li>';
                                        } );
                                    }

                                    html += '<li><p>PV值：<em>' + v.goods_pv + '</em></p></li>' +
                                            '<li><p>库存：<i>' + v.goods_number + '</i></p></li>' +
                                            '<li><p>产品编号：<i>' + v.goods_sn + '</i></p></li>' +
                                            '</ul></div></div></div>';
                                    eachNumber++;
                                }
                            } );
                            if( totalNumber > eachNumber ){
                                html += '<div align="center" onclick="getNewGoods();" class="sp-list-bottom goods-last-notice"><span>上拉/点击获取更多商品</span></div>';
                            }else{
                                html += '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有更多商品了</span></div>';
                                status = 'false';
                            }
                        }else{
                            html = '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有相应的商品</span></div>';
                        }
                        list.html( html );
                    }
                }
            } );
        }

        /* 添加商品 */
        function add_goods( input, price ) {
            var new_number = $( input ).parent().next().next().children( 'input' ).val();
            var total_price = $( '.total-price' );
            var total_number = $( '.total-number' );
            var new_price = price * new_number;
            if( $(input).is(':checked') ){
                total_price.text( Number( total_price.text() ) + Number( new_price ) );
                total_number.text( Number( total_number.text() ) + Number( new_number ) );
            }else{
                total_price.text( Number( total_price.text() ) - Number( new_price ) );
                total_number.text( Number( total_number.text() ) - Number( new_number ) );
            }
        }

        /* 提交购物车表单 */
        $( '.cart-btn' ).click( function () {
            document.getElementById("cart_form").submit();
        } );

        /* 设置商品分类菜单宽度 */
        function setScrollerW() {
            var scroller = $( '#scroller' );
            var liNumber = scroller.children( 'ul' ).children( 'li' ).length;
            var width = liNumber * 100;
            scroller.css( 'width', width + 'px' );
        }
    </script>
@endsection