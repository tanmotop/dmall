<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1,
			maximum-scale=3, minimum-scale=1, user-scalable=no">
    <title>{!! $title !!}</title>
    <link rel="stylesheet" href="/assets/css/member_level.css"/>
    <script src="/plugins/bootstrap/js/jquery-1.12.4.min.js"></script>
    <link href="/plugins/bootstrap/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
</head>
<body>
<span style="margin-left: 10px;" onclick="jump_url();"><i class="fa fa-chevron-left"></i></span>
<div class="summary">
    <p>{{ $teamLevelInfo['my']->realname }} | {{ $teamLevelInfo['my']->parent_realname }} | {{ $teamLevelInfo['my']->phone }}</p>
    <p>编号：{{ $teamLevelInfo['my']->id }}</p>
    <p>激活时间：{{ $teamLevelInfo['my']->actived_at }}</p>
    <p>团队人数：{{ $teamLevelInfo['my']->team_count }}</p>
    <p>业绩合计：{{ $teamLevelInfo['my']->team_pv }}<if condition="$data['children']"><span onclick="show_content( this );" class="num-">-</span></if></p>
</div>
@if (!empty($teamLevelInfo['members']))
    <div class="content">
        @foreach($teamLevelInfo['members'] as $member)
        @endforeach
    </div>
@endif
<br/><br/><br/>
</body>
</html>
<script type="text/javascript">
    /* 显示一级代理 */
    function show_content( span ) {
        var text =  $( span ).text();
        if( text == '+' ){
            $( '.content' ).show( 'slow' );
            $( span ).text( '-' );
        }else{
            $( '.content' ).hide( 'slow' );
            $( span ).text( '+' );
        }
    }

    /* 显示一级代理的一级代理 */
    function show_table( span ) {
        var text =  $( span ).text();
        var table = $( span ).parent().next();
        var hr = $( span ).parent().next().next().next();
        if( text == '+' ){
            table.show();
            $( span ).text( '-' );
            hr.removeClass( 'hr2' );
        }else{
            table.hide();
            $( span ).text( '+' );
            hr.addClass( 'hr2' );
        }
    }

    /* 跳转到代理商页面 */
    function jump_url() {
        window.location.href = '{{route('agents')}}';
    }

    /* 显示下级代理商 */
    function team_go_to( span ) {
        var id = $( span ).attr( 'value' );
        window.location.href = "{:U('Member/team_level', '', false)}/id/"+id;
    }
</script>