
<table class="eui-table" eui="even">
    <thead>
    <tr>
        <th colspan="10" eui="text-left">包裹信息</th>
    </tr>
    <tr>
        <th>序号</th>
        <th>学生名称</th>
        <th>性别</th>
        <th>年级</th>
        <th>班级</th>
        <th>购买产品</th>
        <th>本批次要发货的产品</th>
        <th>包裹扫描情况</th>
        <th>缺货情况</th>
        <th>换货情况</th>
    </tr>
    </thead>
    <tbody>
    @foreach($package_detail as $key=> $v)
    <tr>
        <td>{{$key+1}}</td>
        <td>{{$v['name']}}</td>
        <td>{{$v['sex']}}</td>
        <td>{{$v['grade']}}</td>
        <td>{{$v['grade_class']}}</td>
        <td>
            @foreach($v['sell_fashions'] as $pv)
                <p>{{$pv['fashion_name']}}({{$pv['fashion_code']}}) {{$pv['fashion_size']}} X {{$pv['fashion_num']}}</p>
            @endforeach
        </td>
        <td>
            @foreach($v['sell_fashions'] as $rv)
                <p>{{$rv['fashion_name']}}({{$rv['fashion_code']}}) {{$rv['fashion_size']}} X {{$rv['fashion_num']}}</p>
            @endforeach
        </td>
        <td>
            @foreach($v['scan_student'] as $sv)
            <p>{{$sv['fashion_name']}}({{$sv['fashion_code']}}) {{$sv['fashion_size']}} X {{$sv['fashion_num']}}</p>
              @endforeach
        </td>
        <td>
            @foreach($v['scan_que'] as $qv)
            <p>{{$qv['fashion_name']}}({{$qv['fashion_code']}}) {{$qv['fashion_size']}} X {{$qv['fashion_num']}}</p>
            @endforeach
        </td>
        <td>
            @foreach($v['scan_huan'] as $hv)
            <p>{{$hv['o_fashion_code']}} 换{{$hv['r_fashion_code']}}</p>
            @endforeach
        </td>

    </tr>
    @endforeach
    </tbody>
</table>