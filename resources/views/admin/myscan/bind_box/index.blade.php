@extends('admin.layouts.eui.desk-master')
@section('title', '扫描页面')

@section('content')
    <script language="javascript" src="http://www.halfrin.com/Public/Behind/newJs/LodopFuncs.js"></script>
    <script language="javascript" src="{{ URL::asset('admin/eui/newJs/vue.js') }}"></script>
    <script language="javascript" src="{{ URL::asset('admin/eui/newJs/Sweep.js') }}"></script>
    <style>
        /* 搜索功能配合 */
        html, body{
            overflow: visible;
            min-height: 100%;
        }
    </style>
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
        <a class="eui-btn" eui="primary sm"  onclick="RE('{{route('admin.myscan.index.print_vip',['batch_id'=>$batch_data['batch_id']])}}')">打印学生vip</a>
        <a class="eui-btn" eui="primary sm" onclick="RE('{{route('admin.myscan.index.scaning',['batch_id'=>$batch_data['batch_id']])}}')">货物扫描</a>
        <a class="eui-btn" eui="primary,sm" onclick="RE('{{route('bind_box.bind_box_display',['batch_id'=>$batch_data['batch_id']])}}')">货物装箱</a>
        <a class="eui-btn" eui="primary sm" onclick="RE('{{route('admin.myscan.index.end_scan',['batch_id'=>$batch_data['batch_id']])}}')">配送出库</a>
    </div>
    <!--标签页-->

    <div id="app">
        <div class="scanning-wrap">
            <div class="scanning-show" eui="clearfix">
                <div class="scanning-show-main" eui="pull-left">
                    <div :class="['clear',currentBox.code==''?'hide':'']">
                        <a @click="clearBox(currentBox.id)" class="eui-icon-trash danger" title="清空箱子"></a>
                    </div>
                    <div :class="['scanning-box',currentBox.code==''?'hide':'']" data-layer-title="货箱详情" :data-layer-url="'/myscan/bindBoxDisplay?batch_id='+batchData.batchId+'&order_data_cate_id='+batchData.sourceId+'&xiao_shou_fa_huo_id='+batchData.fahuoId+'&box_id='+currentBox.id" eui="clearfix">
                        <p class="code">编码：<span>@{{currentBox.name}}</span></p>
                        <p class="num">已装箱【<span class="danger">@{{currentBox.num}}</span>】件</p>
                    </div>
                    <div class="code-list">
                        <h1 :class="['scanBagCode',scanError?'done':'danger']">@{{currentCode}}</h1>
                        <ul class="code-list-box">
                            <li v-for="data in currentBox.products">
                                @{{data}}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="scanning-show-information" eui="pull-right">
                    <p class="box"><span style="width: 200px;">箱子总数【<span class="danger">@{{batchData.box}}</span>】</span>已扫描【<span class="danger package_box_total">@{{batchData.scanBox}}</span>】</p>
                    <p class="bag"><span style="width: 200px;">包裹总数【<span class="danger">@{{batchData.bag}}</span>】</span>已扫描【<span class="danger package_bag_total">@{{batchData.scanBag}}</span>】</p>
                    {{--<p class="product"><span style="width: 200px;">产品总数:【<span class="danger">@{{batchData.products}}</span>】</span>已扫描:【<span class="danger package_product_total">@{{batchData.scanProducts}}</span>】</p>--}}
                </div>
            </div>
            <div class="scanning-info">
                <input class="eui-ipt box-num" name="addBox" type="text" placeholder="请输入新增空箱数量">
                <a class="eui-btn add-box" eui="primary" href="javascript:addBox($('.box-num').val());"><i class="eui-icon-addition"></i> 添加空箱</a>
                <a class="eui-btn print-box" href="javascript:printBoxCodeSt();" eui="done">打印已选箱号</a>
                <a class="eui-btn print-box" href="javascript:printBoxSt();" eui="done">打印箱贴</a>
                <a class="eui-btn delete-box" href="javascript:delBox();">删除已选空箱</a>
                <a class="scanning-over eui-btn" eui="danger" href="javascript:scan('OVER');">装箱结束</a>
                <input type="checkbox" class="eui-checkbox all-box" title="空箱全选">
                <div eui="pull-right">
                    <input name="box-number" class="eui-ipt z-search-ipt" value="" placeholder="请输入搜索编码" type="text">
                    <a href="javascript:;" class="z-box-btn eui-btn" eui="warn"><i class="eui-icon-search"></i> 搜索</a>
                </div>
            </div>
            <ul class="scanning-box-list scanning-box-box">
                <li v-for="data in boxList" :class="['close',currentBox.code==data.code?'active':'']">
                    <i class="batch-open eui-icon-barrage" @click="batchScan(data.code)" title="批量扫描"></i>
                    <a class="scanning-box" data-layer-title="货箱详情" :data-layer-url="'/myscan/boxDetail?batch_id='+batchData.batchId+'&order_data_cate_id='+batchData.sourceId+'&xiao_shou_fa_huo_id='+batchData.fahuoId+'&box_id='+data.id" href="javascript:;"></a>
                    <label class="info">
                        <input type="checkbox" :value="data.id">
                        <p class="code">编码：<span>@{{data.name}}</span></p>
                        <p class="num">数量：<span>@{{data.num}}</span></p>
                    </label>
                </li>
            </ul>
        </div>
        <div :class="['batch-scan',replace.state?'':'hide']" style="z-index: 1000000;">
            <div class="head">
                <h2>@{{replace.title}}</h2>
                <a class="close" @click="openTips('close')" href="javascript:;">&times;</a>
            </div>
            <!-- 批量操作 -->
            <div :class="['main',replace.batch.state?'':'hide']">
                <label>
                    <span>包裹编码</span>
                    <input class="code eui-ipt" :value="currentBox.code" type="text" value="" readonly>
                </label>
                <label>
                    <span>产品编码</span>
                    <input class="eui-ipt" type="text" v-model="replace.batch.scanCode" name="" id="">
                </label>
                <label>
                    <span>数量</span>
                    <input class="num eui-ipt" maxlength="2" v-model="replace.batch.num" type="number" placeholder="请输入数量" value="">
                </label>
                <label>
                    <span></span>
                    <a class="submit eui-btn" @click="batchProduct" eui="primary" href="javascript:;">提交</a>
                    <a class="close eui-btn" @click="openTips('close')" href="javascript:;">取消</a>
                </label>
            </div>
            <!-- 移箱操作 -->
            <div :class="['main',replace.move.state?'':'hide']">
                <label>
                    <span>产品编码</span>
                    <input class="code eui-ipt move-vip" value="" disabled type="text">
                </label>
                <label>
                    <span>目标箱号</span>
                    <input class="eui-ipt" type="text" placeholder="请输入箱号，如：0007" v-model="replace.move.box">
                </label>
                <label>
                    <span></span>
                    <a class="submit eui-btn" @click="moveBox()" eui="primary" href="javascript:;">提交</a>
                    <a class="close eui-btn" @click="openTips('close')" href="javascript:;">取消</a>
                </label>
            </div>
        </div>
        <audio id="mp3-error" class="hide"  preload="auto">
            <source src="{{ URL::asset('admin/eui/music/error1.ogg') }}" type="audio/ogg">
        </audio>
        <audio id="mp3-success" class="hide"  preload="auto">
            <source src="{{ URL::asset('admin/eui/music/success1.ogg') }}" type="audio/ogg">
        </audio>
    </div>

    <script>
        eui.init('.eui-checkbox');
        // 初始化扫码枪模块
        Sweep.init();
        widFocus();
        var mp3Error=document.getElementById("mp3-error");
        var mp3Success=document.getElementById("mp3-success");
        // 是否允许扫描
        var scanState=true;
        var scanApp = new Vue({
            el: '#app',
            data: {
                // 批次信息
                batchData:{
                    // 客户端id
                    userId:"",
                    // 批次id
                    batchId:"{{$batch_data['batch_id']}}",
                    // 扫描类型
                    scanType:'box',
                    // 发货id
                    fahuoId:"0",
                    // 数据采集id
                    sourceId:"0",
                    // 箱子总数-已扫描箱子总数
                    box:"{{$sell_batch_statistics['box_num']}}",
                    scanBox:Number("{{$sell_batch_statistics['scan_box_num']}}"),
                    // 包裹总数-已扫描包裹总数
                    bag:"{{$sell_batch_statistics['package_num']}}",
                    scanBag:Number("{{$sell_batch_statistics['scan_package_num']}}"),
                    // 产品总数-已扫描产品总数
                    {{--products:"{{$sell_batch_statistics['fashion_num']}}",--}}
                    {{--scanProducts:Number("{{$sell_batch_statistics['scan_fashion_num']}}"),--}}
                },
                // 包裹列表数据
                boxList:[],
                // 当前包裹信息
                currentBox:{
                    id:'',
                    name:'',
                    code:'',
                    num:'',
                    products:[]
                },
                // 扫描状态
                scanError:true,
                // 当前扫描的编码
                currentCode:'请扫描',
                // 编码格式配置
                config:{
                    bag:{
                        head:'XX',
                        leng:12
                    },
                    box:{
                        head:'SELL',
                        leng:18
                    }
                },
                // 批量数据
                replace:{
                    state:false,
                    title:'',
                    batch:{
                        state:false,
                        scanCode:'',
                        num:1
                    },
                    // 移箱数据
                    move:{
                        vip:'',
                        box:'',
                        state:false
                    }
                }
            },
            methods:{
                // 扫码回调, 提交前校验
                scanBefore(code){
                    // 还原扫描状态
                    this.scanError=true;
                    // 展示编码
                    this.currentCode=code;
                    // 如果扫over
                    if(code=='OVER') return this.submitCode('over');
                    // 检查编码规格---并分类
                    var type=this.checkCode(code);
                    if(type=='box'){
                        // 如果是包裹编码---检查是否在当前批次---是此批次返回需要扫描的产品
                        const need=this.checkBatch(code);
                        if(need!=false){
                            // 定位包裹---初始化包裹产品数据
                            this.activeBag(need);
                            // 包裹正常---提交
                            this.submitCode(code);
                        };
                    }else if(type=='bag'){
                        // 扫描包裹校验
                        const state=this.checkBox(code);
                        if(state){
                            this.submitCode(code);
                            this.batchData.scanBag+=1;
                            this.currentBox.num=Number(this.currentBox.num)+1;
                        };
                    }else if(type=='product'){
                        this.submitCode(code);
                        this.batchData.scanProducts+=1;
                        this.currentBox.num=Number(this.currentBox.num)+1;
                    };
                },
                // 检查编码规格
                checkCode(code){

                    const head=code.substring(0,4);

                    if(head==this.config.box.head){
                        // 检查扫码编码
                        if(code.length!=this.config.box.leng){
                            return this.error('箱子编码长度不正确');
                        }
                        return 'box';
                    }else if(head==this.config.bag.head){

                        // 检查扫码编码
                        if(this.currentBox.code==''){
                            this.error('请先扫描箱子编码');
                            return false;
                        };
                        if(code.length!=this.config.bag.leng){
                            return this.error('包裹编码长度不正确');
                        }
                        return 'bag';
                    }else{

                        // 扫到产品---检查是否有扫包裹编码
                        if(this.currentBox.code==''){
                            this.error('请先扫描箱子编码');
                            return false;
                        };
                        return 'product';
                    };
                    return false;
                },
                // 校验批次
                checkBatch(code){
                    let isBatch=false;
                    let data={};
                    // 去需要扫的集合里找有无此包裹编码
                    this.boxList.forEach((n,i)=>{
                        if(n.code==code){
                        // 如果在此批次---获取包裹该扫描的数据
                        isBatch=true;
                        data=n;
                        return;
                    }
                });
                    if(!isBatch){
                        this.error('此箱号不在当前批次');
                        return isBatch;
                    };
                    // 校验成功,返回该包裹需要扫描的数据
                    return data;
                },
                // 获取箱子信息, 设定为当前箱子
                activeBag(need){
                    this.boxList.forEach(n=>{
                        if(n.code==need.code){
                        if(!n.isScan){
                            // 如果此箱子没计数过---以扫箱子+1
                            n.isScan=true;
                            this.batchData.scanBox+=1;
                        }
                        // 赋值给当前箱子信息

                        return this.currentBox=n;
                    }
                });
                },
                // 检查包裹是否已装
                checkBox(code){
                    var state=true;
                    console.log(this.currentBox);
                    this.currentBox.products.forEach(n=>{
                        if(n==code){
                        this.error('该包裹已装箱, 请勿重复扫描')
                        state=false;
                    };
                });
                    if(state) this.currentBox.products.push(code);
                    return state;
                },
                // 扫码提交
                submitCode(code){
                    const that=this;
                    $.ajax({
                        type: "get",
                        url: "/scan2.php",
                        data: {message:code,client_id:that.batchData.userId,batch_id:that.batchData.batchId,scan_type:that.batchData.scanType},
                        dataType: "json",
                        success(data){
                            console.log(data);
                            if(data.code==1){
                                mp3Success.play();
                            }else{
                                that.error(data.msg);
                                eui.popover({
                                    style:'warn',
                                    info:data.msg
                                });
                            };
                            if(code=='over'){
                                eui.prompts('已结束扫描!');
                            };
                        },
                        error(){
                            // 异常
                            that.error('系统异常!');
                            eui.popover({
                                style:'danger',
                                info:'系统异常!'
                            });
                        }
                    });
                },
                // 打开操作窗口，批量或移箱
                openTips(type){
                    scanState=false;
                    if(type=='batch'){
                        this.replace.state=true;
                        this.replace.title='批量扫描';
                        this.replace.batch.scanCode='';
                        this.replace.batch.state=true;
                        this.replace.move.state=false;
                    }else if(type=='move'){
                        this.replace.state=true;
                        this.replace.title='移箱操作';
                        this.replace.batch.state=false;
                        this.replace.move.state=true;
                        this.replace.move.vip='';
                        this.replace.move.box='';
                    }else{
                        this.replace.state=false;
                        this.replace.batch.state=false;
                        this.replace.move.state=false;
                        scanState=true;
                    }
                },
                // 批量扫描
                batchScan(code){
                    this.openTips('batch');
                    // 当前包裹获取焦点
                    this.scanBefore(code);
                },
                // 批量扫描提交
                batchProduct(){
                    // 校验产品
                    let that=this;
                    if(!that.replace.batch.scanCode) return eui.prompts('产品编码不能为空！');
                    $.ajax({
                        type: "get",
                        url: "{{route('bind_box.batch_scan_box')}}",
                        data: {box_id:that.currentBox.id,unit:that.replace.batch.scanCode,num:that.replace.batch.num},
                        dataType: "json",
                        success(data){
                            console.log(data);
                            if(data.code==1){
                                // 关闭对话框
                                that.openTips('close');
                                eui.prompts('提交成功!');
                                // 更新已扫描商品统计
                                that.batchData.scanProducts+=Number(that.replace.batch.num);
                                // 更新当前包裹num
                                that.currentBox.num=Number(that.currentBox.num)+Number(that.replace.batch.num);
                                mp3Success.play();
                            }else{
                                that.error(data.msg);
                                eui.popover({
                                    style:'warn',
                                    info:data.msg
                                });
                            }
                        },
                        error(){
                            // 异常
                            that.error('系统异常!');
                            eui.popover({
                                style:'danger',
                                info:'系统异常!'
                            });
                        }
                    });
                },
                // 清除箱子数据
                clearBox(id){
                    if(!id) return;
                    const that=this;
                    eui.popover({
                        type:'confirm',
                        style:'danger',
                        info:'确定要清空箱子？？？',
                        okaycall(){
                            $.ajax({
                                type: "get",
                                url: "{{route('bind_box.clear_box')}}",
                                data: {box_id:id,batch_id:that.batchData.batchId},
                                dataType: "json",
                                success: function(data){
                                    if(data.code==0){
                                        that.currentBox.products=[];
                                        that.currentBox.num=0;
                                    }
                                    eui.prompts(data.msg);
                                },
                                error(){
                                    // 异常
                                    that.error('系统异常!');
                                    eui.popover({
                                        style:'danger',
                                        info:'系统异常!'
                                    });
                                }
                            });
                        }
                    });
                },
                // 移箱操作
                openMove(code,id){
                    this.openTips('move');
                    $('.move-vip').val(code);
                    $('.move-id').val(id);
                },
                moveBox(){
                    /// TODO 移动箱子 code 要移动的编码 box_id 所在箱子的id 要移动到的箱子的ID
                    const that=this;
                    let moveId='';
                    const code=$('.move-vip').val();
                    const url=$('.eui-layer.on').attr('data-layer-urldata').split('&');
                    let id='';
                    url.forEach(n=>{
                        if(n.indexOf('box_id')!=-1) id=n.split('=')[1];
                });
                    that.boxList.forEach(n=>{
                        if(n.name==that.replace.move.box) moveId=n.id;
                    if(moveId==that.currentBox.id){
                        eui.prompts('目标箱号不能是当前箱号');
                        moveId=false;
                    };
                });
                    if(!moveId) return;
                    $.ajax({
                        type: "get",
                        url: "{{route('bind_box.move_box')}}",
                        data: {code:code,box_id:id,need_box_id:moveId},
                        dataType: "json",
                        success: function(data){
                            ///TODO 成功操作
                            if(data.code==0){
                                (that.currentBox.num-=1);
                                that.boxList.forEach(n=>{
                                    if(n.id==moveId) n.num=Number(n.num)+1;
                            });
                            };
                            eui.prompts(data.msg);
                            that.openTips('close');
                        },
                        error(){
                            // 异常
                            that.error('系统异常!');
                            eui.popover({
                                style:'danger',
                                info:'系统异常!'
                            });
                        }
                    });
                },
                // 报错
                error(msg){
                    this.scanError=false;
                    mp3Error.play();
                    eui.prompts(msg+'!');
                    console.log(msg);
                    return false;
                }
            },
            created(){
                // 赋值客户端id为当前时间戳
                this.batchData.userId="{{$user}}"+'box';
                // 箱子列表
                this.boxList=[
                        <?php foreach($box_data as $v): ; ?>
                    {
                        id:"{{$v['id']}}",
                        code:"{{$batch_data['batch_sn']}}"+"{{$v['code']}}",
                        name:"{{$v['code']}}",
                        num:"{{$v['element']}}",
                        products:[]
                    },
                    <?php endforeach ; ?>
                ];
            }
        });
        // 绑定扫码枪回调
        Sweep.call=code=>{
            // 扫码数据导入vue

            if(scanState) return scanApp.scanBefore(code.toUpperCase());
        };
        // 测试用---模拟扫描
        function scan(code){
            if(scanState) return scanApp.scanBefore(code.toUpperCase());
        };
        // 搜索箱号---定位
        $('.z-box-btn').click(function(){
            var s=$(this).siblings('.z-search-ipt').val();
            if(!s) return;
            var list=$(this).parents('.scanning-wrap').find('.scanning-box-list li');
            list.each(function(){
                var code=$(this).find('.code span').text();
                if(code==s){
                    var that=$(this);
                    that.addClass('danger');
                    that.mouseover(function(){
                        that.removeClass('danger');
                        that.off('mouseover');
                    });
                    $("html,body").animate({scrollTop:that.offset().top-100}, 200);
                }
            })
        });
        // 空箱全选
        $('.all-box').change(function(){
            var state=$(this).prop('checked');
            $('.scanning-box-list li').each(function(){
                if($(this).find('.num span').text()==0) $(this).find('input').prop('checked',state);
            });
        });
    </script>


    <script>
        // 获取已选箱子id
        function getBoxId(){
            var arr=[];
            $('.scanning-box-list input:checked').each(function(){
                arr.push($(this).val());
            });
            console.log(arr);
            return arr;
        }
        function addBox(num) {
            if(parseInt(num)<1) return;
            var batch_id='{{$batch_data["batch_id"]}}';
            $.ajax({
                type: "get",
                url: "{{route('bind_box.add_box')}}",
                data: {batch_id:batch_id, num:num},
                dataType: "json",
                success: function(data){
                    if(data.code==0){
                        /////重新加载页面
                        window.location.reload();
                    }else{
                        //TODO 更换弹出层 ok
                        mp3Error.play();
                        eui.prompts('增加箱子失败');
                    }
                }
            });
        }
        function delBox(){
            var arr=getBoxId();
            if(!arr.length) return;
            $.ajax({
                type: "get",
                url: "{{route('bind_box.del_box')}}",
                data: {box_id:arr},
                dataType: "json",
                success: function(data){
                    if(data.code==0){
                        //TODO 重新刷新这个页面 ok
                        window.location.reload();
                    }else{
                        //TODO 更换弹出层 ok
                        mp3Error.play();
                        eui.prompts('删除箱子失败');
                    }
                }
            });
        }



        // 打印-----------------------------------------------

        var LODOP;

        /////TODO 打印箱号  参数 箱子的id 不是编码 示例 box_id = ['1','2'];
        function printBoxCodeSt() {
// alert('开始发送');
            var batch_id="{{$batch_data['batch_id']}}";
            var custom_name="";
            var type="大货到校";
            var box_id=getBoxId();
            if(!box_id.length) return;
            $.ajax({
                type: "post",
                url: "{{route('bind_box.box_info')}}",
                data: {box_id:box_id, batch_id:batch_id,custom_name:custom_name,type:type },
                dataType: "json",
                success: function(data){
                    printBoxCodeStPr(data.data);
                }
            });
        }
        function printBoxCodeStPr(arr) {
            // arr=['XX216c726522','XX216c726534','XX216c72653d','XX216c726545'];
            //arr=['XXe0843d5e99','XXe0843d5eaa'];
            LODOP=getLodop();


            $.each(arr,function (k,v) {
                CreatePrintPage(v);
            });
//        for (j = 1; j <=3; j++) {
//            CreatePrintPage();
//        };

            LODOP.PREVIEW();
        };
        function CreatePrintPage(data) {
            //LODOP=getLodop(document.getElementById('LODOP1'),document.getElementById('LODOP_EM1'));
            //LODOP.PRINT_INITA(0,0,800,1600,"打印控件功能演示_Lodop功能_打印条码");

            LODOP.NewPage();

            //LODOP.PRINT_INIT("");
            LODOP.ADD_PRINT_TEXT(12,27,119,21,"条形码箱贴");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
            LODOP.ADD_PRINT_RECT(44,10,236,167,0,3);
            LODOP.ADD_PRINT_LINE(38,10,40,246,0,3);
            LODOP.ADD_PRINT_LINE(105,10,103,246,0,3);
            LODOP.ADD_PRINT_LINE(139,10,141,246,0,3);
            LODOP.ADD_PRINT_LINE(175,10,177,246,0,3);
            LODOP.ADD_PRINT_LINE(44,52,211,54,0,3);
            LODOP.ADD_PRINT_TEXT(56,18,34,40,"编号");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(115,16,42,20,"类别");
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(148,14,42,20,"备注");
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(187,18,43,20,"日期");
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(44,83,144,38,data.box_sn);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",47);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(112,64,133,23,data.type);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(150,65,129,22,data.custom);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(186,70,168,19,data.date.date);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",10); 
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_BARCODE(0,253,71,240,"128Auto",data.one_code);
            LODOP.SET_PRINT_STYLEA(0,"Angle",90);
            LODOP.ADD_PRINT_TEXT(221,98,117,16,"为您的校园增添色彩");
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);



        };


        ///TODO 打印箱贴
        function printBoxSt() {
            var batch_id="{{$batch_data['batch_id']}}";
            var custom_name="";
            var type="大货到校";

            var data_type="数据类型";

            var order_xiao_shou_sn="";
            var box_id=getBoxId();
            if(!box_id.length) return;
            $.ajax({
                type: "post",
                url: "{{route('bind_box.box_content')}}",
                data: {box_id:box_id, batch_id:batch_id,data_type:data_type , custom_name:custom_name ,order_xiao_shou_sn:order_xiao_shou_sn},
                dataType: "json",
                success: function(data){
                    printBoxStPr(data);
                }
            });
        }

        function printBoxStPr(data) {
            CreateOneFormPage(data);
            LODOP.PREVIEW();
        }

        function CreateOneFormPage(data){
            $.each(data.data,function (k,v) {
                //console.log();
                LODOP.NewPage();
                LODOP.ADD_PRINT_HTM(50,0,800,1200,v);
            });
            /*for (i = 1; i < 8; i++) {
                LODOP.NewPage();
                LODOP.ADD_PRINT_HTM(88,200,350,600,document.getElementById("kuaidi0").innerHTML);
            }*/
        };
    </script>


@endsection