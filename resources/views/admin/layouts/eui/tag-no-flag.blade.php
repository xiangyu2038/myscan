

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
    </div>

</div>
<!-- 加载 .eui-tab 模块 -->
<script>
    eui.init('.eui-tab');
</script>
