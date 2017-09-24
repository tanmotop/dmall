@extends('layouts.app')

@section('styles')
	<link href="/assets/css/finance.css" rel="stylesheet" />
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
                <a href="{{ route('finances_bouns') }}">
                    <img src="/assets/img/icon_@2x_80.png">
                    <p>奖金查询</p>
                </a>
            </li>
            <li class="two">
                <a href="{{ route('finances_flow_cost') }}">
                    <img src="/assets/img/icon_@2x_82.png">
                    <p>财务流水</p>
                </a>
            </li>
            <li class="three">
                <a href="{{ route('finances_charge') }}">
                    <img src="/assets/img/icon_@2x_81.png">
                    <p>在线充值</p>
                </a>
            </li>
            <li class="four">
                <a href="{{ route('finances_charge_records') }}">
                    <img src="/assets/img/icon_@2x_86.png">
                    <p>充值记录</p>
                </a>
            </li>
            <li class="five">
                <a href="{:U('Finance/team_level')}">
                    <img src="/assets/img/icon_@2x_87.png">
                    <p>团队业绩</p>
                </a>
            </li>
        </ul>
    </div>
    <br/><br/>
@endsection