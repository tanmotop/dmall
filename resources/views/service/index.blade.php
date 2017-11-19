@extends('layouts.app')

@section('styles')
    <link href="/assets/css/tdglxt.css" rel="stylesheet" />
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
                <a href="{{ route('service_online') }}">
                    <img src="/assets/img/icon_@2x_88.png">
                    <p>在线客服</p>
                </a>
            </li>
            <li class="two">
                <a href="{{ route('service_material') }}">
                    <img src="/assets/img/icon_@2x_92.png">
                    <p>官方培训资料</p>
                </a>
            </li>
            <li class="three">
                <a href="{{ route('service_notice') }}">
                    <img src="/assets/img/icon_@2x_93.png">
                    <p>公告栏</p>
                </a>
            </li>
        </ul>
    </div>
    <br/><br/>
@endsection