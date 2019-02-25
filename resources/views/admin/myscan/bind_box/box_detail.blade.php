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
        <th>包裹情况</th>
        <th>缺货情况</th>
        <th>换货情况</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($items['packages_info'] as $key=> $v): ; ?>
    <tr>
        <td>{{$key+1}}</td>
        <td>{{$v['student_info']['name']}}</td>
        <td>{{$v['student_info']['sex']}}</td>
        <td>{{$v['student_info']['grade_name']}}</td>
        <td>{{$v['student_info']['class_name']}}</td>
        <td>
            <?php foreach($v['will_fa_fashion'] as $pv): ; ?>
            <p>{{$pv['fashion_name']}}({{$pv['fashion_code']}}) {{$pv['fashion_size']}} X {{$pv['fashion_num']}}</p>
            <?php endforeach ; ?>
        </td>
        <td>
            <?php foreach($v['will_fa_fashion'] as $pv): ; ?>
            <p>({{$pv['fashion_code']}}) {{$pv['fashion_size']}} X {{$pv['fashion_num']}}</p>
            <?php endforeach ; ?>
        </td>
        <td>
            <?php foreach($v['scan'] as $pv): ; ?>
            <p>({{$pv['fashion_code']}}) {{$pv['fashion_size']}} X {{$pv['fashion_num']}}</p>
            <?php endforeach ; ?>
        </td>
        <td>
            <?php foreach($v['que']['que'] as $qv): ; ?>
                <p>({{$qv['fashion_code']}}) {{$qv['fashion_size']}} X {{$qv['fashion_num']}}</p>
            <?php endforeach ; ?>
        </td>
        <td>
            <?php foreach($v['huan'] as $hv): ; ?>
                <p>{{$hv['o_fashion_code']}} 换{{$hv['r_fashion_code']}}</p>
            <?php endforeach ; ?>
        </td>
    </tr>
    <?php endforeach ; ?>
    </tbody>
</table>
<table class="eui-table" eui="even">
    <thead>
    <tr>
        <th colspan="5" eui="text-left">产品信息</th>
    </tr>
    <tr>
        <th>序号</th>
        <th>编码</th>
        <th>尺码</th>
        <th>数量</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($items['fashions_info'] as $key=> $v): ; ?>
    <tr>
        <td>
{{$key+1}}
        </td>
        <td>
{{$v['fashion_code']}}
        </td>
        <td>
            {{$v['fashion_size']}}
        </td>
        <td>
{{$v['fashion_num']}}
        </td>
        <td>
            <a onclick="scanApp.openMove('{{$v["fashion_code"]}}')" class="eui-btn" eui="primary,sm">移箱</a>
        </td>
    </tr>
    <?php endforeach ; ?>
    </tbody>
</table>
