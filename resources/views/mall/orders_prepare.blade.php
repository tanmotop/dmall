@extends('layouts.app')

@section('styles')
    <link href="/assets/css/order.css" rel="stylesheet" />
    <link href="/plugins/ickeck/flat/green.css" rel="stylesheet">
    <link href="/plugins/bootstrap/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <style type="text/css">
        .zc-bottom ul li input{
            width: 70%;
        }
        .zc-bottom ul li label{
            color: black;
            font-size: 14px;
        }
    </style>
@endsection

@section('content')
<div class="zc-body">
    <div class="body-top">
        <div style="margin-top: 5px;margin-left: 10px;" class="top-left">
            <a href="{{ route('carts') }}">
                <i style="font-size: 22px;margin-left: 5px;color: #b3b3b3;" class="fa fa-chevron-left"></i>
            </a>
        </div>
        <div style="margin-top: 8px;margin-right: 10px;" class="top-right">
            <a href="{{ route('home') }}">
                <i style="font-size: 26px;margin-left: -5px;color: #b3b3b3;" class="fa fa-home"></i>
            </a>
        </div>
    </div>



    <form id="order_form">
        <div class="zc-bottom">
            <ul>
                <li>
                    <label>搜索客户：</label>
                    <input class="info" type="text" value="" id='search' placeholder="客户太多可以搜索后再选择">
                </li>
                <li>
                    <label>我的客户：</label>
                    <select id="select" style="width: 72%">
                        <option value="">请选择</option>
                    </select>
                </li>
                <li><label style="font-size: 0.7em;color: red">* 填写收件人、手机、地址（必须包含省市区）三个信息需用顿号"、"隔开</label></li>
                <li>
                    <label>收件人信息：</label>
                    <input class="info" type="text" name="info" placeholder="收货人、手机、详细地址">
                </li>
                <li>
                    <label>收件地区：</label>
                    <select required name="province_id" id="province" data-type="Y">
                        <option value="">请选择</option>
                    </select>
                    <select required name="city_id" id="city">
                        <option value="">请选择</option>
                    </select>
                    <select required name="area_id" id="district">
                        <option value="">请选择</option>
                    </select>
                </li>
                <li>
                    <label>收件人：</label>
                    <input style="background: white" class="user_name" type="text" name="user_name">
                </li>
                <li>
                    <label>手机：</label>
                    <input style="background: white"  class="user_phone" type="text" name="user_phone">
                </li>
                <li>
                    <label>详细地址：</label>
                    <input style="background: white"  class="user_address" type="text" name="user_address">
                </li>
                <li><label style="font-size: 0.7em;color: red">* 选择顺丰快递详细地址必须填写完整的省市区</label></li>
                <li>
                    <label>备注：</label>
                    <input type="text" name="remarks" class="remarks">
                </li>
                <li>
                    <label>运费：</label>
                    <input class="freight" readonly type="text" name="freight" style="width: 50%">
                    <select class="courier_id" required name="courier_id" id="courier_id">
                        @foreach($couriers as $id => $courier)
                            <option value="{{ $id }}">{{ $courier }}</option>
                        @endforeach
                    </select>
                </li>
                <li>
                    <label>订单金额：</label>
                    <input class="total_price" data-value="{{$totalInfo['price']}}" value="{{$totalInfo['price']}}" readonly type="text" name="total_price">
                </li>
                <li class="notice" style="height: 30px;text-align: center;font-size: 14px;color: red;display: none;"></li>
            </ul>
            <input class="total_pv" type="hidden" name="total_pv" value="{{ $totalInfo['pv'] }}" />
        </div>
        <div align="center">
            <label>
                <input style="background: white" class="post-way" value="" type="checkbox"/> <span>到店自提</span>
            </label>
            <label style="margin-left: 10px;">
                <input style="background: white" name="save_address" class="save_address" type="checkbox" value="Y" /> <span>把信息存储到我的客户资料</span>
            </label>
        </div>
    </form>

    <div class="btn">
        <button type="button" class="style btn-submit">确 认</button>
    </div>
</div>
<br/><br/>
<div id="add_dialog">

</div>
@endsection

@section('scripts')
<script src="/plugins/bootstrap/js/jquery-1.12.4.min.js"></script>
<script src="/plugins/ickeck/icheck.min.js"></script>
<script type="text/javascript">
    var customers = {}; // 记录客户信息
</script>
{{-- 城市三级联动 --}}
<script type="text/javascript">
    $(function() {
        regions(1, 1);
        getCustomers();
    });

    /* 信息黏贴 */
    $( "input[name='info']" ).change( function (){
        var info = $( this ).val();
        info = info.split( '、' );
        $( '.user_name' ).val( info[0] );
        $( '.user_phone' ).val( info[1] );
        $( '.user_address' ).val( info[2] );
    } );

    function regions(pid, level)
    {
        resetOptions(level);
        $.get('{{ route('address_regions') }}', {pid: pid}, function (response) {
            $select = level == 1 ? $('#province') : level == 2 ? $('#city') : $('#district');
            for (var i in response) {
                $select.append('<option value="'+i+'">'+response[i]+'</option>');
            }
        })
    }
    // 重置选项
    function resetOptions(level)
    {
        if (level == 2) {
            $('#city').html('<option value="">请选择</option>');
            $('#district').html('<option value="">请选择</option>');
        } else if (level == 3) {
            $('#district').html('<option value="">请选择</option>');
        }
    }
    // 获取运费
    function getFreight(courierId, regionId)
    {
        if (courierId == 0 || regionId == 0)
            return;

        $.get('{{ route('address_freights') }}', {
            courier_id:courierId,
            region_id:regionId,
            weight: '{{ $totalInfo['weight'] }}'
        }, function(response){
            var $freight = $('.freight');

            if (response.code == 10000) {
                $freight.attr('data-value', response.freight);
                if (!$('.post-way').is(':checked')) {
                    $freight.val(response.freight)
                } else {
                    $freight.val(0)
                }
                $('.total_price').val(parseFloat($('.total_price').attr('data-value')) + parseFloat($freight.val()))
            }
            else {
                $('.total_price').val(parseFloat($('.total_price').attr('data-value')));
                $freight.attr('data-value', '');
                $freight.val('');
                $("select[name='courier_id']").val(0);
                alert('无法送达')
            }
        })
    }

    function getCustomers()
    {
        $.get('{{ route('address_customers') }}', {}, function(response) {
            if (!response.length) return false;
            var html = '';
            for(var i in response) {
                var item = response[i];
                customers[item.id] = item;
                html += '<option value="'+item.id+'">'+item.name+'</option>';
            }
            $('#select').append(html)
        })
    }
    $('#province').on('change', function() {
        var pid = $(this).val();
        var courierId = $("select[name='courier_id']").val();
        regions(pid, 2);
        getFreight(courierId, pid);
    });

    $('#city').on('change', function() {
        var pid = $(this).val();
        regions(pid, 3);
    });

    $("select[name='courier_id']").on('change', function () {
        var courierId = $(this).val();
        var provinceId = $('#province').val();
        getFreight(courierId, provinceId);
    });

    // 选择客户
    $('#select').on('change', function () {
        var id = $(this).val();
        var customer = customers[id];
        setOptionsByCustomerInfo(customer.province_id, customer.city_id, customer.area_id);
        $('.user_name').val(customer.name);
        $('.user_phone').val(customer.phone);
        $('.user_address').val(customer.address);
        var courierId = $("select[name='courier_id']").val();
        getFreight(courierId, customer.province_id)
    });

    // 到店自提
    $('.post-way').on('change', function() {
        var $freight = $('.freight')
        if (!$freight.attr('data-value')) {
            return ;
        }
        if (!$(this).is(':checked')) {
            $freight.val($freight.attr('data-value'));
        } else {
            $freight.val(0);
        }
        $('.total_price').val(parseFloat($('.total_price').attr('data-value')) + parseFloat($freight.val()))
    })

    function setOptionsByCustomerInfo(provinceId, cityId, areaId)
    {
        // 设置省份
        $('#province').val(provinceId)
        // 设置城市
        $.get('{{ route('address_regions') }}', {pid: provinceId}, function (response) {
            $('#city').html('<option value="">请选择</option>');
            for (var i in response) {
                $('#city').append('<option value="'+i+'">'+response[i]+'</option>');
            }
            $('#city').val(cityId);
            // 设置地区
            $.get('{{ route('address_regions') }}', {pid: cityId}, function (response) {
                $('#district').html('<option value="">请选择</option>');
                for (var i in response) {
                    $('#district').append('<option value="'+i+'">'+response[i]+'</option>');
                }
                $('#district').val(areaId);
            })
        })
    }
    // 搜索客户
    $('#search').on('keyup', function() {
        var val = $(this).val();
        if (val == '') $('#select option').show();
        $('#select option').each(function(i, item) {
            var text = $(item).text();
            if (text.indexOf(val) != -1) {
                $(item).show();
            } else {
                $(item).hide();
            }
        });
    });
    // 提交订单
    $('.btn-submit').on('click', function() {
        $('.btn-submit').prop('disabled',true);
        var data = {
            user_province : $('#province').val(),
            user_city: $('#city').val(),
            user_area: $('#district').val(),
            user_name: $('.user_name').val(),
            user_phone: $('.user_phone').val(),
            user_address: $('.user_address').val(),
            freight: $('.freight').val(),
            courier_id: $('.courier_id').val(),
            total_price: $('.total_price').val(),
            total_pv: '{{ $totalInfo['pv'] }}',
            remarks: $('.remarks').val(),
            post_way: $('.post-way').is(':checked') ? 2 : 1,
            save_address: $('.save_address').is(':checked') ? 1 : 0,
            _token: '{{ csrf_token() }}'
        };
        var notice = $('.notice');
        $.ajax( {
            url: "{{ route('orders_submit') }}",
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function ( json ) {
                if(json.code == 10000){
                    var html = '<div class="modal fade in" tabindex="-1" role="dialog" id="MyShare" aria-hidden="false" style="display: block;">' +
                        '<div class="modal-dialog">' +
                        '<div class="out-zx">' +
                        '<i style="color: green" class="fa fa-check-circle fa-5x"></i>' +
                        '<p>下单成功！余额:'+ json.money + '</p>' +
                        '<div class="change-btn">' +
                        '<button style="margin-left: 0" type="button" class="right-btn btn-ok">确定</button>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="modal-back fade in"></div>';
                    $('#add_dialog').html(html);
                    $('.btn-submit').prop('disabled',false);
                }else if (json.code == 10003) {
                    var html = '<div class="modal fade in" tabindex="-1" role="dialog" id="MyShare" aria-hidden="false" style="display: block;">' +
                                '<div class="modal-dialog">' +
                                '<div class="out-zx">' +
                                '<i style="color: red" class="fa fa-close fa-5x"></i>' +
                                '<p>账户余额不足</p>' +
                                '<div class="change-btn">' +
                                '<button type="button" class="left-btn btn-close" style="margin-right:0;">我知道了</button>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="modal-back fade in"></div>';
                        $('#add_dialog').html( html );
                        $('.btn-submit').prop('disabled',false);
                } else {
                    notice.css( {'display': 'block', 'color': 'red' } ).html( json.msg );
                    $('.btn-submit').prop('disabled',false);
                }
            },
            error: function(json) {
                var errors = json.responseJSON.errors;
                for (i in errors) {
                    notice.css( {'display': 'block', 'color': 'red' } ).html(errors[i][0]);
                    break;
                }
                $('.btn-submit').prop('disabled',false);
            }
        });
    })
    /* 关闭模态框 */
    $('#add_dialog').on('click', '.btn-close', function () {
        $('#add_dialog').html('');
    })
    $('#add_dialog').on('click', '.btn-ok', function() {
        window.location.href = '{{ route('orders') }}'
    })
</script>
@endsection

