
@extends('admin.layouts.eui.desk-master')
@section('title', '处理零售发货')

@section('content')
<form class="eui-form" eui="border" style="margin-bottom:20px;" enctype="multipart/form-data"  >
    <div style="width:700px;" class="addFaHuoForm">
        <div class="eui-form-main">
            <label class="eui-form-label">发货日期</label>
            <div class="eui-form-item">
                <input type="text" name="fa_huo_time" class="eui-date" style="width:400px;">
            </div>
        </div>
        <div class="eui-form-main">
            <label class="eui-form-label">发货备注</label>
            <div class="eui-form-item">
                <input class="eui-ipt" name="note" type="text" style="width:400px;">
            </div>
        </div>
        <div class="eui-form-main">
            <div class="eui-form-item">
                <button type="button" class="eui-btn submit" eui="primary">提交</button>
                <button type="button" class="eui-btn">取消</button>
            </div>
        </div>
    </div>
</form>
<div class="z-tool" eui="clearfix">
    <div eui="pull-right">
        <form id="myform" action="{{route('admin.myscan.index.add_fa_huo')}}" onkeydown="if(event.keyCode==13){return $(this).find('.z-search-btn').click();}" enctype="multipart/form-data" method="get">
            {{--<input type="hidden" name="per_page" value="{$arrSearch[per_page]}">--}}
            {{--<input type="hidden" name="current_page" value="{$arrSearch[current_page]}">--}}
            {{--<input type="text" name="" class="eui-ipt" value="" placeholder="订单号">--}}
            {{--<input type="text" name="" class="eui-ipt" value="" placeholder="学生姓名">--}}
            {{--<input type="text" name="" class="eui-ipt" value="" placeholder="电话">--}}
            {{--<input type="text" name="" class="eui-ipt" value="" placeholder="学校名称">--}}
            <button type="reset" class="eui-btn"><i class="eui-icon-trash"></i> 清空</button>
            <div style="margin-bottom:15px;"></div>
            <input type="text" name="start" class="eui-date date-start" eui="upper:.date-end" value="{{$start}}" placeholder="下单开始日期">
            <input type="text" name="end" class="eui-date date-end" eui="lower:.date-start" value="{{$end}}" placeholder="下单结束日期">
            {{--<input type="text" name="" class="eui-date date-start1" eui="upper:.date-end1" value="" placeholder="支付开始日期">--}}
            {{--<input type="text" name="" class="eui-date date-end1" eui="lower:.date-start1" value="" placeholder="支付结束日期">--}}
            <button type="button" class="z-search-btn eui-btn submits" eui="warn"><i class="eui-icon-search"></i> 搜索</button>
        </form>
    </div>
</div>
<table class="eui-table add-retail-list">
    <thead>
    <tr>
        <th>序号</th>
        <th><a href="javascript:;" class="eui-btn pltui-all" data-st="0" eui="sm,primary">全选</a></th>
        <th>订单号</th>
        <th>金额</th>
        <th>创建时间</th>
        <th>姓名</th>
        <th>性别</th>

    </tr>
    </thead>
    <tbody>
    @foreach($all_sell_data['data'] as  $key=>$v)
    <tr>
        <td>{{$key+1}}</td>
        <td><input type="text" class="eui-checkbox" value="{{$v['order_sn']}}" title=" "></td>
        <td>{{$v['order_sn']}}</td>
        <td>{{$v['price']}}</td>
        <td>{{$v['created_at']}}</td>
        <td>{{$v['student_info']['name']}}</td>
        <td>{{$v['student_info']['sex']}}</td>
    </tr>
        @endforeach
    </tbody>
</table>
{{$all_sell_data['links']}}
@endsection

@section('script')
<script>
    eui.init('.eui-date,.eui-checkbox');
    $('.pltui-all').click(function(){
        var st=$(this).attr('data-st');
        var ts=true;
        if(st==0){
            ts=true;
            $(this).attr('data-st',1);
        }else{
            ts=false;
            $(this).attr('data-st',0);
        };
        $('.add-retail-list tr').each(function(){
            $(this).find('input').prop('checked',ts);
        });
    });
    $('.addFaHuoForm .submit').click(function(){
        var f=$('.addFaHuoForm');
        var data={
            fa_huo_time:f.find('[name=fa_huo_time]').val(),
            note:f.find('[name=note]').val(),
            order_sn:[]
        };
        $('.add-retail-list tbody tr').each(function(){
            var ipt=$(this).find('input');
            if(ipt.prop('checked')) data.order_sn.push(ipt.val());
        });
        if(!data.fa_huo_time) return eui.prompts('请选择发货日期');
        if(!data.note) return eui.prompts('请填写发货备注');
        if(!data.order_sn.length) return eui.prompts('请勾选订单号');

        $.ajax({
            url:'{{route('admin.myscan.index.add_fa_huo')}}',
            type: "post",
            data:data,
            success:function(res){
                RE('{{route('admin.myscan.index.index')}}');
            },
            error:function(){
                eui.prompts('系统异常！');
            },
            beforeSend:function(XMLHttpRequest){
                $('#pop').show();
            }

        })
    });
</script>

<script>
    $('.submits').click(function () {
        $('#myform').submit();
    })
</script>
@endsection