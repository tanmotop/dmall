@extends('layouts.app')

@section('styles')
    <link href="/assets/css/tdglxt.css" rel="stylesheet" />
    <link href="/assets/css/td.css" rel="stylesheet" />
    <link href="/assets/css/mb.css" rel="stylesheet" />
    <link href="/plugins/bootstrap/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
@endsection

@section('content')
<div class="zc-body">
    <div class="body-top">
        <div style="margin-top: 5px;margin-left: 10px;" class="top-left">
            <a href="{{ route('service_notice') }}">
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
    <div class="zc-bottom">
        <ul>
            <li><input readonly type="text" value="{{ $notice->title }}"></li>
            <li><input style="font-size: 4px;color: #CFCFD1;" readonly type="text" value="{{ $notice->created_at }}"></li>
            <li>
                <div style="padding: 5px;padding-bottom: 10px;">
                    {!! $notice->content !!}
                </div>
            </li>
        </ul>
    </div>
</div>
<br/><br/>
@endsection

@section('scripts')
@endsection