@extends('layouts.app')

@section('styles')
	<link href="/assets/css/sp.css" rel="stylesheet"/>
    <link href="/plugins/bootstrap/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <style>
        .up-right input {
            margin-left: 4px;
            margin-right: 4px;
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
            width: 1.6em;
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
            <div style="margin-top: 8px;margin-right: 8px;" class="top-right">
                <a href="{{ route('home') }}">
                    <i style="font-size: 26px;margin-left: -5px;color: #b3b3b3;" class="fa fa-home"></i>
                </a>
            </div>
        </div>
        
        @if (count($goodsList))
        <div id="remove1" style="height: 30px;" class="sp-list-bottom">
            <div style="margin-top: 4px;margin-left: 6px;" class="zh-check">
                <input value="Y" type="checkbox" class="chk_1 check-all" id="check_all1">
                <label for="check_all1" class="select-all-btn"></label>
            </div>
            <h4 style="margin-top: 6px;">选择全部商品</h4>
        </div>
        @endif

        @forelse($goodsList as $goods)
            <div class="sp-list-bottom splistbottom cart-item" data-attr-id="{{ $goods->attr_id }}" data-cart-id="{{ $goods->cart_id }}">
                <div class="up">
                    <div class="zh-check">
                        <input value="Y" type="checkbox" class="chk_1" id="check_{{ $goods->attr_id }}">
                        <label for="check_{{ $goods->attr_id }}" class="select-btn"></label>
                    </div>
                    <h4>{{ $goods->name }}</h4>
                    <div class="up-right-delet">
                        <button data-id="{{ $goods->cart_id }}" type="button" class="sc-btn del-goods">删除</button>
                    </div>
                </div>
                <div class="down">
                    <div class="down-left">
                        <img src="{{env('APP_URL') . '/uploads/' . $goods->logo}}" alt="商品logo" />
                    </div>
                    <div class="down-right">
                        <ul>
                            <li>
                                <p>购买价：<em>￥{{ $goods->buy_price }}</em></p>
                                <div class="mycart-number">
                                    <div class="up-right">
                                        <img class="reduce-btn" src="/assets/img/goods_del.png">
                                        <input value="{{ $goods->count }}" class="input-numer" value="1" data-buy-price="{{ $goods->buy_price }}" data-stock="{{$goods->stock}}"/>
                                        <img class="add-btn" src="/assets/img/goods_add.png">
                                    </div>
                                </div>
                            </li>
                            <li><p>规格：<span class="active-kw">{{ $goods->attr_name }}</span></p></li>
                            <li><p>PV值：<i>{{ $goods->pv }}</i></p></li>
                            <li><p>产品编号：<i>{{ $goods->goods_id }}</i></p></li>
                        </ul>
                    </div>
                </div>
            </div>
        @empty
            <div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>您的购物车为空</span></div>
        @endforelse
        
        @if (count($goodsList))
        <div id="remove2" style="height: 30px;" class="sp-list-bottom">
            <div style="margin-top: 4px;margin-left: 8px;" class="zh-check">
                <input value="Y" type="checkbox" class="chk_1 check-all" id="check_all2">
                <label for="check_all2" class="select-all-btn"></label>
            </div>
            <h4 style="margin-top: 6px;">选择全部商品</h4>
        </div>
        @endif
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
            <button style="line-height: 0;" class="cart-btn submit-btn" type="button">提交订单</button>
        </div>
    </footer>
    <div id="cart_dialog" style="display: none">
        <div class="modal fade in" tabindex="-1" role="dialog" id="MyShare" aria-hidden="false" style="display: block;">' +
            <div class="modal-dialog">
                <div class="out-zx">
                    <i style="color: red" class="fa fa-exclamation-circle fa-5x"></i>
                    <p>删除提示</p>
                    <div class="change-btn">
                        <button style="float: none" type="button" class="left-btn btn-close">再想想</button>
                        <button style="float: none;" type="button" class="right-btn btn-login btn-sure-del" data-del-id="">确认删除</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-back fade in"></div>
    </div>
@endsection

@section('scripts')
    <script>
        var selectGoods = {};
        var $totalNumber = $('.total-number');
        var $totalPrice  = $('.total-price');
        var totalNumber = 0;
        var totalPrice  = 0;
    </script>
    <script type="text/javascript">
        // 删除事件
        $('.del-goods').on('click', function () {
            $('#cart_dialog').show();
            var cartId = $(this).attr('data-id')
            $('.btn-sure-del').attr('data-del-id', cartId)
        })
        // 关闭弹窗事件
        $('.btn-close').on('click', function () {
            $('#cart_dialog').hide();
            $('.btn-sure-del').attr('data-del-id', '')
        });
        // 确定删除事件
        $('.btn-sure-del').on('click', function() {
            var cartId = $(this).attr('data-del-id')
            $(this).attr('data-del-id', '')
            $.post('{{route('carts_del')}}', {
                cart_id: cartId,
                _token: '{{ csrf_token() }}'
            }, function(response) {
                if (response.code == 10000) {
                    $('.cart-item[data-cart-id="'+cartId+'"]').remove();
                } else {
                    alert('删除失败');
                }
                $('#cart_dialog').hide();
            })
        })
        // 全选框选择事件
        $('#check_all1,#check_all2').on('change', function() {
            var checkboxLength = $('.cart-item').find('input[type=checkbox]').length
            var checkedCount = 0
            var $checkbox = $('.cart-item').find('input[type=checkbox]');
            $checkbox.each(function(item) {
                if ($(this).is(':checked')) ++checkedCount
            })
            if (checkedCount == checkboxLength) {
                $checkbox.removeAttr('checked')
                $('#check_all1,#check_all2').removeAttr('checked')
            } else {
                $checkbox.prop('checked', 'checked')
                $('#check_all1,#check_all2').prop('checked', 'checked')
            }
            changeTotalNumber_Price()
        });
        // 单项选择事件
        $('.cart-item').on('change', 'input[type=checkbox]', function() {
            changeTotalNumber_Price()
            changeCheckbox()
        })
        // 减少点击事件
        $('.cart-item').on('click', '.reduce-btn', function() {
            var $input = $(this).next();
            var newValue = parseInt($input.val()) - 1
            $input.val(newValue < 1 ? 1 : newValue)
            if ($(this).parents('.cart-item').find('input[type=checkbox]').is(':checked')) {
                changeTotalNumber_Price();
            }
        });
        // 减少点击事件
        $('.cart-item').on('click', '.add-btn', function() {
            var $input = $(this).prev();
            var newValue = parseInt($input.val()) + 1
            var stock = $input.attr('data-stock')
            $input.val(newValue > stock ? stock : newValue)
            if ($(this).parents('.cart-item').find('input[type=checkbox]').is(':checked')) {
                changeTotalNumber_Price();
            }
        });
        // 输入事件
        $('.cart-item').on('change', '.input-numer', function() {
            var stock = $(this).attr('data-stock');
            var value = parseInt($(this).val());
            // 判断合法值
            if (isNaN(value) || value <= 0) value = 1;
            if (value > stock) value = stock;
            $(this).val(value)
            if ($(this).parents('.cart-item').find('input[type=checkbox]').is(':checked')) {
                changeTotalNumber_Price();
            }
        })
        // 提交订单事件
        $('.submit-btn').on('click', function() {
            var selectGoods = {}, length = 0
            $('.cart-item').each(function(i, item) {
                if ($(item).find('input[type=checkbox]').is(':checked')) {
                    var cartId = $(item).attr('data-cart-id');
                    var count  = $(item).find('.input-numer').val();
                    selectGoods[cartId] = count
                    ++length
                }
            })
            if (length == 0) alert('您还没有选择商品');
            $.post('{{ route('carts_prepare') }}', {
                '_token': '{{ csrf_token() }}',
                'selectGoods': JSON.stringify(selectGoods)
            }, function(response) {
                if (response.code == 10000) {
                    location = '{{ route('orders_prepare') }}';
                } else {
                    alert('部分商品可能库存不足，请重新选择');
                }
            })
        })
    </script>

    <script>
        function changeCheckbox()
        {
            var $checkAll_A = $('#check_all1')
            var $checkAll_B = $('#check_all2')
            var checkboxLength = $('.cart-item').find('input[type=checkbox]').length
            var checkedCount = 0
            $('.cart-item').find('input[type=checkbox]').each(function(item) {
                if ($(this).is(':checked')) ++checkedCount
            })
            if (checkedCount == checkboxLength) {
                $('#check_all1,#check_all2').prop('checked', 'checked')
            } else {
                $('#check_all1,#check_all2').removeAttr('checked')
            }
        }
        function changeTotalNumber_Price()
        {
            totalNumber = 0;
            totalPrice = 0;
            $('.cart-item').each(function() {
                var $item = $(this)
                if ($item.find('.select-btn').prev().is(':checked')) {
                    var price = parseFloat($item.find('.input-numer').attr('data-buy-price'))
                    var count = parseInt($item.find('.input-numer').val())
                    totalNumber += count
                    totalPrice += price * count
                }
            })
            $totalNumber.text(totalNumber)
            $totalPrice.text(totalPrice)
        }
    </script>
@endsection