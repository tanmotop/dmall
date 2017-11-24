<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1,
            maximum-scale=3, minimum-scale=1, user-scalable=no">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="/assets/css/index.css"/>
</head>
<style type="text/css">
    a{
        text-decoration: none;
    }
</style>
<body style="padding-top: 0">
<p align="center" style="background: white;font-size: small;color: #FD6B6D">
   <span>您当前账户还未激活，请在线充值，系统将自动激活！</span>
</p>

<a href="{{ route('ucenter') }}">
    <div style="margin: 0.8rem 1rem;">
        <img src="/assets/img/100.png">
        <span>个人中心</span>
    </div>
</a>

<a href="{{ route('finances_charge') }}">
    <div style="margin: 0.8rem 1rem;">
        <img src="/assets/img/200.png">
        <span>在线充值激活</span>
    </div>
</a>

<br/><br/>
</body>
</html>
