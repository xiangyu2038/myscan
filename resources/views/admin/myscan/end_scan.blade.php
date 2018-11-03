@extends('admin.layouts.eui.desk-master')
@section('title', '处理零售发货')

@section('content')
<!--发货批次-->
<table class="eui-table table-no-num">
    <thead>
    <tr>
        <td>发货批次：{{$batch_data['batch_note']}}（ <span class="danger">{{$batch_data['batch_sn']}}</span> ）</td>
        <td>批次生成时间：{{$batch_data['batch_create_at']}}</td>
        <td>批次状态：{{$batch_data['batch_out_status']}}</td>
        <td>批次来源：{{$batch_data['batch_source']}}</td>
    </tr>

    </thead>
</table>
<!--发货批次-->

<!--标签页-->
<div class="z-tool sendGoods-tool" eui="clearfix">
    <a class="eui-btn" eui="primary,sm"  onclick="RE('{{route('admin.myscan.index.print_vip',['batch_id'=>$batch_data['batch_id']])}}')">打印学生vip</a>
    <a class="eui-btn" eui="primary,sm" onclick="RE('{{route('admin.myscan.index.scaning',['batch_id'=>$batch_data['batch_id']])}}')">货物扫描</a>
    <a class="eui-btn" eui="primary sm" onclick="RE('{{route('admin.myscan.index.end_scan',['batch_id'=>$batch_data['batch_id']])}}')">配送出库</a>
</div>
<!--标签页-->

<div class="z-tool" eui="clearfix">
    <div eui="pull-right">
        <a class="eui-btn cp-type-btn-orde" onclick="RE('{{route('admin.myscan.index.end_scan',['batch_id'=>$batch_data['batch_id']])}}')" eui="warn sm">出库产品清单</a>

        <a class="eui-btn cp-type-btn-bag" onclick="RE('{{route('admin.myscan.index.export_out_list',['batch_id'=>$batch_data['batch_id']])}}')" >导出出库产品清单</a>
    </div>
</div>


<table class="eui-table table-no-num" eui="text-left">
    <thead>
    <tr>
        <td>出库信息: 分拣包裹【<span class="danger">{{$need_chu_ku['all_package_num']}}</span>】个, 出库产品【<span class="danger">{{$need_chu_ku['all_fashion_num']}}</span>】件</td>
    </tr>

    </thead>
</table>
<table class="eui-table" eui="even">
    <thead>
    <tr>
        <th>序号</th>
        <th>产品名称</th>
        <th>产品编码</th>
        <th>尺码</th>
        <th>需出库数量</th>
        <th>本次发货数量</th>

    </tr>
    </thead>
    <tbody>
    @foreach($need_chu_ku['need_data'] as $key=>$v)
    <tr>
        <td>{{$key+1}}</td>
        <td>产品名称</td>
        <td>{{$v['fashion_code']}}</td>
        <td>{{$v['fashion_size']}}</td>
        <td>{{$v['fashion_num']}}</td>
        <td>{{$v['need_num']}}</td>

    </tr>
    @endforeach
    </tbody>
</table>
<div class="outbound-wrap">
    <label>
        承运单位:
        <select class="eui-ipt" name="wu_liu">
            <option value="自家承运">自家承运</option>
            <option value="德邦物流">德邦物流</option>
        </select>
    </label>
    <label>
        出库操作人:
        <input type="text" name="operate" class="eui-ipt">
    </label>
    <label>
        出库时间:
        <input type="text" name="time" class="eui-date">
    </label>
    <a class="eui-btn confirm-outbound" eui="done,pull-right" href="javascript:;">确认出库</a>
</div>
@endsection

@section('script')
<script>
    eui.init('.eui-checkbox,.eui-date');
    //TODO 配送出库  wu_liu 物流方式 operate 操作人员 out_time 出库时间
    function out(wu_liu,operate,out_time) {
        var batch_id="{{$batch_data['batch_id']}}";
        $.ajax({
            type: "get",
            url: "{{route('admin.myscan.index.go_out')}}",
            data: {wu_liu:wu_liu,operate:operate,out_time:out_time,batch_id:batch_id},
            dataType: "json",
            success: function(data){
                console.log(data);
                ///TODO 成功返回code=1 失败返回code=0
                if(data.code==0){
                    eui.popover({
                        info:'操作成功！'
                    });
                }else{
                    eui.prompts('操作失败!');
                }
            },
            error: function(){
                eui.prompts('系统异常, 稍后再试!');
            }
        });
    };

    $('.confirm-outbound').click(function(){
        var wu_liu=$('[name=wu_liu]').val();
        var operate=$('[name=operate]').val();
        var out_time=$('[name=time]').val();
        out(wu_liu,operate,out_time);
    });
</script>
    @endsection
