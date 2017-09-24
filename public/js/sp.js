/* 商品图片路径 */
var viewPath = "<?php echo C( 'IMAGE_URL' ); ?>" + "goods/";
/* 定义总的加载的商品个数 */
totalNumber = 0;
/* 定义每次加载的商品个数 */
singleNumber = 3; // （只能修改此处）
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
    $.ajax( {
        url: "{:U( 'Goods/getGoodsList' )}",
        type: 'POST',
        data: { 'cat_id': 'all' },
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
                                '<input name="cart[' + goods + '][check]" value="Y" onclick="add_goods( this )" type="checkbox" class="chk_1" id="check_a' + eachNumber + '">' +
                                '<label for="check_a' + eachNumber + '"></label>' +
                                '</div>' +
                                '<h4>' + v.goods_name + '</h4>' +
                                '<div class="up-right">' +
                                '<img onclick="reduce_number( this );" src="__PUBLIC__/Home/default/img/icon_@2x_07.gif">' +
                                '<input name="cart[' + goods + '][goods]" onchange="change_number( this, ' + v.goods_number + ' );" type="number" value="1"/>' +
                                '<img onclick="add_number( this, ' + v.goods_number + ' );" src="__PUBLIC__/Home/default/img/icon_@2x_07.gif">' +
                                '</div>' +
                                '</div>' +
                                '<div class="down">' +
                                '<div class="down-left">' +
                                '<img src="' + viewPath + v.mid_logo + '">' +
                                '</div>' +
                                '<div class="down-right">' +
                                '<ul>' +
                                '<li><p>零售价：<em>￥' + v.goods_price + '</em></p></li>' +
                                '<li><p>购买价：<em>￥' + v.level_price + '</em></p></li>';
                            if( v.gaData != '' ){
                                $.each( v.gaData, function ( k1, v1 ){
                                    html += '<li><p>' + v1.attr_name + '：<span class="active-kw">' + v1.attr_value + '</span></p></li>';
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
                        html += '<div align="center" class="sp-list-bottom"><span>没有更多商品了</span></div>';
                        status = 'false';
                    }
                }else{
                    html = '<div align="center" class="sp-list-bottom"><span>没有相应的商品</span></div>';
                }
                $( '#goods-list' ).html( html );
            }
        }
    } );
    loaded();
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

    $( '.sp-list-top' ).find( 'li span' ).removeClass( 'sp-list-active' );
    $( span ).addClass( 'sp-list-active' );
    var list = $( '#goods-list' );
    var spinner = '<div class="sk-spinner sk-spinner-double-bounce">' +
        '<div class="sk-double-bounce1"></div>' +
        '<div class="sk-double-bounce2"></div></div>';
    list.html( spinner );
    $.ajax( {
        url: "{:U( 'Goods/getGoodsList' )}",
        type: 'POST',
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
                                '<input name="cart[' + goods + '][check]" value="Y" onclick="add_goods( this )" type="checkbox" class="chk_1" id="check_a' + eachNumber + '">' +
                                '<label for="check_a' + eachNumber + '"></label>' +
                                '</div>' +
                                '<h4>' + v.goods_name + '</h4>' +
                                '<div class="up-right">' +
                                '<img onclick="reduce_number( this );" src="__PUBLIC__/Home/default/img/icon_@2x_07.gif">' +
                                '<input name="cart[' + goods + '][goods]" onchange="change_number( this, ' + v.goods_number + ' );" type="number" value="1"/>' +
                                '<img onclick="add_number( this, ' + v.goods_number + ' );" src="__PUBLIC__/Home/default/img/icon_@2x_07.gif">' +
                                '</div>' +
                                '</div>' +
                                '<div class="down">' +
                                '<div class="down-left">' +
                                '<img src="' + viewPath + v.mid_logo + '">' +
                                '</div>' +
                                '<div class="down-right">' +
                                '<ul>' +
                                '<li><p>零售价：<em>￥' + v.goods_price + '</em></p></li>' +
                                '<li><p>购买价：<em>￥' + v.level_price + '</em></p></li>';
                            if( v.gaData != '' ){
                                $.each( v.gaData, function ( k1, v1 ){
                                    html += '<li><p>' + v1.attr_name + '：<span class="active-kw">' + v1.attr_value + '</span></p></li>';
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
                        html += '<div align="center" class="sp-list-bottom"><span>没有更多商品了</span></div>';
                        status = 'false';
                    }
                }else{
                    html = '<div align="center" class="sp-list-bottom"><span>没有相应的商品</span></div>';
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
                    '<input name="cart[' + goods + '][check]" value="Y" onclick="add_goods( this )" type="checkbox" class="chk_1" id="check_a' + eachNumber + '">' +
                    '<label for="check_a' + eachNumber + '"></label>' +
                    '</div>' +
                    '<h4>' + v.goods_name + '</h4>' +
                    '<div class="up-right">' +
                    '<img onclick="reduce_number( this );" src="__PUBLIC__/Home/default/img/icon_@2x_07.gif">' +
                    '<input name="cart[' + goods + '][goods]" onchange="change_number( this, ' + v.goods_number + ' );" type="number" value="1"/>' +
                    '<img onclick="add_number( this, ' + v.goods_number + ' );" src="__PUBLIC__/Home/default/img/icon_@2x_07.gif">' +
                    '</div>' +
                    '</div>' +
                    '<div class="down">' +
                    '<div class="down-left">' +
                    '<img src="' + viewPath + v.mid_logo + '">' +
                    '</div>' +
                    '<div class="down-right">' +
                    '<ul>' +
                    '<li><p>零售价：<em>￥' + v.goods_price + '</em></p></li>' +
                    '<li><p>购买价：<em>￥' + v.level_price + '</em></p></li>';
                if( v.gaData != '' ){
                    $.each( v.gaData, function ( k1, v1 ){
                        html += '<li><p>' + v1.attr_name + '：<span class="active-kw">' + v1.attr_value + '</span></p></li>';
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
            html += '<div align="center" class="sp-list-bottom"><span>没有更多商品了</span></div>';
            status = 'false';
        }
        setTimeout( function (){
            list.children( '.goods-last-notice' ).remove();
            list.append( html );
        }, 1000 );
    }
}

/* 逐个减少商品数量 */
function reduce_number( img ){
    var input = $( img ).next();
    var val = $( input ).val();
    if( val > 1 ){
        $( input ).val( val - 1 );
    }
}

/* 逐个增加商品数量 */
function add_number( img, goods_number ){
    var input = $( img ).prev();
    var val = $( input ).val();
    if( val < goods_number ){
        $( input ).val( Number(val) + Number(1) );
    }
}

/* 手动输入商品数量 */
function change_number( input, goods_number ) {
    var val = $( input ).val();
    if( val == '' || val < 1 ){
        $( input ).val( 1 );
    }
    if( val > goods_number ){
        $( input ).val( goods_number );
    }
}

/* 搜索商品 */
function goods_search( a ) {
    /* 获取关键字 */
    var val = $( a ).next().val();
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

    var list = $( '#goods-list' );
    var spinner = '<div class="sk-spinner sk-spinner-double-bounce">' +
        '<div class="sk-double-bounce1"></div>' +
        '<div class="sk-double-bounce2"></div></div>';
    list.html( spinner );
    $.ajax( {
        url: "{:U( 'Goods/getGoodsList' )}",
        type: 'POST',
        data: { 'cat_id': catgory_id, 'keywords': val },
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
                                '<input name="cart[' + goods + '][check]" value="Y" onclick="add_goods( this )" type="checkbox" class="chk_1" id="check_a' + eachNumber + '">' +
                                '<label for="check_a' + eachNumber + '"></label>' +
                                '</div>' +
                                '<h4>' + v.goods_name + '</h4>' +
                                '<div class="up-right">' +
                                '<img onclick="reduce_number( this );" src="__PUBLIC__/Home/default/img/icon_@2x_07.gif">' +
                                '<input name="cart[' + goods + '][goods]" onchange="change_number( this, ' + v.goods_number + ' );" type="number" value="1"/>' +
                                '<img onclick="add_number( this, ' + v.goods_number + ' );" src="__PUBLIC__/Home/default/img/icon_@2x_07.gif">' +
                                '</div>' +
                                '</div>' +
                                '<div class="down">' +
                                '<div class="down-left">' +
                                '<img src="' + viewPath + v.mid_logo + '">' +
                                '</div>' +
                                '<div class="down-right">' +
                                '<ul>' +
                                '<li><p>零售价：<em>￥' + v.goods_price + '</em></p></li>' +
                                '<li><p>购买价：<em>￥' + v.level_price + '</em></p></li>';
                            if( v.gaData != '' ){
                                $.each( v.gaData, function ( k1, v1 ){
                                    html += '<li><p>' + v1.attr_name + '：<span class="active-kw">' + v1.attr_value + '</span></p></li>';
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
                        html += '<div align="center" class="sp-list-bottom"><span>没有更多商品了</span></div>';
                        status = 'false';
                    }
                }else{
                    html = '<div align="center" class="sp-list-bottom"><span>没有相应的商品</span></div>';
                }
                list.html( html );
            }
        }
    } );
}

/* 添加商品 */
function add_goods( input ) {
    if( $(input).is(':checked') ){
        var html = '';
    }

}

/* 提交购物车表单 */
$( '.cart-btn' ).click( function () {
    document.getElementById("cart_form").submit();
} );
