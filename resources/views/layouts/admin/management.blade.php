<!DOCTYPE HTML>
<html lang="ja">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title></title>

    <!--Bootstrap CSS -->
    <link rel="stylesheet" href="/css/libs/bootstrap-4.2.1-dist/bootstrap.min.css">

    <!--public/css -->
    <link href="{{ asset('css/app.css', $is_https) }}" rel="stylesheet">

    <!--jquery-ui/css -->
    <link rel="stylesheet" href="/css/libs/jquery/jquery-ui.css">
    <link rel="stylesheet" href="/css/libs/jquery/jquery-ui.structure.css">
    <link rel="stylesheet" href="/css/libs/jquery/jquery-ui.theme.css">

</head>
<body>


<div id="app">
    <div id="loginCheckHeader">
        <div class="border border-dark border-bottom-0 p-1 bg-primary text-white" style="text-align:right;display:inline-block;width:100%;min-width:1100pt;">
            <div style="text-align:left;width:20%;float:left;display:inline;height:30pt;">
                <div class="d-inline-block" style="color: white;text-align:left;height:11pt;padding-left:5pt;font-size:11pt;"> 管理画面 </div>
            </div>
            <div>
                <a style="color:#ffffff;" href="{{ url('admin/logout', null, $is_https) }}"
                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                    {{ __('ログアウト') }}
                </a>

                <form id="logout-form" action="{{ url('admin/logout', null, $is_https) }}" method="POST" style="display: none;">
                    @csrf
                </form>

            </div>
        </div>
    </div>
    <management_menu></management_menu>
</div>


<!-- Optional JavaScript -->
<!--jquery,jquery-ui/js -->
<script src="/js/libs/jquery/jquery-3.3.1.js"></script>
<script src="/js/libs/jquery/jquery-ui.js"></script>
<script src="/js/libs/jquery/datepicker-ja.js"></script>

<!-- Popper.js, Bootstrap JS -->
<script src="/js/libs/popper-1.14.7/popper.min.js" ></script>
<script src="/js/libs/bootstrap-4.2.1-dist/bootstrap.min.js" ></script>

<!--Font Awesome5-->
<script src="/js/libs/fontawesome-free-5.15.2-web/all.min.js"></script>

<!-- Vue.js, 共通js -->
<script src=" {{ asset('/js/app.js', $is_https) }} "></script>

<script>

</script>

</body>
</html>