@extends('layouts.app')

@section('styles')
    <link href="/css/zc.css" rel="stylesheet" />
    <link href="/plugins/bootstrap/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
@endsection

@section('content')
    <div class="zc-body">
        <div class="body-top">
            <div style="margin-top: 5px;margin-left: 10px;" class="top-left">
                <a href="{{route('ucenter')}}">
                    <i style="font-size: 22px;margin-left: 5px;color: #b3b3b3;" class="fa fa-chevron-left"></i>
                </a>
            </div>
            <div style="margin-top: 8px;margin-right: 10px;" class="top-right">
                <a href="{{route('home')}}">
                    <i style="font-size: 26px;margin-left: -5px;color: #b3b3b3;" class="fa fa-home"></i>
                </a>
            </div>
        </div>
        <div class="zc-bottom">
            <form id="register_form">
                <ul>
                    <li>代理商编号：<input disabled type="text" value="{{$user->id}}"></li>
                    <li>上级代理商：<input disabled type="text" value=""></li>
                    <li>注册日期：<input disabled type="text" value="{{$user->created_at}}"></li>
                    <li>真实姓名：<input type="text" name="realname"  value="{{$user->realname}}" placeholder="请输入真实姓名"></li>
                    <li>手机号码：<input type="text" name="phone" value="{{$user->phone}}" placeholder="请输入手机号码（将检测唯一性）"></li>
                    <li>微信号码：<input type="text" name="wechat" value="{{$user->wechat}}" placeholder="请输入微信号（将检测唯一性）"></li>
                    <li>旧密码：<input type="password" name="old_password" placeholder="请输入旧密码"></li>
                    <li>新密码：<input type="password" name="new_password" placeholder="请输入新密码"></li>
                    <li>新密码确认：<input type="password" name="confirm_password" placeholder="请再次输入新密码"></li>
                    <li class="notice" style="height: 30px;text-align: center;font-size: 1.1em;color: red;display: none;"></li>
                </ul>
                <input type="hidden" name="id" value="{{$user->id}}">
                {{csrf_field()}}
                {{method_field('PUT')}}
            </form>
        </div>

        <div class="btn" style="display: block">
            <button class="style btn-register" value="">确认修改</button>
        </div>
    </div>
    <br/><br/>
    <div id="register_dialog">

    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        /* 表单验证 */
        $( '.btn-register' ).click( function (){
            var notice = $( '.notice' );
            $.ajax( {
                url: "{{route('edit_user')}}",
                type: 'PUT',
                data: $( '#register_form' ).serialize(),
                dataType: 'json',
                success: function ( json ) {
                    if( json.code === 10000 ){
                        notice.css( {'display': 'block', 'color': 'green' } ).html( json.msg );
                        setTimeout( function (){
                            window.location.href = "{{ route('info') }}";
                        }, 1000 );
                    }
                },
                error: function (xhr) {
                    notice.css( {'display': 'block', 'color': 'red' } ).html( xhr.responseJSON.msg );
                }
            } );
        } );
    </script>
@endsection