@extends('layouts.app')

@section('styles')
    <link href="/assets/css/wjhdls.css" rel="stylesheet" />
    <link href="/plugins/bootstrap/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <style type="text/css">
        .dropdown {position:relative;}
        .dropdown-toggle:focus {outline:0;}
        .dropdown-menu {position:absolute;top:100%;left:0;z-index:1000;display:none;float:left;min-width:160px;padding:5px 0;margin:2px 0 0;font-size:14px;list-style:none;background-color:#ffffff;border:1px solid #cccccc;border:1px solid rgba(0,0,0,0.15);border-radius:4px;-webkit-box-shadow:0 6px 12px rgba(0,0,0,0.175);box-shadow:0 6px 12px rgba(0,0,0,0.175);background-clip:padding-box;}
        .dropdown-menu.pull-right {right:0;left:auto;}
        .dropdown-menu .divider {height:1px;margin:9px 0;overflow:hidden;background-color:#e5e5e5;}
        .dropdown-menu > li > a {display:block;padding:3px 20px;clear:both;font-weight:normal;line-height:1.428571429;color:#333333;white-space:nowrap;}
        .dropdown-menu > li > a:hover,.dropdown-menu > li > a:focus {color:#ffffff;text-decoration:none;background-color:#428bca;}
        .dropdown-menu > .active > a,.dropdown-menu > .active > a:hover,.dropdown-menu > .active > a:focus {color:#ffffff;text-decoration:none;background-color:#428bca;outline:0;}
        .dropdown-menu > .disabled > a,.dropdown-menu > .disabled > a:hover,.dropdown-menu > .disabled > a:focus {color:#999999;}
        .dropdown-menu > .disabled > a:hover,.dropdown-menu > .disabled > a:focus {text-decoration:none;cursor:not-allowed;background-color:transparent;background-image:none;filter:progid:DXImageTransform.Microsoft.gradient(enabled=false);}
        .open > .dropdown-menu {display:block;}
        .open > a {outline:0;}
        .dropdown-header {display:block;padding:3px 20px;font-size:12px;line-height:1.428571429;color:#999999;}
        .dropdown-backdrop {position:fixed;top:0;right:0;bottom:0;left:0;z-index:990;}
        .pull-right > .dropdown-menu {right:0;left:auto;}
        .dropup .caret,.navbar-fixed-bottom .dropdown .caret {border-top:0 dotted;border-bottom:4px solid #000000;content:"";}
        .dropup .dropdown-menu,.navbar-fixed-bottom .dropdown .dropdown-menu {top:auto;bottom:100%;margin-bottom:1px;}
        @media (min-width:768px) {.navbar-right .dropdown-menu {right:0;left:auto;}}
    </style>
@endsection

@section('content')
    <div class="wjhdls-body">
        <div class="body-top">
            <div style="margin-top: 5px;margin-left: 10px;" class="top-left">
                <a href="{{ route('agents') }}">
                    <i style="font-size: 22px;margin-left: 5px;color: #b3b3b3;" class="fa fa-chevron-left"></i>
                </a>
            </div>
            <div style="margin-top: 8px;margin-right: 10px;" class="top-right">
                <a href="{{ route('home') }}">
                    <i style="font-size: 26px;margin-left: -5px;color: #b3b3b3;" class="fa fa-home"></i>
                </a>
            </div>
        </div>
        <form id="code_form">
            <div class="yqmff-bottom">
                <div class="yqmff-bottom-left ">
                    <p>编号：<input name="create_member_id" value="{$Think.session.m_id}"/></p>
                    <p>邀请码：<input name="invitation_code" id="code" placeholder="点击刷新生成邀请码"/></p>
                    <p>有效时间：<input name="time" class="" id="time" placeholder="点我设置时间"/></p>
                </div>
                <div class="yqmff-bottom-right">
                    <button onclick="reflash();" type="button" class="sx-btn">刷新</button>
                </div>

            </div>
            <div align="center" class="sp-list-bottom notice" style="display: none;"><span></span></div>
            <div class="btn">
                <button onclick="/*add_code();*/location='{{ route('agents_codes') }}'" class="style" type="button">确认发放</button>
            </div>
        </form>
    </div>
    <br/><br/>
@endsection

@section('scripts')
    <script src="/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
    <script>
        /* 刷新验证码 */
        function reflash() {
            $.ajax({
                url: "{:U( 'Member/ajaxMakeCode' )}",
                type: 'POST',
                dataType: 'json',
                success: function ( json ){
                    if( json.code == 'success' ){
                        $( '#code' ).val( json.data );
                    }
                }
            });
        }

        /* 发放激活码 */
        function add_code() {
            var notice = $( '.notice' );
            $.ajax({
                url: "{:U( 'Member/ajaxAddCode' )}",
                type: 'POST',
                data: $( '#code_form' ).serialize(),
                dataType: 'json',
                success: function ( json ){
                    if( json.code == 'success' ){
                        notice.css( {'display': 'block', 'color': 'green' } ).html( json.msg );
                        window.location.href = "{:U('Member/query_code')}";
                    }else{
                        notice.css( {'display': 'block', 'color': 'red' } ).html( json.msg );
                    }
                }
            });
        }

        /* 时间控件 */
        $('#time').datetimepicker({
            language: 'zh-CN',
            format: 'yyyy-mm-dd',
            initialDate: new Date(),
            autoclose: true,
            minView: 'month'
        });
    </script>
@endsection
