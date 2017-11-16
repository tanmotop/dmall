@extends('layouts.app')

@section('styles')
	<link href="/assets/css/zc.css" rel="stylesheet" />
	<link href="/plugins/bootstrap/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
@endsection

@section('content')
	<div class="zc-body">
        <div class="body-top">
            <div style="margin-top: 5px;margin-left: 10px;" class="top-left">
                <a href="{{ route('login') }}">
                    <i style="font-size: 22px;margin-left: 5px;color: #b3b3b3;" class="fa fa-chevron-left"></i>
                </a>
            </div>
            <div style="margin-top: 8px;margin-right: 10px;" class="top-right">
                <a href="{{ route('home') }}">
                    <i style="font-size: 26px;margin-left: -5px;color: #b3b3b3;" class="fa fa-home"></i>
                </a>
            </div>
        </div>
        <div class="zc-bottom">
            <form id="register_form">
                <ul>
                    {{ csrf_field() }}
                    <li>邀请码：<input type="text" name="invitation_code" placeholder="将检测可用性" id="invitation_code"></li>
                    <li>用户名：<input type="text" name="username" placeholder="数字与字母的组合并且至少三位"></li>
                    <li>登录密码：<input type="password" name="password"></li>
                    <li>确认密码：<input type="password" name="repasswd"></li>
                    <li>真实姓名：<input type="text" name="realname"></li>
                    <li>身份证号：<input type="text" name="id_card_num" placeholder="身份证为18位"></li>
                    <li>邮箱：<input type="text" name="email" placeholder="请输入正确的邮箱格式"></li>
                    <li>微信号：<input type="text" name="wechat"></li>
                    <li>手机：<input type="text" name="phone" placeholder="手机号为11位"></li>
                    <li class="notice" style="height: 30px;text-align: center;font-size: 1.1em;color: red;display: none;"></li>
                </ul>
            </form>
        </div>

        <div class="btn" style="display: block">
            <button class="style btn-register">完成注册</button>
        </div>
    </div>
    <br/><br/>
    <div id="register_dialog">

    </div>
@endsection

@section('scripts')
<script>
	/* 邀请码可用性验证 */
    $( '#invitation_code' ).change( function (){
        var value = $( this ).val();
        var notice = $( '.notice' );
        if( value != '' ){
            $.ajax( {
                url: '{{ route('auth_check_code') }}',
                type: 'POST',
                data: {
                    code: value,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function ( json ) {
                    if( json.code == '10000' ){
                        $( '.btn' ).show();
                        notice.css( {'display': 'block', 'color': 'green' } ).html( json.msg );
                    }else{
                        $( '.btn' ).hide();
                        notice.css( {'display': 'block', 'color': 'red' } ).html( json.msg );
                    }
                }
            } );
        }else{
            notice.css( 'display', 'block' ).html( '请输入邀请码！' );
        }
    } );

    /* 注册验证 */
    $( '.btn-register' ).click( function (){
        var notice = $( '.notice' );
        $.ajax( {
            url: "{{ route('register_submit') }}",
            type: 'POST',
            data: $( '#register_form' ).serialize(),
            dataType: 'json',
            success: function ( json ) {
                if( json.code == '10000' ){
                    notice.css( {'display': 'none'});
                    var html = '<div class="modal fade in" tabindex="-1" role="dialog" id="MyShare" aria-hidden="false" style="display: block;">' +
                            '<div class="modal-dialog">' +
                            '<div class="out-zx">' +
                            '<img src="/assets/img/icon_@2x_28.png">' +
                            '<p>注册成功</p>' +
                            '<div class="change-btn">' +
                            '<button type="button" class="left-btn btn-close">我知道了</button>' +
                            '<button type="button" class="right-btn btn-login">立即登录</button>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="modal-back fade in"></div>';
                    $( '#register_dialog' ).html( html );

                    /* 关闭模态框 */
                    $( '.btn-close' ).click( function () {
                        $( '#register_dialog' ).html( '' );
                        location = location.href;
                    } );

                    /* 去登录页 */
                    $( '.btn-login' ).click( function () {
                        window.location.href = "{{ route('login') }}?relogin=1";
                    } );
                }else{
                    notice.css( {'display': 'block', 'color': 'red' } ).html( json.msg );
                }
            },
            error: function(json) {
                var errors = json.responseJSON.errors;
                for (i in errors) {
                    notice.css( {'display': 'block', 'color': 'red' } ).html(errors[i][0]);
                    break;
                }
            }
        } );
    } );
</script>
@endsection