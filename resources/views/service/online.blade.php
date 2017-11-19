@extends('layouts.app')

@section('styles')
    <link href="/assets/css/tdglxt.css" rel="stylesheet" />
    <link href="/plugins/bootstrap/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
@endsection

@section('content')
<div class="zc-body">
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
    <br/>
    <div class="body-top">
        <div style="margin-left: 20px;" class="top-left">
            <a href="#">
                <img src="/assets/img/icon_@2x_18.gif">
            </a>
            <span style="font-size: 15px;margin-left: 10px;"><a href="http://wpa.qq.com/msgrd?v=3&uin=3480179282&site=qq&menu=yes&from=message&isappinstalled=0">在线客服</a></span>
        </div>

        <div class="top-right">
            <a href="#">
                <img src="/assets/img/icon_@2x_09.png">
            </a>
        </div>
    </div>
    <div class="body-top">
        <div style="margin-left: 20px;" class="top-left">
            <a href="{{ route('service_message') }}">
                <img src="/assets/img/mmbb.png">
                <span style="font-size: 15px;margin-left: 10px;">留言板</span>
            </a>
        </div>

        <div class="top-right">
            <a href="{{ route('service_message') }}">
                <img src="/assets/img/icon_@2x_09.png">
            </a>
        </div>
    </div>
</div>
<br/><br/>
@endsection