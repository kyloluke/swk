<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="robots" content="noindex,nofollow,noarchive" />
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/libs/jquery/jquery-ui.css">
    <link rel="stylesheet" href="/css/libs/jquery/jquery-ui.structure.css">
    <link rel="stylesheet" href="/css/libs/jquery/jquery-ui.theme.css">
    <link href="{{ asset('css/app.css', $is_https) }}" rel="stylesheet">

    <title>ShachihataCloud 勤怠管理</title>

</head> 
<body>
    <div id="app">
        @include('layouts.w001_header')
        @include('layouts.w002_content')
    </div>
    <!-- jquery,jquery-ui/js -->
    <script src="/js/libs/jquery/jquery-3.3.1.js"></script>
    <script src="/js/libs/jquery/jquery-ui.js"></script>
    <script src="/js/libs/jquery/datepicker-ja.js"></script>
    <!-- Popper.js, Bootstrap JS -->
    <script src="/js/libs/popper-1.14.7/popper.min.js"></script>
    <script src="/js/libs/bootstrap-4.2.1-dist/bootstrap.min.js"></script>
    <!-- Font Awesome5 -->
    <script src="/js/libs/fontawesome-free-5.15.2-web/all.min.js"></script>
    @php
    //力業でIE判定
        $browser = strtolower($_SERVER['HTTP_USER_AGENT']);
        $isIE = (strstr($browser , 'trident') || strstr($browser , 'msie'));
    @endphp
    @if($isIE)
    <!-- Promiseの変換 -->
    <script src="https://www.promisejs.org/polyfills/promise-7.0.4.min.js"></script>
    <!-- polyfills find -->
    <script src="/js/libs/polyfills_find.js"></script>
    <!-- polyfills includes -->
    <script src="/js/libs/polyfills_includes.js"></script>
    <!-- ES5対応共通js -->
    <script src=" {{ asset('/js/app.es5.js', $is_https) }} "></script>
    @else
    <!-- 通常共通js -->
    <script src=" {{ asset('/js/app.js', $is_https) }} "></script>
    @endif
</body>
</html>
