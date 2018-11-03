@extends('admin.layouts.eui.desk-master-no-flag')
@section('title', '处理零售发货')

@section('content')
    <img width="300" src="{{$vv['one_code']}}">
    <table class="eui-table table-no-num">
        <thead>
        <tr>
            <td width="33%">订单编号：{{$vv['sell_order_sn']}}</td>
            <td width="33%">学生姓名：{{$vv['name']}}</td>
            <td width="33%">学校名称：{{$vv['school']}}</td>
        </tr>
        <tr>
            <td width="33%">性别：{{$vv['sex']}}</td>
            <td width="33%">年级/班级：{{$vv['grade']}}/{{$vv['grade_class']}}</td>
            <td width="33%">产品数量：{{$vv['total']['total_num']}}</td>
        </tr>
        </thead>
    </table>
    <h2 style="margin-bottom:10px;">产品清单</h2>
    <table class="eui-table table-no-num">
        <thead>
        <tr>
            <td>库位号</td>
            <td>产品名称</td>
            <td>产品编码</td>
            <td>关联编码</td>
            <td>尺码</td>
            <td>产品数量</td>
            <td>单价</td>
            <td>小计</td>
        </tr>
        </thead>
        <tbody>
       @foreach($vv['fashion_info'] as $vvv)
        <tr>
            <td>库位号</td>
            <td>{{$vvv['fashion_name']}}</td>
            <td>{{$vvv['fashion_code']}}</td>
            <td>关联编码</td>
            <td>{{$vvv['fashion_size']}}</td>
            <td>{{$vvv['fashion_num']}}</td>
            <td>{{$vvv['fashion_price']}}</td>
            <td>{{$vvv['fashion_price']}}</td>
        </tr>
           @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="7">合计</td>
            <td>{{$vv['total']['total_price']}}</td>
        </tr>
        </tfoot>
    </table>
@endsection