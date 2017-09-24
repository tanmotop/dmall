@extends('layouts.app')

@section('styles')
	<link href="/assets/css/sp.css" rel="stylesheet"/>
    <link href="/plugins/bootstrap/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <style>
        .up-right input {
            margin-left: 8px;
            margin-right: 8px;
            border: none;
            border-right: 1px solid #BFBFBF;
            border-left: 1px solid #BFBFBF;
            /* border-radius: 3px; */
            line-height: 2em;
            outline: none;
            width: 25%;
            /* margin-bottom: 10px; */
            font-size: 0.8em;
            color: #000;
            text-align: center;
            /* padding: 0; */
        }
        .up-right img {
            width: 2em;
        }
        .up-right {
            width: 60%;
            position: relative;
            right: -60%;
            /*float: right;*/
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
            <div style="margin-top: 8px;margin-right: 10px;" class="top-right">
                <a href="{{ route('home') }}">
                    <i style="font-size: 26px;margin-left: -5px;color: #b3b3b3;" class="fa fa-home"></i>
                </a>
            </div>
        </div>

        <if condition="!empty( $data )">
            <div id="remove1" style="height: 30px;" class="sp-list-bottom">
                <div style="margin-top: 1px;margin-left: 13px;" class="zh-check">
                    <input onclick="select_all( this, {$tmp.price}, {$tmp.number} );" name="cart_a" value="Y"
                           type="checkbox" id="check_a" class="chk_1">
                    <label for="check_a"></label>
                </div>
                <h4 style="margin-top: 1px;">选择全部商品</h4>
            </div>
        </if>

        <form id="car-goods" action="{:U('Order/add')}" method="get">
            <if condition="!empty($data)">
                <foreach name="data" key="k" item="v">
                    <div class="sp-list-bottom splistbottom">
                        <div class="up">
                            <div class="zh-check">
                                <input name="cart[{$v.id}]" value="{$v.goods_number}"
                                       onclick="add_goods( this, '{$v.goods_price}', '{$v.goods_number}' );" type="checkbox"
                                       id="check_a{$k}" class="chk_1">
                                <label for="check_a{$k}"></label>
                            </div>
                            <h4>{$v.goods_name}</h4>
                            <div class="up-right-delet">
                                <button data-id="{$v.id}" type="button" class="sc-btn">删除</button>
                            </div>
                        </div>
                        <div class="down">
                            <div class="down-left">
                                <img src="{$v.mid_logo|showImg=###, 'goods/', 'logo/'}"/>
                            </div>
                            <div class="down-right">
                                <ul>
                                    <li>
                                        <p>购买价：<em>￥{$v.goods_price}</em></p>
                                        <div class="mycart-number">
                                            <div class="up-right">
                                            <img onclick="reduce_number( this, '{$v.goods_price}' );" src="__PUBLIC__/Home/default/img/goods_del.png">
                                            <input  data-number="1" onchange="change_number( this, '{$v.total_number}', '{$v.goods_price}' );" data-dj="{$v.goods_price}" id="id_{$v.goods_id}" type="number" value="{$v.goods_number}"/>
                                            <img onclick="add_number( this, '{$v.total_number}', '{$v.goods_price}' );" src="__PUBLIC__/Home/default/img/goods_add.png">
                                            </div>

                                        </div>
                                    </li>
                                    <foreach name="v['gaData']" item="v1">
                                        <li><p>{$v1.attr_name}：<span class="active-kw">{$v1.attr_value}</span></p></li>
                                    </foreach>
                                    <li><p>PV值：<i>{$v.goods_pv}</i></p></li>
                                    <li><p>产品编号：<i>{$v.goods_sn}</i></p></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </foreach>
                <else/>
                <div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>您的购物车为空</span></div>
            </if>
        </form>

        <if condition="!empty( $data )">
            <div id="remove2" style="height: 30px;" class="sp-list-bottom">
                <div style="margin-top: 1px;margin-left: 13px;" class="zh-check">
                    <input onclick="select_all( this, {$tmp.price}, {$tmp.number} );" name="cart_b" value="N"
                           type="checkbox" id="check_b" class="chk_1">
                    <label for="check_b"></label>
                </div>
                <h4 style="margin-top: 1px;">选择全部商品</h4>
            </div>
        </if>
    </div>
    <br/><br/><br/><br/>
    <footer>
        <div class="footer-left">
            <div class="left-p">
                <p>数量：<em class="total-number">0</em>件</p>
                <p>小计：<em>￥</em><em class="total-price">0</em></p>
            </div>
        </div>
        <div class="footer-right">
            <button style="line-height: 0;" class="cart-btn" type="button">提交订单</button>
        </div>
    </footer>
    <div id="cart_dialog"></div>
@endsection

@section('styles')
	
    <script type="text/javascript">
        /* 选择要购买的商品 */
        function add_goods(input, price, number) {

            var total_price = $('.total-price');
            var total_number = $('.total-number');
            var a = $('#check_a');
            var b = $('#check_b');
            var form = $('#car-goods');
            var checkbox = form.children('.sp-list-bottom').children('.up').children('.zh-check').children('input');
            var length = checkbox.length;
            var count = 0;
            for (var i in checkbox) {
                if (checkbox[i].checked) {
                    count++;
                }
            }
            if (count == length) {
                a.prop('checked', 'checked');
                b.prop('checked', 'checked');
            } else {
                a.removeAttr('checked');
                b.removeAttr('checked');
            }
            //alert(length);alert(count);

                var jk = 0;
                var zs = 0;
                $('.splistbottom').each(function(){
                    if($(this).find('.up').find('input').is( ':checked' )){
                        var g = parseInt($(this).find('.down').find('.up-right').find('input').val());
                        var h =parseInt($(this).find('.down').find('.up-right').find('input').attr('data-dj'));
                        var k = g*h;
                        jk = jk+g;
                        zs = zs+k;
                    }
                })
                total_price.text(zs);
                total_number.text(jk);

        }
        /* 逐个减少商品数量 */
        function reduce_number( img, price ){
            var total_price = $( '.total-price' );
            var total_number = $( '.total-number' );
            var input = $( img ).next();
            var val = $( input ).val();
            var i_status = $( img ).parents('.down').prev().find( 'input' ).is( ':checked' );
            if( val > 1 ){
                if( i_status ){
                    total_price.text( Number( total_price.text() ) - Number( price ) );
                    total_number.text( Number( total_number.text() ) - Number( 1 ) );
                }
                $(input).val( val - 1 );

            }
            input.attr( 'data-number', $( input ).val() );
            $(input).parents(".down").prev().find('input').val($( input ).val());

        }

        /* 逐个增加商品数量 */
        function add_number( img, goods_number, price ){
            var total_price = $( '.total-price' );
            var total_number = $( '.total-number' );
            var input = $( img ).prev();
            var val = parseInt($( input ).val());
            var i_status = $( img ).parents('.down').prev().find( 'input' ).is( ':checked' );
            if( val < goods_number ){
                if( i_status ){
                    total_price.text( Number( total_price.text() ) + Number( price ) );
                    total_number.text( Number( total_number.text() ) + Number( 1 ) );
                }
                $( input ).val( val + 1 );
            }
            input.attr( 'data-number', $( input ).val() );
            input.attr('value',$( input ).val() );
            $(input).parents(".down").prev().find('input').val($( input ).val());
        }
        /* 手动输入商品数量 */
        function change_number( input, goods_number, price ) {
            var total_price = $( '.total-price' );
            var total_number = $( '.total-number' );
            var val = parseInt($( input ).val());
            var old_number = $( input ).attr( 'data-number' );
            var i_status = $(input).parents('.down').prev().find( 'input' ).is( ':checked' );
            if( val == '' || val < 1 ){
                $( input ).val( 1 );
            }
            if( val > goods_number ){
                $( input ).val( goods_number );
            }
            var new_number = Number( $( input ).val() ) - Number( old_number );
            var new_price = price * new_number;
            if( i_status ){
                var jk = 0;
                var zs = 0;
                $('.splistbottom').each(function(){
                    if($(this).find('.up').find('input').is( ':checked' )){
                        var g = parseInt($(this).find('.down').find('.up-right').find('input').val());
                        var h =parseInt($(this).find('.down').find('.up-right').find('input').attr('data-dj'));
                        var k = g*h;
                        jk = jk+g;
                        zs = zs+k;
                    }
                })
                total_price.text(zs);
                total_number.text(jk);
            }
            $(input).parents(".down").prev().find('input').val($( input ).val());
            $( input ).attr( 'data-number', $( input ).val() );

        }
        /* 选择要删除的商品 */
        $('.sc-btn').click(function () {
            var div = $(this).parent().parent().parent();
            var form = $('#car-goods');
            var dialog = $('#cart_dialog');
            var id = $(this).attr('data-id');
            var html = '<div class="modal fade in" tabindex="-1" role="dialog" id="MyShare" aria-hidden="false" style="display: block;">' +
                    '<div class="modal-dialog">' +
                    '<div class="out-zx">' +
                    '<i style="color: red" class="fa fa-exclamation-circle fa-5x"></i>' +
                    '<p>删除提示</p>' +
                    '<div class="change-btn">' +
                    '<button style="float: none" type="button" class="left-btn btn-close">再想想</button>' +
                    '<button style="float: none;" type="button" class="right-btn btn-login">确认删除</button>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="modal-back fade in"></div>';
            dialog.html(html);

            /* 关闭模态框 */
            $('.btn-close').click(function () {
                dialog.html('');
            });

            /* 确认删除 */
            $('.btn-login').click(function () {
                $.ajax({
                    url: "{:U( 'Cart/ajaxDelCartGoods' )}",
                    type: 'POST',
                    data: {'id': id},
                    dataType: 'json',
                    success: function (json) {
                        if (json.code == 'success') {
                            dialog.html('');
                            div.remove();
                            var divNumber = form.children('div').length;
                            if (divNumber == 0) {
                                var html = '<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>您的购物车为空</span></div>';
                                form.html(html);
                                $('#remove1').remove();
                                $('#remove2').remove();
                                $('.cart-btn').attr('disabled', 'disabled')
                            }
                        }
                    }
                });
            });
        });

        /* 提交购物车表单 */
        $('.cart-btn').click(function () {
          document.getElementById("car-goods").submit();
        });

        /* 全选商品 */
        function select_all(input) {
            var inputs = $('#car-goods').find('input');
            var total_price = $('.total-price');
            var val = $(input).val();
            var a = $('#check_a');
            var b = $('#check_b');
            var total_number = $('.total-number');
            if ($(input).is(':checked')) {
                inputs.prop('checked', 'checked');
                if (val == 'Y') {
                    b.prop('checked', 'checked');
                } else {
                    a.prop('checked', 'checked');
                }
            } else {
                inputs.removeAttr('checked');
                total_price.text('0');
                total_number.text('0');
                if (val == 'Y') {
                    b.removeAttr('checked');
                } else {
                    a.removeAttr('checked');
                }
            }
            var jk = 0;
            var zs = 0;
            $('.splistbottom').each(function(){
                if($(this).find('.up').find('input').is( ':checked' )){
                    var g = parseInt($(this).find('.down').find('.up-right').find('input').val());
                    var h =parseInt($(this).find('.down').find('.up-right').find('input').attr('data-dj'));
                    var k = g*h;
                    jk = jk+g;
                    zs = zs+k;
                }
            })
            total_price.text(zs);
            total_number.text(jk);
        }
    </script>
@endsection