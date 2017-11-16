@extends('layouts.app')

@section('styles')
    <link href="/css/zc.css" rel="stylesheet" />
    <link href="/css/yqmcx.css" rel="stylesheet" />
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
        .up-right input{
            margin-left: 8px;
            margin-right: 8px;
            border: none;
            border-right: 1px solid #BFBFBF;
            border-left: 1px solid #BFBFBF;
            /*border-radius: 3px;*/
            line-height: 2em;
            outline: none;
            width: 30%;
            /* margin-bottom: 10px; */
            font-size: 0.8em;
            color: #000;
            text-align: center;
            /*padding: 0;*/
        }
        .yqmcx-bottom input{
            border: none;
            line-height: 2em;
            color: #1A1A1A;
        }
        .btn{
            width: 20%;
            border: 0;
            border-radius: 5px;
            color: #fff;
            /* height: 40px; */
            line-height: 1.5em;
            text-align: center;
            background-color: #0088cc;
            font-size: 0.9em;
            margin-top: 10px;
            margin-left: 40%;
        }
    </style>
@endsection

@section('content')
    <div class="zc-body">
        <div class="body-top">
            <div style="margin-top: 5px;margin-left: 10px;" class="top-left">
                <a href="{{ route('ucenter') }}">
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
            {{--<div class="sk-spinner sk-spinner-double-bounce">--}}
                {{--<div class="sk-double-bounce1"></div>--}}
                {{--<div class="sk-double-bounce2"></div>--}}
            {{--</div>--}}
            @forelse($users as $user)
            <form id="form_data_{{ $user['id'] }}">
                {{ csrf_field() }}
                <div class="yqmcx-bottom">
                    <p>代理商编号： {{ $user['user_id'] }}</p>
                    <p>客户姓名：{{ $user['name'] }} </p>
                    <p>收货人：<input type="text" style="width: 60px" name="name" value="{{ $user['name'] }}"><span style="color: orange">|</span> <input type="text" style="width: 100px" name="tel" value="{{ $user['tel'] }}"></p>
                    <p>收货地区：
                        <select required name="province" class="province" data-content="2">
                            @foreach($provinces as $provinceId => $province)
                            <option value="{{ $provinceId }}" @if($provinceId == $user->province_id) selected = "selected" @endif>{{ $province }}</option>
                            @endforeach
                        </select>
                        <select required name="city" class="city" data-content="3">
                            @foreach($user->cities as $cityId => $city)
                            <option value="{{ $cityId }}" @if($cityId == $user->city_id) selected = "selected" @endif>{{ $city }}</option>
                            @endforeach
                        </select>
                        <select required name="area" class="area">
                            @foreach($user->areas as $areaId => $area)
                            <option value="{{ $areaId }}" @if($areaId == $user->area_id) selected = "selected" @endif>{{ $area }}</option>
                            @endforeach
                        </select>
                    </p>
                    <p>详细地址：<input style="width: 70%" type="text" name="address" value="{{ $user['address'] }}"></p>
                    <p><button class="btn submit_btn" data-id="{{ $user['id'] }}" type="button">修改</button></p>
                </div>
            </form>
            @empty
                <div align="center" style="background: #E9EFF0" class="sp-list-bottom"><span>没有相应的资料</span></div>
            @endforelse
        </div>
    </div>
    <br/><br/>
@endsection

@section('scripts')
    <script src="/assets/js/dropload.min.js"></script>
    <script type="text/javascript">
        var provinces = JSON.parse('{!! $provincesJson !!}');
        var page = 2;
        $('#member_list').dropload({
            domDown : {
                domClass   : 'dropload-down',
                domRefresh : '<div align="center" class="sp-list-bottom goods-last-notice"><span>↑上拉获取更多资料</span></div>',
                domUpdate  : '<div align="center" class="sp-list-bottom goods-last-notice"><span>↓释放加载</span></div>',
                domLoad    : '<div align="center" class="sp-list-bottom goods-last-notice"><span>加载中...</span></div>'
            },
            loadDownFn : function(me){
                $.ajax({
                    type: 'GET',
                    url: "{{ route('customer.list') }}",
                    dataType: 'json',
                    data:{
                        dataType: 'json',
                        page:page
                    },
                    success: function(json){
                        console.log(json);
                        console.log(page);
                        page++;

                        var html = '';
                        var data = json.data;
                        for (var i = 0; i < data.length; i++) {
                            html += '<form id="form_data_' + data[i].id + '">\n' +
                                    '{{ csrf_field() }}\n' +
                                '<div class="yqmcx-bottom">\n' +
                                '<p>代理商编号： ' + data[i].user_id + '</p>\n' +
                                '<p>客户姓名：' + data[i].name + ' </p>\n' +
                                '<p>收货人：<input type="text" style="width: 60px" name="name" value="' + data[i].name + '"><span style="color: orange">|</span> <input type="text" style="width: 100px" name="tel" value="' + data[i].tel + '"></p>\n' +
                                '<p>收货地区：\n' +
                                '<select required name="province" class="province" data-content="2">\n';

                            $.each(provinces, function (provinceId, province) {
                                html += '<option value="' + provinceId + '"';
                                if (provinceId == data[i].province_id) {
                                    html += ' selected="selected" ';
                                }
                                html += '>' + province + '</option>\n';
                            });

                            html += '</select>\n' +
                                '<select required name="city" class="city" data-content="3">\n';

                            $.each(data[i].cities, function (cityId, city) {
                                html += '<option value="' + cityId + '"';
                                if (cityId == data[i].city_id) {
                                    html += ' selected="selected" ';
                                }
                                html += '>' + city + '</option>\n';
                            });


                            html += '</select>\n' +
                                '<select required name="area" class="area">\n';

                            $.each(data[i].areas, function (areaId, area) {
                                html += '<option value="' + areaId + '"';
                                if (areaId == data[i].area_id) {
                                    html += ' selected="selected" ';
                                }
                                html += '>'+ area +'</option>\n';
                            });

                            html += '</select>\n' +
                                '</p>\n' +
                                '<p>详细地址：<input style="width: 70%" type="text" name="address" value="' + data[i].address + '"></p>\n' +
                                '<p><button class="btn submit_btn" data-id="' + data[i].id + '" type="button">修改</button></p>\n' +
                                '</div>\n' +
                                '</form>';
                        }

                        $('#member_list').append(html);
                        me.resetload();
                    },
                    error: function(xhr, type){
                        alert('Ajax error!');
                        me.resetload();
                    }
                });
            }
        });

        $(document).on('change', '.province,.city',function (){
            var select = $(this);
            var level = $(this).attr('data-content');
            select.nextAll( 'select' ).empty().append( new Option( '请选择', 0) );

            if (select.val() <= 0) {
                alert('请选择地区');
                return false;
            }

            $.ajax( {
                url: "{{ route('customer.region') }}",
                type: 'GET',
                data: {
                    parent_id: select.val(),
                    level: level
                },
                dataType: 'json',
                success: function ( json ){
                    if( json.code === 10000 ){
                        $.each( json.data, function ( id, name ){
                            select.next().append( new Option( name, id ) );
                        } );
                    }
                }
            } );
        } );

        $(document).on('click', '.submit_btn', function () {
            var id = $(this).attr('data-id');
            var data = $("#form_data_" + id).serialize();
            $.ajax({
                url: "{{ route('customer.update') }}" + '?id=' + id,
                type: 'PUT',
                dataType:'json',
                data:data,
                success: function (json) {
                    if (json.code === 10000) {
                        alert('修改成功');
                    }
                },
                error: function (data) {
                    $.each(data.responseJSON.errors, function (k, v) {
                        alert(v[0]);
                        return false
                    })
                }
            });
        });
    </script>
@endsection