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
    @foreach($list as $item)
    <div class="body-top">
        <div style="margin-left: 20px;" class="top-left">
            <a href="{:U('CustomerService/training_detail', array( 'tra_id' => $v['id'] ))}">
                <img src="/assets/img/ziliao.png">
                <span style="font-size: 15px;margin-left: 10px;">{{ $item->name }}</span>
            </a>
        </div>
        <div class="top-right">
            <a href="{{ route('service_material_detail', ['type_id' => $item->id]) }}">
                <img src="/assets/img/icon_@2x_09.png">
            </a>
        </div>
    </div>
    @endforeach
</div>
<br/><br/>
@endsection