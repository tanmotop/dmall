<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1,
			maximum-scale=3, minimum-scale=1, user-scalable=no">
    <title>{!! $title !!}</title>
    <link rel="stylesheet" href="/assets/css/index.css"/>
</head>
<style type="text/css">
    a{
        text-decoration: none;
    }
</style>
<body>
<img onclick="personal_index()" src="/assets/img/index_personal.png" >
<a href="{{ route('agents') }}">
    <div>
        <img src="/assets/img/index_member.png">
        <span>代理商管理</span>
    </div>
</a>

<a href="{{ route('mall') }}">
    <div>
        <img src="/assets/img/index_goods.png">
        <span>商品管理</span>
    </div>
</a>

<a href="{{ route('finances') }}">
    <div>
        <img src="/assets/img/300.png">
        <span>财务管理</span>
    </div>
</a>

<a href="{{ route('service') }}">
    <div>
        <img src="/assets/img/400.png">
        <span>客服中心</span>
    </div>
</a>
<br/><br/>
</body>
</html>
<script type="text/javascript">
    /* 去个人中心 */
    function personal_index() {
        window.location.href = "{{route('ucenter')}}";
    }
</script>