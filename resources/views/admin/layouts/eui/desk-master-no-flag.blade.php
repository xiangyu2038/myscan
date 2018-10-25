<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ URL::asset('admin/eui/less/eui.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('admin/eui/iconfont/iconfont.css') }}" />
    <link rel="stylesheet" href="{{asset('css/loading.css')}}">
    <script src="{{ URL::asset('admin/eui/js/jquery-2.1.4.min.js') }}"></script>
    <script src="{{ URL::asset('admin/eui/js/eui.js') }}"></script>

    <link rel="stylesheet" href="{{ URL::asset('admin/eui/newStyle/style_pc.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/eui/newStyle/main.css') }}">
</head>
<body>


@component('admin.layouts.eui.tag-no-flag')

@endcomponent



<!-- Main content -->

@yield('content')

<!-- /.content -->

@include('admin.layouts.eui.pop')



<!-- REQUIRED JS SCRIPTS -->
@yield('script')
<script>

    function RE(url) {
        $('#pop').show();
        window.location.href=url;
        window.onbeforeunload=function(){
        }
    }
</script>
<script src="{{ URL::asset('admin/my/master.js') }}"></script>
<script src="{{ URL::asset('admin/eui/newJs/main.js') }}"></script>
</body>
</html>


