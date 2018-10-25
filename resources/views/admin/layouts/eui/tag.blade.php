

<!-- 风格二 -->
<div class="eui-tab" style="height: auto" eui="border">
    <div class="eui-tab-menu aa" eui="style2">
        @if(isset($sub_menus))
        @foreach($sub_menus as $sub_menu)
        <div @if($sub_menu['route_name']==$sub_menu['current'])
             class="eui-tab-item"
             @else
             class="eui-tab-item"
             @endif data-url="{{route($sub_menu['route_name'])}}">{{$sub_menu['name']}}</div>
        @endforeach
        @endif

            <span eui="pull-right">

                                        <a class="eui-btn" eui="primary,sm" id="back">后退</a>
                                     <a class="eui-btn" eui="primary,sm" id="forward">前进</a>

                                    </span>
    </div>

</div>
<!-- 加载 .eui-tab 模块 -->
<script>
    eui.init('.eui-tab');
</script>




<script>
    $('.aa').click(function () {

        $('#myModal').modal('show');
        //alert($(this).attr('data-url'));
        window.location.href=$(this).attr('data-url');
    });

    $('#back').click(function () {

        console.log(history.go(-1));

    });

    $('#forward').click(function () {
        console.log(history.go(1));
    });


</script>