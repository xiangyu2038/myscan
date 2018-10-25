<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title') t5</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{asset('admin/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('admin/adminlte/bower_components/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('admin/adminlte/bower_components/Ionicons/css/ionicons.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('admin/adminlte/dist/css/AdminLTE.min.css')}}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{asset('admin/adminlte/dist/css/skins/_all-skins.min.css')}}">
    <!-- Morris chart -->
    <link rel="stylesheet" href="{{asset('admin/adminlte/bower_components/morris.js/morris.css')}}">
    <!-- jvectormap -->
    <link rel="stylesheet" href="{{asset('admin/adminlte/bower_components/jvectormap/jquery-jvectormap.css')}}">
    <!-- Date Picker -->
    <link rel="stylesheet" href="{{asset('admin/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{asset('admin/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{asset('admin/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/loading.css')}}">

    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="{{asset('admin/adminlte/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{asset('admin/adminlte/plugins/timepicker/bootstrap-timepicker.min.css')}}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('admin/adminlte/bower_components/select2/dist/css/select2.min.css')}}">

    <link rel="stylesheet" href="{{asset('admin/adminlte/plugins/iCheck/all.css')}}">
    <link rel="stylesheet" href="http://adminlte.la998.com/plugins/fullcalendar/fullcalendar.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery 3 -->
    <script src="{{asset('admin/adminlte/bower_components/jquery/dist/jquery.min.js')}}"></script>
    <!-- Google Font -->


<!-- 自己的js -->
    <script src="{{asset('admin/my/master.js')}}"></script>
    <!-- 自己的js -->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
@component('admin.layouts.tag')
@endcomponent


<!-- Main content -->
<section class="content">
@yield('content')
</section>
<!-- /.content -->

@include('admin.layouts.pop')


<!-- jQuery UI 1.11.4 -->
<script src="{{asset('admin/adminlte/bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('admin/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>


<script src="{{asset('admin/adminlte/bower_components/fastclick/lib/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('admin/adminlte/dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
{{--<script src="dist/js/pages/dashboard.js"></script>--}}
<!-- AdminLTE for demo purposes -->
<script src="{{asset('admin/adminlte/dist/js/demo.js')}}"></script>

<!-- iCheck 1.0.1 -->

@yield('script')
<script>
    function RE(url) {
        $('#myModal').modal('show');
        window.location.href=url;
    }
</script>

</body>
</html>
