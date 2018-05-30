@extends('layouts.app')

@section('styles')
	<link href="/assets/css/zcxy.css" rel="stylesheet" />
@endsection

@section('content')
	<div style="padding: 15px" class="zcxy-body">
	    <br/>
		{!!$protocol['content']!!}
	    <div class="btn">
	        <button class="style btn-agree" type="button">同意注册协议</button>
	    </div>
	</div>
	<br/><br/>
@endsection

@section('scripts')
	<script>
		$('.btn-agree').click(function(){
		    window.location.href="{{ route('register') }}";
		});
	</script>
@endsection