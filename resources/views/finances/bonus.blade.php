@extends('layouts.app')

@section('styles')
	<link href="/assets/css/order_detail.css" rel="stylesheet" />
	<link href="/plugins/bootstrap/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
	<link href="/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
	<style type="text/css">
        /* 时间控件 */
        .dropdown {
            position: relative;
        }
        .dropdown-toggle:focus {
            outline: 0;
        }
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            display: none;
            float: left;
            min-width: 160px;
            padding: 5px 0;
            margin: 2px 0 0;
            font-size: 14px;
            list-style: none;
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border: 1px solid rgba(0, 0, 0, 0.15);
            border-radius: 4px;
            -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
            background-clip: padding-box;
        }
        .dropdown-menu.pull-right {
            right: 0;
            left: auto;
        }
        .dropdown-menu .divider {
            height: 1px;
            margin: 9px 0;
            overflow: hidden;
            background-color: #e5e5e5;
        }
        .dropdown-menu > li > a {
            display: block;
            padding: 3px 20px;
            clear: both;
            font-weight: normal;
            line-height: 1.428571429;
            color: #333333;
            white-space: nowrap;
        }
        .dropdown-menu > li > a:hover,
        .dropdown-menu > li > a:focus {
            color: #ffffff;
            text-decoration: none;
            background-color: #428bca;
        }
        .dropdown-menu > .active > a,
        .dropdown-menu > .active > a:hover,
        .dropdown-menu > .active > a:focus {
            color: #ffffff;
            text-decoration: none;
            background-color: #428bca;
            outline: 0;
        }
        .dropdown-menu > .disabled > a,
        .dropdown-menu > .disabled > a:hover,
        .dropdown-menu > .disabled > a:focus {
            color: #999999;
        }
        .dropdown-menu > .disabled > a:hover,
        .dropdown-menu > .disabled > a:focus {
            text-decoration: none;
            cursor: not-allowed;
            background-color: transparent;
            background-image: none;
            filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
        }
        .open > .dropdown-menu {
            display: block;
        }
        .open > a {
            outline: 0;
        }
        .dropdown-header {
            display: block;
            padding: 3px 20px;
            font-size: 12px;
            line-height: 1.428571429;
            color: #999999;
        }
        .dropdown-backdrop {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 990;
        }
        .pull-right > .dropdown-menu {
            right: 0;
            left: auto;
        }
        .dropup .caret,
        .navbar-fixed-bottom .dropdown .caret {
            border-top: 0 dotted;
            border-bottom: 4px solid #000000;
            content: "";
        }
        .dropup .dropdown-menu,
        .navbar-fixed-bottom .dropdown .dropdown-menu {
            top: auto;
            bottom: 100%;
            margin-bottom: 1px;
        }
        @media (min-width: 768px) {
            .navbar-right .dropdown-menu {
                right: 0;
                left: auto;
            }
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
            <form method="get" action="">
                <div class="tdcymd-top-center">
                   <input id="time" name="month" value="{{ \Illuminate\Support\Facades\Input::get('month') ?? Carbon\Carbon::now()->format('Y-m') }}" style="text-align: center" type="text" placeholder="请 选 择 时 间">
                </div>
                <div style="margin-top: 5px;margin-right: 10px;" class="top-right">
                    <button style="background: white;padding-top: 5px;" type="submit"><i style="font-size: 22px;color: #b3b3b3;" class="fa fa-search"></i></button>
                </div>
            </form>
        </div>

        <br/>

        <div id="member_list">
            <div class="zc-bottom">
                <ul id="list">
                    <li>当月奖金合计：<input style="width: 50%" readonly type="text" value="￥{{ $data->total_bonus }}"></li>
                    <li>个人业绩合计：<input style="width: 50%" readonly type="text" value="{{ $data->personal_pv ?? 0 }}"></li>
                    <li>团队业绩合计：<input style="width: 50%" readonly type="text" value="{{ $data->teams_pv  ?? 0 }}"></li>
                    <li>业绩奖金收入：<input style="width: 50%" readonly type="text" value="￥{{ $data->personal_pv_bonus }}"></li>
                    <li>级别差价收入：<input style="width: 50%" readonly type="text" value="￥{{ $data->level_money ?? 0 }}"></li>
                    <li>邀代奖金收入：<input style="width: 50%" readonly type="text" value="￥{{ $data->invite_money ?? 0 }}"></li>
                    <li>个人零售利润：<input style="width: 50%" readonly type="text" value="￥{{ $data->retail_money ?? 0 }}"></li>
                    <li>团队业绩抽成：<input style="width: 50%" readonly type="text" value="￥{{ $data->teams_pv_bonus }}"></li>
                </ul>
            </div>
        </div>
    </div>
    <br/><br/>
@endsection

@section('scripts')
	<script src="/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
	<script src="/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
	<script type="text/javascript">
        $( function (){
            /* 默认选择当前月 */
            var get_time = "{$Think.get.time}";
            if( get_time == '' ){
                var time = new Date();
                var new_time = time.getFullYear() + '-' + (Number(time.getMonth()) + Number(1));
                $( '#time' ).val( new_time );
            }
        } );
       // function ajaxfresh(){
       //     var html='';
       //     $.ajax({
       //         url:"__URL__/ajax_query",
       //         type:"post",
       //         success:function(data){
       //             $("#list").html('');
       //             html= '<li>当月奖金合计：<input style="width: 50%" readonly type="text" value="'+data.all+'"></li>'+
       //             '<li>个人业绩合计：<input style="width: 50%" readonly type="text" value="'+data.self_pv+'"></li>'+
       //             '<li>团队业绩合计：<input style="width: 50%" readonly type="text" value="'+data.all_pv+'"></li>'+
       //             '<li>业绩奖金收入：<input style="width: 50%" readonly type="text" value="'+data.pv_income+'"></li>'+
       //             '<li>级别差价收入：<input style="width: 50%" readonly type="text" value="'+data.level_income+'"></li>'+
       //             '<li>邀代奖金收入：<input style="width: 50%" readonly type="text" value="'+data.invitation_income+'"></li>'
       //             $("#list").append(html);
       //             setTimeout(function(){
       //                 ajaxfresh();
       //             },2000)
       //         }
       //     })
       // }
       // ajaxfresh();


        /* 时间控件 */
        $('#time').datetimepicker({
            language: 'zh-CN',
            format: 'yyyy-mm',
            initialDate: new Date(),
            autoclose: true,
            startView: 'year',
            minView:'year',
            maxView:'decade'
        });

        /* 提交表单 */
        function submit_form() {
            document.getElementById("formid").submit();
        }
    </script>
@endsection