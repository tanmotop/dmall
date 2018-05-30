@extends('layouts.app')

@section('styles')
    <!-- bootstrap开始 -->
    <link href="/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/plugins/bootstrap/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/assets/css/login.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="login-body">
        <div class="login-body-contnet">
            <div class="sby-loginName">
                <img style="border-radius: 10px;" src="/assets/img/logo.jpg">
                <form id="login_form">
                    {{ csrf_field() }}
                    <input style="font-size: 13px;" type="text" name="username" id="username" placeholder="账号 / 用户名 / 邮箱 / 微信号 / 手机">
                    <input style="font-size: 13px;" type="password" name="password" id="password" placeholder="请输入密码">
                </form>

                <div style="margin:0;">
                    <label>
                        <div class="zh-check">
                            <input type="checkbox" class="chk_1" id="remember">
                            <label for="remember" style="margin-bottom: -6%"></label>
                            <span>记住密码</span>
                        </div>
                    </label>
                </div>

                <button style="width: 60%;height: 43px" class="style btn-login" value="" onclick="login()">登录</button>
                <div style="margin-top: 10px;margin-right: -20%;">
                    <span style="color: white;font-family: '微软雅黑'">没有账号？</span> <a style="color: #288EC8;font-family: '微软雅黑';padding-bottom:1px; border-bottom:1px solid #288EC8" href="{{route('contract')}}">立即注册</a>
                </div>
            </div>
        </div>

    </div>
@endsection


@section('scripts')
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/plugins/jq-cookie/jquery.cookie.js"></script>
    <script src="/plugins/jq-cookie/jquery.base64.js"></script>
    <script type="text/javascript">
        function login()
        {
            var username = $('#username').val();
            var password = $('#password').val();
            if (!username) {
                alert('账号信息不能为空');
                return false;
            }
            if (!password) {
                alert('密码不能为空');
                return false;
            }
            if($('#remember').is(":checked")) {
                $.cookie('name',username,{ expires: 7 });
                $.cookie('password',$.base64.encode(password),{ expires: 7 });
            }
            else {
                $.cookie('name','',{ expires: -1 });
                $.cookie('password','',{ expires: -1 });
            }
            var _token = '{{ csrf_token() }}';
            $.post('{{ route('login_submit') }}', {
                username: username,
                password: password,
                _token: _token
            }, function(response) {
                if (response == 1) {
                    location = '/';
                } else {
                    alert('账号或密码错误');
                }
            })
        }
        $('document').ready(function()
        {
            if($.cookie('name') && $.cookie('password')){
                $('#username').val($.cookie('name'));
                $('#password').val($.base64.decode($.cookie('password')));
                $('#remember').prop('checked',true);
            }
        });
    </script>
@endsection