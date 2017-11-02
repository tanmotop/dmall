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
            width: {{count($cats)*100}}px;
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
                    <li id="cat_0">
                        <span onclick="javascript:location='{{ route('goods') }}'" @if($catId == 0) class="sp-list-active" @endif>全部</span>
                    </li>
                    @foreach($cats as $cat)
                        <li id="cat_{{$cat->id}}">
                            <span onclick="javascript:location='{{ route('goods', ['cat_id' => $cat->id]) }}'"  @if($catId == $cat->id) class="sp-list-active" @endif>{{ $cat->name}}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div id="goods-list">
            @forelse($goodsList as $goods)
                <div class="sp-list-bottom goods-item" data-attr-id="{{ $goods->attr_id }}">
                    <div class="up">
                        <div class="zh-check">
                            <input value="Y" type="checkbox" class="chk_1" id="check_{{ $goods->attr_id }}">
                            <label for="check_{{ $goods->attr_id }}" class="select-btn"></label>
                        </div>
                        <h4>{{ $goods->name }}</h4>
                        <div class="up-right">
                            <img class="reduce-btn" src="/assets/img/goods_del.png">
                            <input type="number" class="input-numer" value="1" data-buy-price="{{ $goods->user_prices['level_'.$myLevel] }}" data-stock="{{$goods->stock}}">
                            <img class="add-btn" src="/assets/img/goods_add.png">
                        </div>
                    </div>
                    <div class="down">
                        <div class="down-left">
                            <img src="{{env('APP_URL') . '/uploads/' . $goods->logo}}">
                        </div>
                        <div class="down-right">
                            <ul>
                                @foreach(array_reverse($userLevels, true) as $key => $levelName)
                                    <li><p>{{ $levelName }}价：￥{{ $goods->user_prices['level_'.$key] }}</p></li>
                                @endforeach
                                <li><p>零售价：<em><del>￥{{ $goods->price }}</del></em></p></li>
                                <li><p>购买价：<em>￥{{ $goods->user_prices['level_'.$myLevel] }}</em></p></li>
                                <li><p>PV值：<em>{{ $goods->pv }}</em></p></li>
                                <li><p>库存：<i>{{ $goods->stock }}</i></p></li>
                                <li><p>产品编号：<i>{{ $goods->id }}</i></p></li>
                            </ul>
                        </div>
                    </div>
                </div>
             @empty
                <div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>暂无数据</span></div>
            @endforelse
        </div>
        @if ($goodsList->currentPage() != $goodsList->lastPage())
            <div align="center" onclick="getMoreGoods()" class="sp-list-bottom tips"><span>上拉/点击获取更多邀请码</span></div>
        @endif
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
            <button style="line-height: 0;" class="cart-btn add-to-cart" type="button">加入购物车</button>
        </div>
    </footer>
@endsection

@section('scripts')
    <script type="text/javascript" src="/plugins/iscroll/iscroll.js"></script>
    <script type="text/javascript">
        $(function() {
            var myScroll = new IScroll('#wrapper', { eventPassthrough: true, scrollX: true, scrollY: false, preventDefault: false });
            myScroll.scrollToElement('#cat_{{$catPlace}}', true, true);
        })
    </script>
    <script type="text/javascript">
        var myLevel = {{ $myLevel }};
        var userLevels = $.parseJSON('{!! json_encode($userLevels) !!}');
        var selectGoods = {};
        var $totalNumber = $('.total-number');
        var $totalPrice  = $('.total-price');
        var totalNumber = 0;
        var totalPrice  = 0;

        /**
         * 选择
         */
        $('#goods-list').on('click', '.select-btn', function() {
            var $container = $(this).parents('.goods-item');
            var checked = $(this).prev().is(':checked')
            var $input = $container.find('.input-numer');
            var count = $input.val();
            var price = count * $input.attr('data-buy-price');
            changeTotalNumber_Price(!checked, count, price)
            changeSelectGoods(!checked, $container.attr('data-attr-id'), count)
        })

        /**
         * 添加
         */
        $('#goods-list').on('click', '.add-btn', function() {
            var $container = $(this).parents('.goods-item');
            var $input = $container.find('.input-numer');
            var stock = $input.attr('data-stock')
            if (parseInt($input.val()) - 0 + 1 > stock) {
                return false;
            }
            $input.val($input.val() - 0 + 1)
            if ($container.find('.select-btn').prev().is(':checked')) {
                changeTotalNumber_Price(true, 1, $input.attr('data-buy-price'))
                changeSelectGoods(true, $container.attr('data-attr-id'), $input.val())
            }
        })

        /**
         * 减少
         */
        $('#goods-list').on('click', '.reduce-btn', function() {
            var $container = $(this).parents('.goods-item');
            var $input = $container.find('.input-numer');
            if (parseInt($input.val()) <= 1) {
                return false;
            }
            $input.val($input.val() - 1)
            if ($container.find('.select-btn').prev().is(':checked')) {
                changeTotalNumber_Price(false, 1, $input.attr('data-buy-price'))
                changeSelectGoods(true, $container.attr('data-attr-id'), $input.val())
            }
        })

        /**
         * 手动输入值
         */
        $('#goods-list').on('change', '.input-numer', function() {
            var $container = $(this).parents('.goods-item');
            var stock = $(this).attr('data-stock');
            var value = parseInt($(this).val());
            $(this).val(value);
            var attrId = $container.attr('data-attr-id');
            // 判断合法值
            if (isNaN(value)) $(this).val(value = 1);
            if (value > stock) $(this).val(stock);
            if (value <= 0) $(this).val(1);
            // 选中状态需要更新selectGoods
            if ($container.find('.select-btn').prev().is(':checked')) {
                var selectCount = selectGoods['aid' + attrId]
                var changeCount = value - selectCount
                var changePrice = Math.abs(changeCount) * $(this).attr('data-buy-price')
                if (changeCount > 0) {
                    changeTotalNumber_Price(true, changeCount, changePrice)
                } else {
                    changeTotalNumber_Price(false, -changeCount, changePrice)
                }
            }
            changeSelectGoods(1, attrId, value)
        })

        $('.add-to-cart').on('click', function() {
            var length = 0;
            for(var i in selectGoods) ++length;
            if (length == 0) {
                alert('请选择商品');
                return false;
            }
            console.log(selectGoods)
            $.post('{{ route('goods_add_to_cart') }}', {
                '_token': '{{ csrf_token() }}',
                'selectGoods': JSON.stringify(selectGoods)
            }, function(response) {
                if (response.code == 10000) {
                    location = '{{ route('carts') }}'
                }
            });
        })

        /**
         * 修改总数
         * type: 1增加 0减少
         */
        function changeTotalNumber_Price(type, count, price)
        {
            if (type) {
                totalNumber += parseInt(count);
                totalPrice  += parseFloat(price);
            } else {
                totalNumber -= count;
                totalPrice  -= price;
            }
            $totalNumber.text(totalNumber)
            $totalPrice.text(totalPrice)
        }

        /**
         * 修改已选
         * type: 1增加 0减少
         */
        function changeSelectGoods(type, attrId, count)
        {
            if (type) {
                selectGoods[attrId] = count
            } else {
                delete selectGoods[attrId]
            }
        }
    </script>
    <script type="text/javascript">
        $(window).scroll(function() {
            if ($(document).scrollTop() >= $(document).height() - $(window).height()) {
                getMoreGoods();
            }
        });
        var currentPage = parseInt('{{ $goodsList->currentPage() }}')
        var lastPage = parseInt('{{ $goodsList->lastPage() }}')
        function getMoreGoods()
        {
            if (currentPage == lastPage) {
                return false;
            }
            $('.tips span').html('正在获取 <i class="fa fa-spinner fa-spin"></i>');
            $.get('{{ route('goods') }}', {
                page: currentPage + 1,
                dataType: 'json'
            }, function(json) {
                currentPage = json.current_page
                var data = json.data
                var html = ''
                for (var i in data) {
                    var item = data[i]
                    var buyPrice = item.user_prices['level_' + myLevel]
                    html += ""
                        + '<div class="sp-list-bottom goods-item" data-attr-id="' + item.attr_id + '">'
                        + '    <div class="up">'
                        + '        <div class="zh-check">'
                        + '            <input value="Y" type="checkbox" class="chk_1" id="check_' + item.attr_id + '">'
                        + '            <label for="check_' + item.attr_id + '" class="select-btn"></label>'
                        + '        </div>'
                        + '        <h4>海带条试吃装</h4>'
                        + '        <div class="up-right">'
                        + '            <img class="reduce-btn" src="/assets/img/goods_del.png">'
                        + '            <input type="number" class="input-numer" value="1" data-buy-price="'+buyPrice+'" data-stock="' + item.stock + '">'
                        + '            <img class="add-btn" src="/assets/img/goods_add.png">'
                        + '        </div>'
                        + '    </div>'
                        + '    <div class="down">'
                        + '        <div class="down-left">'
                        + '            <img src="/Public/Uploads/images/goods/logo/2017-05-24/mid_59252e977f2c1.jpg">'
                        + '        </div>'
                        + '        <div class="down-right">'
                        + '            <ul>'
                        + '                <li><p>零售价：<em><del>￥' + item.price + '</del></em></p></li>'
                        + '                <li><p>购买价：<em>￥' + buyPrice + '</em></p></li>'
                        + '                <li><p>规则名称：' + item.attr_name + '</p></li>'
                        + '                <li><p>PV值：<em>' + item.pv + '</em></p></li>'
                        + '                <li><p>库存：<i>' + item.stock + '</i></p></li>'
                        + '                <li><p>产品编号：<i>' + item.id + '</i></p></li>'
                        + '            </ul>'
                        + '        </div>'
                        + '    </div>'
                        + '</div>'
                }
                setTimeout(function() {
                    $('#goods-list').append(html)
                    if (json.current_page == json.last_page) {
                        $('.tips').remove();
                        $('#goods-list').append('<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有更多了</span></div>');
                    } else {
                        $('.tips span').html('上拉/点击获取更多邀请码');
                    }
                },500)
            });
        }
    </script>
@endsection