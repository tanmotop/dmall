@extends('layouts.app')

@section('styles')
	<link href="/assets/css/yqmcx.css" rel="stylesheet" />
	<link href="/plugins/bootstrap/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
	<style type="text/css">
		/* 加载动画 */
		.sk-spinner-double-bounce.sk-spinner {width:40px;height:40px;position:relative;margin:0 auto;}
		.sk-spinner-double-bounce .sk-double-bounce1,.sk-spinner-double-bounce .sk-double-bounce2 {width:100%;height:100%;border-radius:50%;background-color:#1ab394;opacity:0.6;position:absolute;top:0;left:0;-webkit-animation:sk-doubleBounce 2s infinite ease-in-out;animation:sk-doubleBounce 2s infinite ease-in-out;}
		.sk-spinner-double-bounce .sk-double-bounce2 {-webkit-animation-delay:-1s;animation-delay:-1s;}
		@-webkit-keyframes sk-doubleBounce {
			0%,100% {-webkit-transform:scale(0);transform:scale(0);}
			50% {-webkit-transform:scale(1);transform:scale(1);}
		}
		@keyframes sk-doubleBounce {
			0%,100% {-webkit-transform:scale(0);transform:scale(0);}
			50% {-webkit-transform:scale(1);transform:scale(1);}
		}
		.up-right input {margin-left:8px;margin-right:8px;border:none;border-right:1px solid #BFBFBF;border-left:1px solid #BFBFBF;/*border-radius:3px;*/line-height:2em;outline:none;width:30%;/* margin-bottom:10px;*/font-size:0.8em;color:#000;text-align:center;/*padding:0;*/}
	</style>
@endsection

@section('content')
	<div class="zc-body">
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
        <div id="member_list">
            @forelse($users as $user)
                <div class="yqmcx-bottom">
                    <p>{{ $user->realname }}</p>
                    <div class="line"></div>
                    <span class="wsy-color">{{ $user->level_name }}</span>
                    <div class="line"></div>
                    <em>{{ $user->phone }}</em>
                    <p>激活时间：{{ $user->actived_at }}</p>
                </div>
            @empty
                <div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>暂无数据</span></div>
            @endforelse
        </div>
        @if ($users->currentPage() != $users->lastPage())
            <div align="center" onclick="getMoreUsers()" class="sp-list-bottom"><span>上拉/点击获取更多用户</span></div>
        @endif
    </div>
    <br/><br/>
@endsection

@section('scripts')
    <script>
        var currentPage = parseInt('{{ $users->currentPage() }}')
        var lastPage = parseInt('{{ $users->lastPage() }}')
        function getMoreUsers()
        {
            if (currentPage == lastPage) {
                return false;
            }
            $('.sp-list-bottom span').html('正在获取 <i class="fa fa-spinner fa-spin"></i>');
            $.get('{{ route('teams_members') }}', {
                page: currentPage + 1,
                dataType: 'json'
            }, function(json) {
                currentPage = json.current_page
                var data = json.data
                var html = ''
                for (var i in data) {
                    html += ''
                    + '<div class="yqmcx-bottom">'
                    + '    <p>' + data[i].realname + '</p>'
                    + '    <div class="line"></div>'
                    + '    <span class="wsy-color">' + data[i].level_name + '</span>'
                    + '    <div class="line"></div>'
                    + '    <em>' + data[i].phone + '</em>'
                    + '    <p>激活时间：' + data[i].actived_at + '</p>'
                    + '</div>'
                }
                setTimeout(function() {
                    if (json.current_page == json.last_page) {
                        $('.sp-list-bottom').remove();
                        $('.zc-body').append('<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有更多了</span></div>');
                    } else {
                        $('.sp-list-bottom span').html('上拉/点击获取更多用户');
                    }
                    $('#member_list').append(html)
                },500)
            });
        }
    </script>

    <script type="text/javascript">
        $(window).scroll(function() {
            if ($(document).scrollTop() >= $(document).height() - $(window).height()) {
                getMoreUsers();
            }
        });
    </script>
@endsection