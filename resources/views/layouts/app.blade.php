<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link href="/assets/css/common.css" rel="stylesheet" />
    <title>{!! $title !!}</title>
    @yield('styles')
</head>
<body>

@yield('content')

</body>
</html>
<script src="/assets/js/jquery-2.2.0.js"></script>
@yield('scripts')