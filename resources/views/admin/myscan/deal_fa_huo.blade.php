@extends('admin.layouts.eui.desk-master')
@section('title', '处理零售发货')

@section('content')
<table class="eui-table table-no-num">
    <thead>
    <tr>
        <td>发货批次：{{$batch_data['batch_note']}}（ <span class="danger">{{$batch_data['batch_sn']}}</span> ）</td>
        <td>批次生成时间：{{$batch_data['batch_create_at']}}</td>
        <td>批次状态：{{$batch_data['batch_out_status']}}</td>
    </tr>

    </thead>
</table>


<table class="eui-table table-no-num" eui="text-left">
    <thead>
    <tr>
        <td colspan="7" class="primary">
            <h3 eui="pull-left">
                <span style="line-height: 28px;">发货包装</span>
            </h3>
            <span eui="pull-right">
                                     <a class="eui-btn" eui="primary,sm"  onclick="RE('{{route('admin.myscan.index.print_vip',['batch_id'=>$batch_data['batch_id']])}}')">打印学生vip</a>
                                    <a class="eui-btn" eui="primary,sm" onclick="RE('{{route('admin.myscan.index.scaning',['batch_id'=>$batch_data['batch_id']])}}')">货物扫描</a>
                <a class="eui-btn" eui="primary sm" onclick="RE('{{route('admin.myscan.index.end_scan',['batch_id'=>$batch_data['batch_id']])}}')">配送出库</a>
                                    </span>
        </td>
    </tr>
    <tr>
        <td colspan="4">

            <div class="eui-btn btn-list" eui="pull-right,sm">
                    <span>
                       <?php
                        if($k_d_c[0]=='ZTO'){
                            echo '中通快递';
                        }elseif ($k_d_c[0]=='YTO'){
                            echo '圆通快递';
                        }
                        ?>
                    </span>
                <div class="btn-list-wrap">
                    <div class="btn-item eui-btn" eui="sm" onclick="setKDCompany('YTO')">圆通快递</div>
                    <div class="btn-item eui-btn" eui="sm" onclick="setKDCompany('ZTO')">中通快递</div>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="3">本批次要配送的学生vip包裹【<span class="danger">{{$fa_huo_data['need_pei_song_num']}}</span>】个</td>
        <td colspan="4">要发货产品总【<span class="danger">{{$fa_huo_data['all_fashion_num']}}</span>】件</td>
    </tr>
    <tr>
        <th>序号</th>
        <th>产品编码</th>
        <th>自定编码</th>
        <th>产品名称</th>
        <th>尺码</th>
        <th>数量</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($fa_huo_data['fen_lei_with_size'] as $keys=>$vv): ; ?>
    <tr>
        <td>{{$keys+1}}</td>
        <td class="fashion_code">{{$vv['fashion_code']}}</td>
        <td><input class="alias_code eui-ipt" eui="sm" type="text" value="{{$vv['fashion_alias_code']}}"></td>
        <td>{{$vv['fashion_name']}}</td>
        <td>{{$vv['fashion_size']}}</td>
        <td>{{$vv['fashion_num']}}</td>
        <td><a href="javascript:;" class="eui-btn fashion_code_edit" eui="sm,primary" data-layer-url="{{route('admin.myscan.index.edit_fashion_size',['batch_id'=>$batch_data['batch_id']])}}" data-layer-title="修改" data-id="{{$batch_data['batch_id']}},{{$vv['fashion_code']}},{{$vv['fashion_size']}}">修改</a></td>
    </tr>
    <?php endforeach ; ?>
    <a href="javascript:;" onclick="RE('{{route('admin.myscan.index.export_li_huo',['batch_id'=>$batch_data['batch_id']])}}')" class="eui-btn sm" eui="sm,primary" >导出理货单</a>

    </div>
    </tbody>
</table>
@endsection

@section('script')
    <script>
        ///TODO 自定义编码 fashion_code='T1601147A',alias_code='S';
        function editAliasCode(fashion_code,alias_code,table) {
            $.ajax({
                type: "get",
                url: "{{route('admin.myscan.index.edit_alias_code')}}",
                data: {fashion_code:fashion_code, alias_code:alias_code},
                dataType: "json",
                success: function(data){
                    //TODO 成功data.code=1 失败 data.code = 0
                    if(data.code==0){
                        editCode(table,fashion_code,alias_code);
                        eui.prompts('编辑成功!');
                    }else{
                        eui.prompts('编辑失败!');
                    }
                }
            });
        };

        var old_code='';

        $('.alias_code').focus(function(){
            old_code='';
            old_code=$(this).val();
        });

        $('.alias_code').blur(function(){
            var code=$(this).val();
            var fashion_code=$(this).parents('tr').find('.fashion_code').text();
            if(old_code!=code) editAliasCode(fashion_code,code,$(this).parents('.eui-table'));
        });

        function editCode(table,fashion_code,edit){
            table.find('tr').each(function(){
                var code=$(this).find('.fashion_code').text();
                if(code==fashion_code) $(this).find('.alias_code').val(edit);
            });
        };
    </script>
    <script>
        ////打印vip
        function printVip(arr) {
            // arr=['XX216c726522','XX216c726534','XX216c72653d','XX216c726545'];
            //arr=['XXe0843d5e99','XXe0843d5eaa'];

            LODOP=getLodop();
            $.each(arr,function (k,v) {
                $.each(v,function (kk,vv) {
                    CreatePrintPage(vv);
                });

                // LODOP.PRINT();
                LODOP.PREVIEW();
            });

//        for (j = 1; j <=3; j++) {
//            CreatePrintPage();
//        };

            // LODOP.PREVIEW();

        };
        function CreatePrintPage(data) {
            //LODOP=getLodop(document.getElementById('LODOP1'),document.getElementById('LODOP_EM1'));
            //LODOP.PRINT_INITA(0,0,800,1600,"打印控件功能演示_Lodop功能_打印条码");

            LODOP.NewPage();
            LODOP.PRINT_INIT("");
            LODOP.SET_PRINT_MODE("PRINT_NOCOLLATE",1);
            LODOP.ADD_PRINT_TEXT(7,17,117,16,"货号:T1605218A ");
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(6,143,81,16,"规格:160");
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(27,18,121,16,"品名:英伦校园风棒球衫");
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(47,19,81,16,"颜色:深灰,酒红");
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_BARCODE(67,35,173,44,"128Auto","T1605218A160");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",7);

            /////////

        };
        ////打印vip
        //TODO 设置打印公司代码  k_d_c 公司代码    目前支持圆通和中通  圆通传YTO  中通传ZTO
        function setKDCompany(k_d_c) {
            $.ajax({
                type: "get",
                url: "{{route('admin.myscan.index.set_kd_company')}}",
                data: {k_d_c:k_d_c},
                dataType: "json",
                success: function(data){
                    eui.prompts(data.msg);
                }
            });
        }
    </script>
@endsection