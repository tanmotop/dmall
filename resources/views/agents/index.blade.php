@extends('layouts.app')

@section('styles')
    <!-- bootstrap开始 -->
    <link href="/assets/css/member.css" rel="stylesheet" />
    <link href="/plugins/bootstrap/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
@endsection

@section('content')
    <div class="body-top">
        <div style="margin-top: 5px;margin-left: 10px;" class="top-left">
            <a href="{{ route('home') }}">
                <i style="font-size: 22px;margin-left: 5px;color: #b3b3b3;" class="fa fa-chevron-left"></i>
            </a>
        </div>
        <div style="margin-top: 8px;margin-right: 10px;" class="top-right">
            <a href="{{ route('home') }}">
                <i style="font-size: 26px;margin-left: -5px;color: #b3b3b3;" class="fa fa-home"></i>
            </a>
        </div>
    </div>

    <div class="tdglxt-body">
        <ul>
            <li class="one">
                <a href="{{ route('contract') }}">
                    <img src="/assets/img/icon_@2x_55.png">
                    <p>代理商注册</p>
                </a>
            </li>
            <li class="two">
                <a href="{{ route('agents_inactive') }}">
                    <img src="/assets/img/icon_@2x_57.png">
                    <p>未激活代理商</p>
                </a>
            </li>
            <li class="three">
                <a href="{{ route('agents_code_sending') }}">
                    <img src="/assets/img/icon_@2x_59.png">
                    <p>邀请码发放</p>
                </a>
            </li>
            <li class="four">
                <a href="{{ route('agents_codes') }}">
                    <img src="/assets/img/icon_@2x_65.png">
                    <p>邀请码查询</p>
                </a>
            </li>
            <li class="five">
                <a href="{{ route('teams_members') }}">
                    <img src="/assets/img/icon_@2x_64.png">
                    <p>团队成员名单</p>
                </a>
            </li>
            <li class="six">
                <a href="{{ route('teams_levels') }}">
                    <img src="/assets/img/icon_@2x_66.png">
                    <p>团队层级图</p>
                </a>
            </li>
        </ul>
    </div>
    <br/><br/>
@endsection
