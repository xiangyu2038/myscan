@extends('admin.layouts.eui.desk-master')
@section('title', '处理零售发货')

@section('content')

    <script language="javascript" src="http://www.halfrin.com/Public/Behind/newJs/LodopFuncs.js"></script>

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
    <a class="eui-btn" eui="primary,sm" onclick="RE('{{route('bind_box.bind_box_display',['batch_id'=>$batch_data['batch_id']])}}')">货物装箱</a>
    <a class="eui-btn" eui="primary sm" onclick="RE('{{route('admin.myscan.index.end_scan',['batch_id'=>$batch_data['batch_id']])}}')">配送出库</a>
</div>
<!--标签页-->

<!--表格页-->
<table class="eui-table" eui="even">

    <div class="z-tool z-tool-pagedata" eui="clearfix">
        <div eui="pull-left">
            <!--            <a class="eui-btn" eui="primary">全部打印</a>-->
            <div class="eui-btn btn-list">
                <span>打印VIP</span>
                <div class="btn-list-wrap">
                    <div class="btn-item eui-btn">
                        <span>打印全部</span>
                        <div class="btn-main">
                            <a class="eui-btn print-allvip" data-type="preview">预览</a>
                            <a class="eui-btn print-allvip" data-type="print">直接打印</a>
                            <a class="eui-btn print-allvip" data-type="print_model">数据模型打印</a>
                        </div>
                    </div>
                    <div class="btn-item eui-btn">
                        <span>打印已选</span>
                        <div class="btn-main">
                            <a class="eui-btn print-vip" data-type="preview">预览</a>
                            <a class="eui-btn print-vip" data-type="print">直接打印</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="eui-btn btn-list">
                <span>打印产品清单</span>
                <div class="btn-list-wrap">
                    <div class="btn-item eui-btn">
                        <span>打印全部</span>
                        <div class="btn-main">
                            <a class="eui-btn  print-allgoods" data-type="preview">预览</a>
                            <a class="eui-btn print-allgoods" data-type="print">直接打印</a>
                        </div>
                    </div>
                    <div class="btn-item eui-btn">
                        <span>打印已选</span>
                        <div class="btn-main">
                            <a class="eui-btn print-goods" data-type="preview">预览</a>
                            <a class="eui-btn print-goods" data-type="print">直接打印</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="eui-btn btn-list">
                <span>打印快递单</span>
                <div class="btn-list-wrap">
                    <div class="btn-item eui-btn">
                        <span>打印全部快递单</span>
                        <div class="btn-main">
                            <a class="eui-btn print-allcourier" data-type="preview">预览</a>
                            <a class="eui-btn print-allcourier"  data-type="print">直接打印</a>
                        </div>
                    </div>
                    <div class="btn-item eui-btn">
                        <span>打印已选快递单</span>
                        <div class="btn-main">
                            <a class="eui-btn print-courier" data-type="preview">预览</a>
                            <a class="eui-btn print-courier" data-type="print">直接打印</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="eui-btn btn-list">
                <span>导出</span>
                <div class="btn-list-wrap">
                    <div class="btn-item eui-btn">
                        <a href="{{route('admin.myscan.index.export_kuai_di',['batch_id'=>$batch_data['batch_id'],'batch_source'=>$batch_data['batch_source']])}}">快递信息</a>
                    </div>
                    <div class="btn-item eui-btn">
                        <a onclick="RE('{{route('admin.myscan.index.export_pro_info',['batch_id'=>$batch_data['batch_id'],'batch_source'=>$batch_data['batch_source']])}}')">发货统计</a>
                    </div>
                    <div class="btn-item eui-btn">
                        <a class="print-export" onclick="RE('{{route('admin.myscan.index.export_hld',['batch_id'=>$batch_data['batch_id'],'batch_source'=>$batch_data['batch_source']])}}')">产品尺购买信息</a>
                    </div>
                </div>
            </div>
        </div>
        <div eui="pull-right">
            <input type="hidden" name="per_page" value="{$arrSearch['per_page']}">
            <input type="hidden" name="current_page" value="{$arrSearch['current_page']}">
            <input type="text" name="key_word" class="eui-ipt z-search-ipt" value="{{$search_con['key_word']}}"  placeholder="请输入搜索关键词">
            <select name="slt1" class="eui-select">
                <option value="1">条件1</option>
                <option value="2">条件2</option>
                <option value="3">条件3</option>
            </select>
            <button id="search" type="button" class="z-search-btn eui-btn" eui="warn"><i class="eui-icon-search" ></i> 搜索</button>
        </div>
    </div>

    <thead>
    <tr>
        <th></th>
        <th>序号</th>
        <th>学生姓名</th>
        <th>条码</th>
        <th>学校</th>
        <th>年级</th>
        <th>班级</th>
        <th>性别</th>
        <th>快递单</th>
        <th>快递公司</th>
        <th>产品清单</th>
        <th>本批次要打印的产品清单</th>
        <th>包裹扫描情况</th>
        <th>缺货情况</th>
        <th>换货情况</th>
        <th>是否打印</th>
        <th>收货地址</th>
        <th>编辑</th>
    </tr>
    </thead>

    <tbody>
    <?php foreach($will_print_data['data'] as $key=>$v): ; ?>
    <tr>
        <td><input class="eui-checkbox" type="checkbox" name="all" value="{{$v['one_code']}}" title=" "></td>
        <td>{{$key+1}}</td>
        <td>{{$v['name']}}</td>
        <td>{{$v['one_code']}}</td>
        <td>{{$v['school']}}</td>
        <td>{{$v['grade']}}</td>
        <td>{{$v['grade_class']}}</td>
        <td>{{$v['sex']}}</td>
        <td>{{$v['kuaidi']}}</td>
        <td>{{$v['kuaidi_company']}}</td>

        <td>
            <?php foreach($v['sell_fashions'] as $vv): ; ?>
            <p>{{$vv['fashion_name']}}({{$vv['fashion_code']}}) 尺码 {{$vv['fashion_size']}} ×{{$vv['fashion_num']}}</p>
            <?php endforeach ; ?>
        </td>
        <td>
            <?php foreach($v['sell_fashions'] as $vv): ; ?>
            <p>{{$vv['fashion_name']}}({{$vv['fashion_code']}}) 尺码 {{$vv['fashion_size']}} ×{{$vv['fashion_num']}}</p>
            <?php endforeach ; ?>
        </td>

        <td>
            <?php foreach($v['scan_student'] as $sv): ; ?>
            <p>{{$sv['fashion_name']}}({{$sv['fashion_code']}}) {{$sv['fashion_size']}} X {{$sv['fashion_num']}}</p>
            <?php endforeach ; ?>
        </td>
        <td>
            <?php foreach($v['scan_que'] as $qv): ; ?>
            <p>{{$qv['fashion_name']}}({{$qv['fashion_code']}}) {{$qv['fashion_size']}} X {{$qv['fashion_num']}}</p>
            <?php endforeach ; ?>
        </td>
        <td>
            <?php foreach($v['scan_huan'] as $hv): ; ?>
            <p>{{$hv['o_fashion_code']}} 换{{$hv['r_fashion_code']}}</p>
            <?php endforeach ; ?>
        </td>
        <td>已打印</td>
        <td>{{$v['address']}}</td>
        <td><a class="eui-btn" eui="sm,primary"  data-layer-url="{{route('admin.myscan.index.edit_address',['one_code'=>$v['one_code'],'batch_id'=>$batch_data['batch_id'],'source'=>$batch_data['batch_source']])}}" data-layer-title="编辑收货地址">编辑</a></td>
    </tr>
    <?php endforeach ; ?>
    </tbody>
</table>
<!--表格页-->

<!--分页-->
{{$will_print_data['links']}}
<!--分页-->
    @endsection
@section('script')

    <script>
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        eui.init('.eui-checkbox,.eui-select');
        ///TODO 打印学生vip  one_code 为学生的条码 为数组  示例 one_code=['XX6b5ff29202','XX6b5ff2920f']
        // printVip(one_code);
        function printVip(one_code,type,print_type) {

            var batch_id="{{$batch_data['batch_id']}}";
            var batch_source = "{{$batch_data['batch_source']}}";

            $.ajax({
                type: "post",
                url: "{{route('admin.myscan.index.print_list')}}",
                data: {batch_id:batch_id, one_code:one_code,print_type:print_type,batch_source:batch_source},
                dataType: "json",
                success: function(data){
                    if(data.code==0){
                        printVipPr(data.data,type,print_type);
                    }else{
                        eui.prompts('没有需要打印的');
                    }
                }
            });
        }
        function printAllVip(key_word,print_model,print_type) {
            var batch_id="{{$batch_data['batch_id']}}";
            var batch_source = "{{$batch_data['batch_source']}}";
            $.ajax({
                type: "get",
                url: "{{route('admin.myscan.index.print_all_list')}}",
                data: {key_word:key_word,batch_id:batch_id,print_model:print_model,print_type:print_type,batch_source:batch_source},
                dataType: "json",
                success: function(data){
                    if(data.code==0){
                        printVipPr(data.data,print_model,print_type);
                    }else{
                        eui.prompts('没有需要打印的');
                    }
                }
            });
        }

        ///TODO 打印产品清单  one_code 为学生的条码 为数组  示例 one_code=['XX6b5ff29202','XX6b5ff2920f']

        function printFashionList(one_code,type) {
            var batch_id="{{$batch_data['batch_id']}}";
            var batch_source = "{{$batch_data['batch_source']}}";
            $.ajax({
                type: "post",
                url: "{{route('admin.myscan.index.print_list')}}",
                data: {batch_id:batch_id, one_code:one_code,print_type:'fashion_list',batch_source:batch_source},
                dataType: "json",
                success: function(data){
                    if(data.code==0){
                        printFashionListPr(data.data,type);
                    }else{
                        eui.prompts('没有需要打印的');
                    }
                }
            });
        }


        //TODO  打印快递清单 one_code 为学生的条码 为数组  示例 one_code=['XX6b5ff29202','XX6b5ff2920f']

        function printKDList(one_code,type) {
            var batch_id="{{$batch_data['batch_id']}}";
            var batch_source = "{{$batch_data['batch_source']}}";
            $.ajax({
                type: "post",
                url: "{{route('admin.myscan.index.print_list')}}",
                data: {batch_id:batch_id, one_code:one_code,print_type:'k_d_list',batch_source:batch_source},
                dataType: "json",
                success: function(data){
                    if(data.code==0){
                        printKDListPr(data.data,type);
                    }else{
                        eui.prompts('没有需要打印的');
                    }
                }
            });
        }

        // 获取条码
        function getCode(type){
            var code=[];
            if(type=='all'){
                $('.eui-table tbody input').each(function(){
                    code.push($(this).val());
                });
            }else{
                $('.eui-table tbody input:checked').each(function(){
                    code.push($(this).val());
                });
            }
            return code;
        }

        // 打印学生
        $('.print-vip').click(function(){
            alert('即将开始打全部========================================================================================');
            var type = $(this).attr('data-type');
            var print_type = 'vip';///数据获取类型
            printVip(getCode(),type,print_type);
        });
        // 打印全部学生
        $('.print-allvip').click(function(){
            // printVip(getCode('all'));
            alert('即将开始执行指令========================================================================================');

            var key_word=$(" input[ name='key_word' ] ").val();
            var print_model = $(this).attr('data-type');///打印模式
            var print_type = 'vip';///数据获取类型

            printAllVip(key_word,print_model,print_type);
        });
        // 打印产品
        $('.print-goods').click(function(){
            alert('即将开始打全部========================================================================================');
            var type = $(this).attr('data-type');
            printFashionList(getCode(),type);
        });
        // 打印全部产品
        $('.print-allgoods').click(function(){
            alert('即将开始打全部========================================================================================');
            var type = $(this).attr('data-type');
            printFashionList(getCode('all'),type);
        });
        // 打印快递
        $('.print-courier').click(function(){
            alert('即将开始打全部========================================================================================');
            var type = $(this).attr('data-type');
            printKDList(getCode(),type);
        });
        // 打印全部快递
        $('.print-allcourier').click(function(){
            alert('即将开始打全部========================================================================================');
            var type = $(this).attr('data-type');
            printKDList(getCode('all'),type);
        });

    </script>

    <script>
        var LODOP; //声明为全局变量
        ////打印vip
        function printVipPr(arr,print_model,print_type) {
            // arr=['XX216c726522','XX216c726534','XX216c72653d','XX216c726545'];
            //arr=['XXe0843d5e99','XXe0843d5eaa'];

            LODOP=getLodop();

            if(print_type == 'vip'){
                $.each(arr,function (k,v) {
                    $.each(v,function (kk,vv) {

                        CreatePrintPage(vv);
                        //CreatePrintPageWithA4(vv);
                    });

                    if(print_model=='preview'){
                        LODOP.PREVIEW();
                    }else{
                        LODOP.PRINT();
                    }


                });
            }else{
                $.each(arr,function (k,v) {
                    $.each(v,function (kk,vv) {
                        CreatePrintPageWithA4(vv);
                    });

                    if(print_model=='preview'){
                        LODOP.PREVIEW();
                    }else{
                        LODOP.PRINT();
                    }


                });
            }
//        for (j = 1; j <=3; j++) {
//            CreatePrintPage();
//        };

            // LODOP.PREVIEW();

        };
        function CreatePrintPage(data) {
            //LODOP=getLodop(document.getElementById('LODOP1'),document.getElementById('LODOP_EM1'));
            //LODOP.PRINT_INITA(0,0,800,1600,"打印控件功能演示_Lodop功能_打印条码");
            var arr = Object.keys(data);
            var len = arr.length;

            LODOP.NewPage();
            if(len==1){
                LODOP.ADD_PRINT_TEXT(45,34,177,29,data.fashion_info);
                LODOP.SET_PRINT_STYLEA(0,"FontSize",6);
                LODOP.SET_PRINT_STYLEA(0,"Bold",1);
                //  LODOP.ADD_PRINT_TEXT(10,5,215,13,"空白分隔符");
            }else{
                LODOP.ADD_PRINT_TEXT(10,5,215,13,"学校:"+data.school);
                LODOP.SET_PRINT_STYLEA(0,"FontSize",7);
                LODOP.SET_PRINT_STYLEA(0,"Bold",1);
                LODOP.ADD_PRINT_TEXT(22,5,215,13,"年级:"+data.grade+" 班级:"+data.grade_class);
                LODOP.SET_PRINT_STYLEA(0,"FontSize",7);
                LODOP.SET_PRINT_STYLEA(0,"Bold",1);
                LODOP.ADD_PRINT_TEXT(34,5,215,13,"姓名:"+data.name+" 性别:"+data.sex);
                LODOP.SET_PRINT_STYLEA(0,"FontSize",7);
                LODOP.SET_PRINT_STYLEA(0,"Bold",1);
                LODOP.ADD_PRINT_TEXT(45,5,36,13,"订购:");
                LODOP.SET_PRINT_STYLEA(0,"FontSize",7);
                LODOP.SET_PRINT_STYLEA(0,"Bold",1);
                LODOP.ADD_PRINT_BARCODE(78,17,200,32,"128Auto",data.one_code);
                LODOP.SET_PRINT_STYLEA(0,"FontSize",7);
                LODOP.ADD_PRINT_TEXT(45,30,196,29,data.fashion_info);
                LODOP.SET_PRINT_STYLEA(0,"FontSize",6);
                LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            }



            /////////

        };


        function CreatePrintPageWithA4(data) {
            LODOP.NewPage();
            LODOP.ADD_PRINT_HTM(0,0,400,700,data);
        };

        ////打印vip

        /////打印产品清单
        function printFashionListPr(data,type) {


            LODOP=getLodop();

            var iPrinterCount=LODOP.GET_PRINTER_COUNT();///打印机个数
            for(var i=0;i<iPrinterCount;i++){

                print_name=LODOP.GET_PRINTER_NAME(i);
                if(print_name=='GP-L80160 Series'){
                    LODOP.SET_PRINTER_INDEX(i);
                    break;
                }

            };


            $.each(data,function (k ,v) {

                $.each(v,function (kk ,vv) {
                    LODOP.SET_PRINT_PAGESIZE(0,1000,vv.print_lengh,"A4");
                    CreateOnePage(vv);
                });

                if(type=='preview'){
                    LODOP.PREVIEW();
                }else{
                    LODOP.PRINT();
                }
            });



        }

        function CreateOnePage(data){
            LODOP.NewPage();
///////////商品内容
            var num=302;
//console.log(PRO);
            ////////////产品清单
            if(data.scan.length){
                LODOP.ADD_PRINT_TEXT(250,100,100,20,"产品清单");
                LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
                LODOP.SET_PRINT_STYLEA(0,"FontSize",13);
                LODOP.ADD_PRINT_TEXT(280,-2,596,20,"-----------------------------------------------");

                $.each(data.scan,function (key,v) {
                    LODOP.ADD_PRINT_TEXT(num,2,210,20,v.fashion_name +" X"+v.fashion_num + "      规格 "+v.fashion_size+"\r\n编码:"+v.fashion_code);
                    LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
                    LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
                    LODOP.ADD_PRINT_TEXT(num+28,-8,606,20,"-----------------------------------------------------");
                    num=num+50;
                });

            }


            ////////////产品清单


///////////////////换货清单
            if(data.scan_huan.length){
                LODOP.ADD_PRINT_TEXT(num+12,100,100,20,"换货清单");
                LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
                LODOP.SET_PRINT_STYLEA(0,"FontSize",13);
                LODOP.ADD_PRINT_TEXT(num+37,-2,596,20,"-----------------------------------------------");
                num=num+52;

                $.each(data.scan_huan,function (key,v) {

                    LODOP.ADD_PRINT_TEXT(num,2,210,20,v.o_fashion_code+'  换 '+v.r_fashion_code);
                    LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
                    LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
                    LODOP.ADD_PRINT_TEXT(num+28,-8,606,20,"-----------------------------------------------------");
                    num=num+50;
                });
            }


///////////////////换货

            ///////////////////缺货清单
            if(data.scan_que.length){
                LODOP.ADD_PRINT_TEXT(num+12,100,100,20,"缺货清单");
                LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
                LODOP.SET_PRINT_STYLEA(0,"FontSize",13);

                LODOP.ADD_PRINT_TEXT(num+37,-2,596,20,"-----------------------------------------------");
                num=num+52;

                $.each(data.scan_que,function (key,v) {
                    LODOP.ADD_PRINT_TEXT(num,2,210,20,v.fashion_name +" X"+v.fashion_num + "      规格 "+v.fashion_size+"\r\n编码:"+v.fashion_code);
                    LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
                    LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
                    LODOP.ADD_PRINT_TEXT(num+28,-8,606,20,"-----------------------------------------------------");
                    num=num+50;
                });
            }


            ///////////////////缺货清单

            num=num+30;

            LODOP.ADD_PRINT_TEXT(num+10,170,56,20,"小计：");

            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
            LODOP.ADD_PRINT_TEXT(num+10,1,56,20,"备注：");
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",11);
            LODOP.ADD_PRINT_TEXT(num+10,45,123,40,data.bei_zhu);
            LODOP.ADD_PRINT_TEXT(num+10,221,28,20,data.total);
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");

            num=num+50;

            LODOP.SET_PRINT_MODE("PRINT_NOCOLLATE",1);


            LODOP.ADD_PRINT_TEXT(220,38,241,20,data.batch_note);
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",7);
            LODOP.ADD_PRINT_TEXT(77,1,281,25,"哈芙琳—校服专家 高端定制");
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",13);
            LODOP.SET_PRINT_STYLEA(0,"Alignment",2);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(118,45,241,24,data.school);
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(136,1,59,26,"年级：");
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.ADD_PRINT_TEXT(118,2,60,20,"学校：");
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.ADD_PRINT_TEXT(155,1,58,25,"班级：");
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.ADD_PRINT_TEXT(174,1,74,26,"收货人：");
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.ADD_PRINT_TEXT(num+290,-41,328,40,"哈芙琳感谢亲对我们的支持，有问题请与我们联系");
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.SET_PRINT_STYLEA(0,"Alignment",2);
            //LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(136,45,242,20,data.grade);
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(155,45,239,20,data.class);
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(173,53,233,20,data.name);
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(192,1,67,20,"VIP号：");
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.ADD_PRINT_TEXT(191,53,229,20,data.sell_order_sn);
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(num+40,24,596,56,"1，商品本身问题或者包装错误\r\n2，商品要保持全新，未被洗涤或穿着");
            LODOP.ADD_PRINT_TEXT(num+26,1,73,20,"调换条件：");
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.ADD_PRINT_TEXT(num+106,24,248,56,'1，校服是定制产品，穿着稍大稍小，不影响使用\r\n2, 商品并非由哈芙琳提供,或并非"杜兰德"，"半成人品牌"');
            LODOP.ADD_PRINT_TEXT(num+203,66,207,56,"上班时间：400--880-1949（周一至周五(8:30-17:30)");
            LODOP.ADD_PRINT_TEXT(num+238,66,193,56,"下班时间：17302152095");

            LODOP.ADD_PRINT_TEXT(num+84,3,100,20,"不予调换情况：");
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.ADD_PRINT_TEXT(num+180,66,190,20,"从实际收货的7日内调换");
            LODOP.ADD_PRINT_TEXT(num+180,0,77,20,"调换间隔：");
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");

            LODOP.ADD_PRINT_TEXT(num+203,0,71,20,"服务热线：");
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            //LODOP.ADD_PRINT_TEXT(num+260,68,100,20,"4008801949");
            //LODOP.ADD_PRINT_TEXT(num+260,0,70,20,"QQ留言：");
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.ADD_PRINT_TEXT(num+260,90,183,20,"banchengren.taobao.com/");
            LODOP.ADD_PRINT_TEXT(num+260,0,100,20,"哈芙琳校园服饰：");
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            // LODOP.ADD_PRINT_TEXT(num+280,0,100,20,"半成人淘宝店：");
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            // LODOP.ADD_PRINT_TEXT(num+280,90,189,20,"http://banchengren.taobao.com/");


            LODOP.ADD_PRINT_IMAGE(9,115,51,49,"<img border='0' src='http://halfrin.com/images/hha.jpg' />");
            LODOP.SET_PRINT_STYLEA(0,"Stretch",1);

            // LODOP.ADD_PRINT_BARCODE(220,20,307,47,"128Auto",'22222222222222');



        };
        //////打印产品清单

        ////打印快递单
        function printKDListPr(data,type) {
            LODOP=getLodop();

            var iPrinterCount=LODOP.GET_PRINTER_COUNT();///打印机个数
            for(var i=0;i<iPrinterCount;i++){

                print_name=LODOP.GET_PRINTER_NAME(i);
                if(print_name=='QR-588 LABELs'){
                    LODOP.SET_PRINTER_INDEX(i);
                    break;
                }

            };

            CreateOneFormPage(data,type);


        }
        ////打印快递单
        function CreateOneFormPage(data,type){
            // var obj=$('.print_area');
            $.each(data,function (k,v) {
                $.each(v,function (kk,vv) {
                    //console.log();
                    LODOP.NewPage();
                    LODOP.ADD_PRINT_HTM(0,0,400,700,vv);
                });
                if(type=='preview'){
                    LODOP.PREVIEW();
                }else{
                    LODOP.PRINT();
                }
            });




            /*for (i = 1; i < 8; i++) {
                LODOP.NewPage();
                LODOP.ADD_PRINT_HTM(88,200,350,600,document.getElementById("kuaidi0").innerHTML);
            }*/
        };
    </script>

    <script>

        $('#search').click(function () {
          var key_word =  $(" input[ name='key_word' ] ").val();
          var batch_id = "{{$batch_data['batch_id']}}";
          var url = '{{URL::current()}}'+'?key_word='+key_word+'&batch_id='+batch_id;

            RE(url);
        });


    </script>
@endsection


