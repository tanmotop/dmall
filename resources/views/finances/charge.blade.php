@extends('layouts.app')

@section('styles')
    <link href="/assets/css/order_detail.css" rel="stylesheet" />
    <link href="/plugins/bootstrap/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">

    <style type="text/css">
        .sp-list-top select,input{
            border: none;
            border-radius: 3px;
            line-height: 3em;
            outline: none;
            width: 70%;
            /* margin-bottom: 10px; */
            font-size: 1em;
            color: #000;
            margin-left: 10px;
        }
        .zc-bottom img{
            height: 2rem;
        }
        .zc-bottom span{
            float: right;
            margin-right:2rem ;
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
            <div style="margin-top: 8px;margin-right: 10px;" class="top-right">
                <a href="{{ route('home') }}">
                    <i style="font-size: 26px;margin-left: -5px;color: #b3b3b3;" class="fa fa-home"></i>
                </a>
            </div>
        </div>

        <div class="sp-list-top">
            <ul>
                <li style="width: 50%;">
                    <select style="height: 30px;" required onchange="select_amount( this )">
                        <option value="">请选择</option>
                        @foreach($levels as $name => $money)
                            <option value="{{ $money }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </li>
                <li style="width: 50%;">
                    <input style="height: 100%" name="recharge_money" type="text" readonly placeholder="将自动计算充值金额"/>
                </li>
            </ul>
        </div>

        <div class="zc-bottom">
            <ul>
                <li style="font-size: 18px;height: 50px;font-weight: bold;padding-top: 20px;">付款方式</li>
                <li id="fk" style="height: 37px;" onclick="select_pay( this, 'wechat' )"><img src="/img/WeQR.jpg"/> <span class="check"><i class="fa fa-check"></i></span></li>
                <li style="height: 37px;" onclick="select_pay( this, 'alipay' )"><img src="/img/AliQR.jpg"/> <span class="check"></span></li>
                <li style="height: 37px;" onclick="select_pay( this, 'bank' )"><img src="/img/Bank.jpg"/><span class="check"></span></li>
            </ul>

        </div>

        <input type="hidden" name="recharge_mode" value="Y"/>

        <div class="btn">
            <button style="float: none;background: #00B700;" type="button" onclick="pay()" class="style btn-register">确认支付</button>
        </div>

    </div>

    <div id="wechat_dialog" style="display: none" class="pay_dialog">
        <div class="modal fade in" tabindex="-1" role="dialog" aria-hidden="false" style="display: block;">
            <div class="modal-dialog">
                <div style="margin-bottom: 20%;" class="out-zx">
                    <img style="width: 90%;height: 90%;border-radius: 0;" src="{{ config('filesystems.disks.admin.url') }}/{{ $payConfig->wechat_qr_code }}"/>
                    <div class="change-btn">
                        <button type="button" class="left-btn btn-close">关闭</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-back fade in"></div>
    </div>

    <div id="alipay_dialog" style="display: none;" class="pay_dialog">
        <div class="modal fade in" tabindex="-1" role="dialog" aria-hidden="false" style="display: block;">
            <div class="modal-dialog">
                <div class="out-zx">
                    <br/>
                    <div align="center">
                        <table>
                            <tr><th>开户人：</th><td>{{ $payConfig->alipay_user }}</td></tr>
                            <tr><th>会员名：</th><td>{{ $payConfig->alipay_name }}</td></tr>
                            <tr><th><span style="color: white;">。</span>账号：</th><td>{{ $payConfig->alipay_phone }}</td></tr>
                        </table>
                    </div>
                    <div class="change-btn">
                        <button type="button" class="left-btn btn-close">关闭</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-back fade in"></div>
    </div>

    <div id="bank_dialog" style="display: none;" class="pay_dialog">
        <div class="modal fade in" tabindex="-1" role="dialog" aria-hidden="false" style="display: block;">
            <div class="modal-dialog">
                <div class="out-zx">
                    <br/>
                    <div align="center">
                        <table>
                            <tr>
                                <th><span style="color: white;">。</span>开户人：</th>
                                <td>{{ $payConfig->bank_user }}</td>
                            </tr>
                            <tr>
                                <th>银行名称：</th>
                                <td>{{ $payConfig->bank_name }}</td>
                            </tr>
                            <tr>
                                <th>银行卡号：</th>
                                <td>{{ $payConfig->bank_card }}</td>
                            </tr>
                            <tr>
                                <th>开户银行：</th>
                                <td>{{ $payConfig->bank_address }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="change-btn">
                    <button type="button" class="left-btn btn-close">关闭</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-back fade in"></div>
    </div>

    <br/><br/>
@endsection

@section('scripts')
    <script type="text/javascript">
        /* 选择充值等级 */
        function select_amount( select ) {
            var amount = $( select ).val();
            var input = $( select ).parent().next().children();
            if( amount == '' ){
                input.val( '' );
            }else{
                input.val( amount );
            }
        }

        function pay(){
            $('#wechat_dialog').show();
            $('#bank_dialog').hide();
            $('#alipay_dialog').hide();
        }

        $( '.btn-close' ).click( function () {
            $( '.pay_dialog' ).hide();
        } );

        /* 选择支付方式 */
        function select_pay( li, type ){
            var span = $( li ).children( 'span' );
            var rh = $(document).height();
            var button = $( 'button.style' );
            $( 'span.check' ).html( '' );
            span.html( '<i class="fa fa-check"></i>' );
            button.hide();
            if( type == 'wechat' ){ // 微信支付
                button.show();
            }
            else{
                $('#' + type + '_dialog').show();
            }
        }
    </script>
@endsection