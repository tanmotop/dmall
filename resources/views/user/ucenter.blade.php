@extends('layouts.user')

@section('styles')
    <link href="/css/personal_center.css" rel="stylesheet"/>
    <link href="/plugins/bootstrap/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <style type="text/css">
        .modal-open{
            overflow: hidden;
        }
        .modal-open .modal {
            overflow-x: hidden;
            overflow-y: auto;
        }
        .modal{
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1050;
            display: none;
            overflow: hidden;
            -webkit-overflow-scrolling: touch;
            outline: 0;
        }
        .fade{
            opacity: 0;
            -webkit-transition: opacity .15s linear;
            -moz-transition: opacity .15s linear;
            transition: opacity .15s linear;
        }
        .fade.in{
            opacity: 1;
        }
        .modal.fade .modal-dialog {
            -webkit-transition: -webkit-transform .3s ease-out;
            -moz-transition: -moz-transform .3s ease-out;
            transition: transform .3s ease-out;
            -webkit-transform: translate(0, -25%);
            -ms-transform: translate(0, -25%);
            -moz-transform: translate(0, -25%);
            transform: translate(0, -25%);
        }
        .modal.in .modal-dialog {
            -webkit-transform: translate(0, 0);
            -ms-transform: translate(0, 0);
            -moz-transform: translate(0, 0);
            transform: translate(0, 0);
        }
        .modal-dialog {
            position: relative;
            width: auto;
        }
        .modal-back{
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 1049;
            background-color: #000;
        }
        .modal-back.fade {
            filter: alpha(opacity=0);
            opacity: 0;
        }
        .modal-back.in {
            filter: alpha(opacity=50);
            opacity: .5;
        }
        .out-zx{
            text-align: center;
            margin: auto;
            background: #fff;
            border-radius: .25em;
            overflow: hidden;
            padding: 1.8em 0 .8em 0;
            width: 80%;
            margin-bottom: 80%;
        }
        .out-zx img{
            width: 4.5em;
            height: 4.5em;
            border-radius: 50%;
        }
        .out-zx p{
            text-align: center;
            color: #000000;
            font-size: .8em;
        }
        .out-zx .change-btn{
            text-align: center;
            margin-top: 3.5em;
            margin-bottom: .2em;
        }
        .out-zx .change-btn button{
            color: #FFF;
            outline: none;
            border: none;
            font-size: 1.05em;
            line-height: 1.15em;
            border-radius: 1.05em;
            padding: .49em 0;
            width: 5.54em;
        }
        .out-zx .change-btn button.left-btn{
            background: #fff;
            color: #1B8DCC;
            border: 1px solid #1B8DCC;
            margin-right: 1.5em;
        }
        .out-zx .change-btn button.right-btn{
            background:#1B8DCC;
            margin-left: 1.5em;
        }
        .out-zx .change-btn button.right-btn:active{
            box-shadow: 0 1px 15px rgba(118,177,253,.5);
        }
        .right-active-btn{
            box-shadow: 0 1px 15px rgba(118,177,253,.5);
        }
        .out-zx .change-btn button.left-btn:active{
            box-shadow: 0px 1px 15px rgba(244,99,134,.5);
        }

        .file {
            position: relative;
            display: inline-block;
            background: #D0EEFF;
            border: 1px solid #99D3F5;
            border-radius: 4px;
            padding: 4px 12px;
            overflow: hidden;
            color: #1E88C7;
            text-decoration: none;
            text-indent: 0;
            line-height: 20px;
        }
        .file input {
            position: absolute;
            font-size: 100px;
            right: 0;
            top: 0;
            opacity: 0;
        }
        .file:hover {
            background: #AADFFD;
            border-color: #78C3F3;
            color: #004974;
            text-decoration: none;
        }
    </style>
@endsection

@section('content')
    <span style="margin-left: 10px;" onclick="jump_url();"><i class="fa fa-chevron-left"></i></span><div onclick="logout()" style="float: right;top: 1rem ;left: -.5rem;position: relative;font-size: x-large;color: white;margin-right: 10px;"> <i class="fa fa-power-off"></i> </div>
    <br/>
    <div class="summary">
        <div style="float: left;padding-top: 10px;">
            <img onclick="edit_face( this );" data-id="{{ $user->id }}" style="width: 100px;height: 100px;" class="img-circle"
                 src="{{ $user->avatar ? config('filesystems.disks.avatar.url') . $user->avatar : '/img/avatar_default.jpg'}}"
                 title="头像"/>
        </div>
        <div style="float: left;margin-left: 10px;">
            <p>{{$user->realname}} | <span style="color: deepskyblue">{{ $user->status == 0 ? '未激活' : $levels[$user->level]}}</span></p>
            <p style="font-size: small">代理商编号：{{$user->id}}</p>
            <p style="font-size: small"><a style="color: #1b8dcc;" href="{{route('info')}}">我的资料 <i class="fa fa-chevron-right"></i></a></p>
        </div>
    </div>
    <div class="content">
        <p>余额：￥{{$user->money}}</p><hr>
        <p>团队充值金额：￥{{ $recharge }}</p><hr>
        <p>团队人数：{{ $teamCount }}</p><hr>
        <p><a href="{{ route('customer.list') }}">客户资料<img style="width: 20px;margin-left: 50%" src="/img/icon_@2x_09.png"/></a></p><hr>
        <br/>
    </div>
    <br/><br/><br/>
    <div id="order_dialog">

    </div>
@endsection

@section('scripts')
    <script type="text/javascript">

        /* 跳转到默认页面 */
        function jump_url(){
            window.location.href = "{{route('home')}}";
        }

        /* 退出登录 */
        function logout() {
            var html = '<div class="modal fade in" tabindex="-1" role="dialog" id="MyShare" aria-hidden="false" style="display: block;">' +
                '<div class="modal-dialog">' +
                '<div class="out-zx">' +
                '<p>您确定要退出吗？</p>' +
                '<div class="change-btn">' +
                '<button style="float: none;" type="button" class="left-btn btn-close">再想想</button>' +
                '<button style="float: none;" type="button" class="right-btn btn-login">确定退出</button>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="modal-back fade in"></div>';
            $( '#order_dialog' ).html( html );

            /* 关闭模态框 */
            $( '.btn-close' ).click( function () {
                $( '#order_dialog' ).html( '' );
            } );

            /* 确定退出 */
            $( '.btn-login' ).click( function () {
                window.location.href = "{{route('logout')}}";
            } );
        }

        /* 点击选择修改头像 */
        function edit_face( option ) {
            var id = $( option ).attr( 'data-id' );
            var url = "{{ route('ucenter.edit_avatar') }}";
            var html = '<form action="' + url + '" method="post" enctype="multipart/form-data"><div class="modal fade in" tabindex="-1" role="dialog" id="MyShare" aria-hidden="false" style="display: block;">' +
                '<div class="modal-dialog">' +
                '<div class="out-zx ">' +
                '<a class="file">点击这里上传头像<input onchange="add_notice( this )" type="file" name="avatar"/></a>' +
                '<p></p>' +
                '<p>上传格式为: jpg, gif, png, jpeg</p>' +
                '<p>使用1:1的图片效果最佳</p>' +
                '<div class="change-btn">' +
                '<button style="float: none;" type="button" class="left-btn btn-close">再想想</button>' +
                '<button style="float: none;" type="submit" class="right-btn btn-login">确定修改</button>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="modal-back fade in"></div>' +
                '<input type="hidden" name="id" value="' + id + '"/>' +
                '{{ csrf_field() }}' +
                '</form>';
            $( '#order_dialog' ).html( html );

            /* 关闭模态框 */
            $( '.btn-close' ).click( function () {
                $( '#order_dialog' ).html( '' );
            } );
        }

        /* 添加上传头像提示 */
        function add_notice( input ) {
            var name = $( input ).val();
            var p = $( input ).parent().next();
            p.html( '已选择文件：' + name );
        }
    </script>
@endsection