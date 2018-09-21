<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>首页-琳云系统(桌面版)</title>
    <link rel="shortcut icon" href="/Public/Behind/images/favicon.png" type="image/x-icon">
    <meta name="description" content="zihan'blog专注于前端设计，前端开发，欢迎访问" />
    <meta name="keywords" content="前端开发，前端设计，前端博客，web前端" />
    <link type="text/css" rel="stylesheet" href="{{ URL::asset('admin/webdesktop/css/css.css') }}" />
</head>
<body id="lxcn">
<div class="bgloader"></div>
<div id="task-bar"><ul class="task-window"></ul></div>
<div id="desk"><ul></ul></div>
<div id="menubar">
    <span class="mtop"></span>
    <ul>
        <li class="i selected" title="作者信息"><a href="index.html?menu=1"></a></li>
        <li class="b" title="博客版本"><a href="index.html?menu=2"></a></li>
        <li class="z" title="部分作品"><a href="index.html?menu=3"></a></li>
        <li class="m" title="RSS"><a href="http://www.zi-han.net/?feed=rss2"></a></li>

    </ul>
    <span class="mbot"><a href="javascript:void(0);">收起</a></span>
</div>
<div id="start-menu">
    <ul class="start-menu">
        <li class="menuSelected sm1"><a href="#">显示桌面</a></li>
        <li class="sm2"><a href="#">显示侧边栏</a></li>
        <li class="sm4"><a href="#">加入收藏</a></li>
        <li class="sm5"><a target="_blank" href="http://www.zi-han.net">返回博客主页</a></li>
    </ul>
</div>
<span class="startMenuBtn"></span>
<noscript><div class="noscript-warning">您的浏览器已禁用了Javascript脚本，请开启Javascript功能之后继续访问！！</div></noscript>
<script type="text/javascript" src="{{ URL::asset('admin/webdesktop/js/jquery-1.6.2.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('admin/webdesktop/js/jquery.tool.js') }}"></script>
<script type="text/javascript">

    function  GetQueryString(name){
        var reg=new   RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r=window.location.search.substr(1).match(reg);
        if(r!=null) return unescape(r[2]);return null;
    }
    //alert(GetQueryString("menu"));
    {{--if (GetQueryString("menu")==null){--}}
        {{--document.write( "<scri"+"pt type=text/javascript src={{ URL::asset('admin/webdesktop/js/shortcut3.js') }}>"+"</scr"+"ipt>" );--}}
    {{--}--}}
    {{--else{--}}
        {{--document.write( "<scri"+"pt type=text/javascript src={{ URL::asset('admin/webdesktop/js/shortcut.js') }}>"+GetQueryString("menu")+".js>"+"</scr"+"ipt>" );--}}
    {{--}--}}
    document.write( "<scri"+"pt type=text/javascript src={{ URL::asset('admin/webdesktop/js/core_1.js') }}>"+"</scr"+"ipt>" );



</script>
<script type="text/javascript" src="{{ URL::asset('admin/webdesktop/js/templates.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('admin/webdesktop/js/jquery-smartMenu.js') }}"></script>
<!--[if lte IE 6]>
<div id="ie6-warning">您正在使用 Internet Explorer 6，在本页面的显示效果可能有差异。建议您升级到 <a href="http://www.microsoft.com/china/windows/internet-explorer/" target="_blank">Internet Explorer 8</a> 或以下浏览器： <a href="http://ie.sogou.com/">搜狗</a> | <a href="http://www.google.com/chrome/?hl=zh-CN">Chrome</a> | <a href="http://chrome.360.cn/">360</a> | <a href="http://firefox.com.cn/">火狐</a></div>
<script type="text/javascript" src="{{ URL::asset('admin/webdesktop/js/forie6.js') }}"></script>
<![endif]-->
<!-- Baidu Button BEGIN -->
<div id="bdshare" class="bdshare_t bds_tools get-codes-bdshare">
    <a class="bds_tsina"></a>
    <a class="bds_tqq"></a>
    <a class="bds_qzone"></a>
    <a class="bds_douban"></a>
    <span class="bds_more"></span>
</div>
<script type="text/javascript" id="bdshare_js" data="type=tools&amp;mini=1" ></script>
<script type="text/javascript" id="bdshell_js"></script>
<script>
    var shortcut = [
            @foreach ($datas as $data)
        [{{$data['key']}},"{{$data['name']}}","{{ $data['icon'] }}","{{ $data['url'] }}",{{ $data['width'] }},{{ $data['height'] }}],
            @endforeach
    ];
</script>

</body>
</html>
