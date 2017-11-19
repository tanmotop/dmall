@extends('layouts.app')

@section('styles')
    <link href="/assets/css/tdglxt.css" rel="stylesheet" />
    <link href="/assets/css/td.css" rel="stylesheet" />
    <link href="/plugins/bootstrap/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <style type="text/css">
        /* 加载动画 */
        .sk-spinner-double-bounce.sk-spinner {
            width: 40px;
            height: 40px;
            position: relative;
            margin: 0 auto;
        }
        .sk-spinner-double-bounce .sk-double-bounce1,
        .sk-spinner-double-bounce .sk-double-bounce2 {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: #1ab394;
            opacity: 0.6;
            position: absolute;
            top: 0;
            left: 0;
            -webkit-animation: sk-doubleBounce 2s infinite ease-in-out;
            animation: sk-doubleBounce 2s infinite ease-in-out;
        }

        .sk-spinner-double-bounce .sk-double-bounce2 {
            -webkit-animation-delay: -1s;
            animation-delay: -1s;
        }
        @-webkit-keyframes sk-doubleBounce {
            0%,
            100% {
                -webkit-transform: scale(0);
                transform: scale(0);
            }
            50% {
                -webkit-transform: scale(1);
                transform: scale(1);
            }
        }

        @keyframes sk-doubleBounce {
            0%,
            100% {
                -webkit-transform: scale(0);
                transform: scale(0);
            }
            50% {
                -webkit-transform: scale(1);
                transform: scale(1);
            }
        }
    </style>
@endsection

@section('content')
<div class="zc-body">
    <div class="body-top">
        <div style="margin-top: 5px;margin-left: 10px;" class="top-left">
            <a href="{{ route('service') }}">
                <i style="font-size: 22px;margin-left: 5px;color: #b3b3b3;" class="fa fa-chevron-left"></i>
            </a>
        </div>
         <div class="tdcymd-top-center">
            <a class="search-btn" style="float: right;margin-right: 5px;">
                <img src="/assets/img/icon_@2x_07.gif">
            </a>
            <input type="text" class="search-value" value="{{ $keyword }}" placeholder="公告标题">
        </div>
        <div style="margin-top: 8px;margin-right: 10px;" class="top-right">
            <a href="{{ route('home') }}">
                <i style="font-size: 26px;margin-left: -5px;color: #b3b3b3;" class="fa fa-home"></i>
            </a>
        </div>
    </div>
   <div id="tra_list">
        @forelse($list as $item)
            <div class="sp-list-bottom">
                <div class="down">
                    <div class="down-left"><img src="/assets/img/icon_@2x_102.png"></div>
                    <div class="down-mid">
                        <div style="margin-top: 6px;">
                            <p>{{ $item->title }}</p>
                        </div>
                        <div style="margin-top: 10px;"><p style="font-size: 4px;color: #CFCFD1;">不二大山</p></div>
                        <div style="margin-top: 10px;"><p style="font-size: 4px;color: #CFCFD1;">{{ $item->created_at }}</p></div>
                    </div>
                    <div style="margin-top: 3%" class="down-right" onclick="notice_detail('{{ $item->id }}')"><img onclick="" src="/assets/img/icon_@2x_09.png"></div>
                </div>
            </div>
        @empty
            <div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>暂无数据</span></div>
        @endforelse
   </div>
   @if ($list->currentPage() != $list->lastPage())
        <div align="center" onclick="getMoreNotices()" class="sp-list-bottom tips"><span>上拉/点击获取更多邀请码</span></div>
    @endif
</div>
<br/><br/>
@endsection

@section('scripts')
<script>
    $(window).scroll(function() {
        if ($(document).scrollTop() >= $(document).height() - $(window).height()) {
            getMoreNotices();
        }
    });
    var currentPage = parseInt('{{ $list->currentPage() }}')
    var lastPage = parseInt('{{ $list->lastPage() }}')
    function getMoreNotices()
    {
        if (currentPage == lastPage) {
            return false;
        }
        $('.tips span').html('正在获取 <i class="fa fa-spinner fa-spin"></i>');
        $.get('{{ route('service_notice') }}', {
            page: currentPage + 1,
            dataType: 'json',
            keyword: '{{ $keyword }}',
        }, function(json) {
            currentPage = json.current_page
            var data = json.data
            var html = ''
            for (var i in data) {
                var item = data[i]
                html += ""
                    + '<div class="sp-list-bottom">'
                    + '    <div class="down">'
                    + '        <div class="down-left"><img src="/assets/img/icon_@2x_102.png"></div>'
                    + '        <div class="down-mid">'
                    + '            <div style="margin-top: 6px;">'
                    + '                <p>'+item.title+'</p>'
                    + '            </div>'
                    + '            <div style="margin-top: 10px;"><p style="font-size: 4px;color: #CFCFD1;">不二大山</p></div>'
                    + '            <div style="margin-top: 10px;"><p style="font-size: 4px;color: #CFCFD1;">'+item.created_at+'</p></div>'
                    + '        </div>'
                    + '        <div style="margin-top: 3%" class="down-right" onclick="detail(\''+item.id+'\')"><img src="/assets/img/icon_@2x_09.png"></div>'
                    + '    </div>'
                    + '</div>'
            }
            setTimeout(function() {
                $('#tra_list').append(html)
                if (json.current_page == json.last_page) {
                    $('.tips').remove();
                    $('#tra_list').append('<div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有更多了</span></div>');
                } else {
                    $('.tips span').html('上拉/点击获取更多邀请码');
                }
            },500)
        });
    }
    $('.search-btn').on('click', function() {
        var val = $('.search-value').val()
        window.location = '{{ route('service_notice') }}?&keyword=' + val
    })
    function notice_detail(id)
    {
        location = '{{ route('service_notice_detail') }}' + '?id=' + id;
    }
</script>
@endsection