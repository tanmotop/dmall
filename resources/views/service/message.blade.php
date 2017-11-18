@extends('layouts.app')

@section('styles')
    <link href="/assets/css/tdglxt.css" rel="stylesheet" />
    <link href="/assets/css/mb.css" rel="stylesheet" />
    <link href="/plugins/bootstrap/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
@endsection

@section('content')
    <div class="body-top">
        <div style="margin-top: 5px;margin-left: 10px;" class="top-left">
            <a href="{{ route('service_online') }}">
                <i style="font-size: 22px;margin-left: 5px;color: #b3b3b3;" class="fa fa-chevron-left"></i>
            </a>
        </div>
        <div style="margin-top: 8px;margin-right: 10px;" class="top-right">
            <a href="{{ route('home') }}">
                <i style="font-size: 26px;margin-left: -5px;color: #b3b3b3;" class="fa fa-home"></i>
            </a>
        </div>
    </div> 
    <br>
    <span style="margin-left: 1.8em">带<em>*</em>为必填</span>
    <form action="{{ route('service_message_submit') }}" method="post">
        <div class="zc-body">
            <div class="zc-bottom">
                <ul>
                    <li><em>*</em> <span>姓名：</span><input required type="text" name="username"></li>
                    <li><em>*</em> <span>手机：</span><input required type="text" name="phone"></li>
                    <li><em>*</em> <span>微信：</span><input required type="text" name="wechat"></li>
                    <li><span>订单号：</span><input type="text" name="order_sn"></li>
                    <li style="border-bottom: none;"><em>*</em> <span>留言：<small style="color: #CFCFD1">(请在此留言，我们会及时联系您)</small></span></li>
                    <li><textarea required name="msg"></textarea></li>
                </ul>
            </div>
            <div class="btn">
                {{ csrf_field() }}
                <button type="submit" class="style btn-register">确认留言</button>
            </div>
        </div>
    </form>
    <br/><br/>
@endsection

@section('scripts')
<script type="text/javascript">
    $('form').submit(function() {
        var $form = $(this);
        $.post($form.attr('action'), $form.serialize(), function(res) {
            if (res == 1) {
                alert('留言成功')
                location = '{{ route('service_online') }}';
            } else {
                location.reload()
            }
        });
        return false;
    });
</script>
@endsection