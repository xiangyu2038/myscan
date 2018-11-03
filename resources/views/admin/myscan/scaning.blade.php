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

        <a class="eui-btn" eui="primary sm" onclick="RE('{{route('admin.myscan.index.end_scan',['batch_id'=>$batch_data['batch_id']])}}')">配送出库</a>
    </div>
    <!--标签页-->

    <div id="app">
        <div class="scanning-wrap scanning-parcel">
            <div class="scanning-show" eui="clearfix">
                <div class="scanning-show-main" eui="pull-left">
                    <div :class="['clear',currentBag.code==''?'hide':'']">
                        <a @click="printOrder('old')" class="eui-icon-print primary" title="打印清单"></a>
                        <a @click="batchScan(currentBag.code)" class="eui-icon-barrage warn" title="手动扫描"></a>
                        <a @click="openChange()" class="eui-icon-manage warn" title="换货"></a>
                        <a @click="clearBag(currentBag.code)" class="eui-icon-trash danger" title="清空包裹"></a>
                    </div>
                    <div :class="['scanning-box',currentBag.code==''?'hide':'']" data-layer-title="包裹详情" :data-layer-url="'/myscan/packageDetail?batch_id='+batchData.batchId+'&one_code='+currentBag.code" eui="clearfix">
                        <p class="name">姓名：<span>@{{currentBag.name}}</span></p>
                        <p class="code">编码：<span>@{{currentBag.code}}</span></p>
                        <p class="num">已装产品【<span class="danger">@{{currentBag.num}}</span>】件</p>
                    </div>
                    <div class="code-list">
                        <!-- <a href="javascript:;" class="eui-icon-refresh scanning-start done"></a> -->
                        <h4>@{{currentBag.code?'产品列表':'请扫描包裹'}}</h4>
                        <ul class="code-list-box">
                            <li v-for="data in currentBag.products" :class="data.isScan?'primary':''">
                                @{{data.code}}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="scanning-show-information" eui="pull-right">
                    <p class="bag"><span style="width: 200px;">包裹总数【<span class="danger">@{{batchData.bag}}</span>】</span>已扫描【<span class="danger package_bag_total">@{{batchData.scanBag}}</span>】</p>
                    <p class="product"><span style="width: 200px;">产品总数【<span class="danger">@{{batchData.products}}</span>】</span>已扫描【<span class="danger package_product_total">@{{batchData.scanProducts}}</span>】</p>
                    <p>&nbsp;</p>
                </div>
            </div>
            <div class="scanning-info">
                <a :class="['subnav',screen=='all'?'active':'']" @click="screen='all'">全部（ @{{bagList.length}} ）</a>
                <a :class="['subnav',screen=='scan'?'active':'']" @click="screen='scan'">已扫描（ @{{screenNum.scan}} ）</a>
                <a :class="['subnav',screen=='no_scan'?'active':'']" @click="screen='no_scan'">未扫描（ @{{screenNum.no_scan}} ）</a>
                <a :class="['subnav',screen=='que_scan'?'active':'']" @click="screen='que_scan'">未完全扫描（ @{{screenNum.que_scan}} ）</a>
                <div eui="pull-right">

                    <div class="eui-btn btn-list">
                    <span>
                        快递获取方式(<span class="kdfs">{{$send_model}}</span>)
                    </span>
                        <div class="btn-list-wrap">
                            <div class="btn-item eui-btn" eui="sm" onclick="setSendModel('old')">old</div>
                            <div class="btn-item eui-btn" eui="sm" onclick="setSendModel('new')">new</div>
                        </div>
                    </div>
                    <div class="eui-btn btn-list">
                    <span>
                        扫描方式(<span class="smfs">{{$scan_model}}</span>)
                    </span>
                        <div class="btn-list-wrap">
                            <div class="btn-item eui-btn" eui="sm" onclick="setScanModel('详细')">详细</div>
                            <div class="btn-item eui-btn" eui="sm" onclick="setScanModel('简化')">简化</div>
                        </div>
                    </div>

                    <a class="scanning-over eui-btn" eui="danger" href="javascript:;" @click="submitCode('over')">分拣结束</a>


                    <input name="box-number" class="eui-ipt z-search-ipt" value="" placeholder="请输入姓名或编码" type="text">
                    <a href="javascript:;" class="z-box-btn eui-btn" eui="warn"><i class="eui-icon-search"></i> 搜索</a>
                </div>
            </div>
            <ul class="scanning-box-list">
                <li v-for="data in bagList" :class="['close',currentBag.code==data.code?'active':'']" v-if="screen=='all' || screen==data.state">
                    <i class="batch-open eui-icon-barrage" @click="batchScan(data.code)" title="手动扫描"></i>
                    <a class="scanning-box" data-layer-title="包裹详情" :data-layer-url="'/myscan/packageDetail?batch_id='+batchData.batchId+'&one_code='+data.code" href="javascript:;"></a>
                    <label class="info">
                        <p class="name">姓名：<span>@{{data.name}}</span></p>
                        <p class="code">编码：<span>@{{data.code}}</span></p>
                        <p class="num">数量：<span>@{{data.num}}</span></p>
                    </label>
                </li>
                <li style="display: block;" class="li-info" eui="text-center" v-if="screen=='未扫描' && batchData.scanBag==bagList.length">
                    <h4>全部包裹扫描完毕</h4>
                </li>
                <li style="display: block;" class="li-info" eui="text-center" v-if="screen=='已扫描' && batchData.scanBag==0">
                    <h4>暂无已扫描包裹</h4>
                </li>
                <li style="display: block;" class="li-info" eui="text-center" v-if="screen=='未完全扫描' && batchData.scanBag==0">
                    <h4>暂无未完全扫描包裹</h4>
                </li>
            </ul>
        </div>
        <div :class="['batch-scan',replace.state?'':'hide']">
            <div class="head">
                <h2>@{{replace.title}}</h2>
                <a class="close" @click="replace.state=false" href="javascript:;">&times;</a>
            </div>
            <!-- 批量扫描 -->
            <div class="main" v-if="replace.type=='batch'">
                <label>
                    <span>包裹编码</span>
                    <input class="code eui-ipt" :value="currentBag.code" type="text" value="" readonly>
                </label>
                <label>
                    <span>产品编码</span>
                    <select class="eui-linkage" v-model="replace.editCode" style="width: 380px;">
                        <option v-for="item in currentBag.products"  :value="item.code">@{{item.code}}</option>
                    </select>
                </label>
                <label>
                    <span>数量</span>
                    <input class="num eui-ipt" maxlength="2" onkeyup="this.value=this.value.replace(/[^0-9-]+/,'');" v-model="replace.num" type="number" placeholder="请输入数量" value="">
                </label>
                <label>
                    <span></span>
                    <a class="submit eui-btn" @click="batchProduct" eui="primary" href="javascript:;">提交</a>
                    <a class="close eui-btn" @click="replace.state=false" href="javascript:;">取消</a>
                </label>
            </div>
            <!-- 更换货 -->
            <div class="main" v-if="replace.type=='change'">
                <label>
                    <span>新商品</span>
                    <input class="num eui-ipt" type="text" v-model="replace.editCode" value="">
                </label>
                <label>
                    <span>原商品</span>
                    <select class="eui-linkage" v-model="replace.scanCode" style="width: 380px;">
                        <option v-for="item in currentBag.products"  :value="item.code" v-if="item.isScan==false">@{{item.code}}</option>
                    </select>
                </label>
                <label>
                    <span></span>
                    <a class="submit eui-btn" @click="changeProduct" eui="primary" href="javascript:;">提交</a>
                    <a class="close eui-btn" @click="replace.state=false" href="javascript:;">取消</a>
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
                    scanType:'package',
                    // 发货id
                    fahuoId:"1",
                    // 数据采集id
                    sourceId:"1",
                    // 包裹总数-已扫描包裹总数
                    bag:"{{$scaning_data['package_num']}}",
                    scanBag:Number("{{$scaning_data['has_scan_package']}}"),
                    // 产品总数-已扫描产品总数
                    products:"{{$scaning_data['fashion_num']}}",
                    scanProducts:Number("{{$scaning_data['has_scan_fashion']}}"),
                    // 是否打印快递单
                    printKD:"快递到家",
                    // 需要扫描的包裹数据
                    needScanBag:[]
                },
                // 包裹列表数据
                bagList:[],
                // 当前包裹信息
                currentBag:{
                    name:'',
                    code:'',
                    num:'',
                    products:[]
                },
                // 编码格式配置
                config:{
                    bag:{
                        head:'XX',
                        leng:12
                    },
                    box:{
                        head:'PS',
                        leng:15
                    }
                },
                // 换货和批量数据
                replace:{
                    title:'',
                    state:false,
                    type:'',
                    scanCode:'',
                    editCode:'',
                    num:1
                },
                // 列表筛选
                screen:'all',
                screenNum:{
                    scan:0,
                    no_scan:0,
                    que_scan:0
                },
                scan_mode:'{{$scan_model}}'
            },
            methods:{
                // 扫码回调, 提交前校验
                scanBefore(code,simple){
                    // 如果扫over
                    if(code=='OVER') return this.submitCode('over');
                    // 检查编码规格---并分类
                    var type=this.checkCode(code);
                    if(type=='bag'){
                        // 如果是包裹编码---检查是否在当前批次---是此批次返回需要扫描的产品
                        const need=this.checkBatch(code);
                        if(need!=false){
                            // 定位包裹---初始化包裹产品数据
                            this.activeBag(need);
                            // 包裹正常---提交
                            this.submitCode(code);
                            if(this.scan_mode=='简化') this.simpleScan(code);
                        };
                    }else if(type=='product'){
                        // 扫描商品校验
                        const state=this.checkProduct(code);
                        if(state===true || state==='print'){
                            // 商品正常---提交
                            this.submitCode(code,state,simple);
                        }else if(state==='noHas'){
                            this.error('清单内没有此产品');
                            // 不存在此商品---换货
                            // const that=this;
                            // // 关闭扫描服务
                            // scanState=false;
                            // eui.popover({
                            //     type:'confirm',
                            //     style:'warn',
                            //     info:'当前清单无此产品, 是否需要更换产品?',
                            //     okaycall(){
                            //         // 开启换货窗口
                            //         that.replace.title='更换产品';
                            //         that.replace.scanCode=code;
                            //         that.replace.state=true;
                            //         that.replace.editCode=code;
                            //         that.replace.type='change';
                            //     },
                            //     cancelcall(){
                            //         // 解开扫描服务
                            //         scanState=true;
                            //     }
                            // });
                        }
                    };
                },
                // 检查编码规格
                checkCode(code){
                    const head=code.substring(0,2);
                    if(head==this.config.bag.head){
                        // 检查扫码编码
                        if(code.length!=this.config.bag.leng){
                            return this.error('包裹编码长度不正确');
                        }
                        return 'bag';
                    }else{
                        // 扫到产品---检查是否有扫包裹编码
                        if(this.currentBag.code==''){
                            this.error('请先扫描包裹清单');
                            return false;
                        };
                        return 'product';
                    };
                },
                // 校验批次
                checkBatch(code){
                    let isBatch=false;
                    let data={};
                    // 去需要扫的集合里找有无此包裹编码
                    this.batchData.needScanBag.forEach((n,i)=>{
                        if(n.one_code==code){
                        // 如果在此批次---获取包裹该扫描的数据
                        isBatch=true;
                        data=n;
                        return;
                    }
                });
                    if(!isBatch){
                        this.error('此包裹不在当前批次');
                        return isBatch;
                    };
                    // 校验成功,返回该包裹需要扫描的数据
                    return data;
                },
                // 获取包裹信息, 设定为当前包裹
                activeBag(need){

                    this.bagList.forEach((n,i)=>{
                        if(n.code==need.one_code){
                        prevKD([n.code]);
                        if(!n.isScan){
                            if(n.num==0){
                                // 如果此包裹没计数过---以扫包裹+1
                                this.batchData.scanBag+=1;
                                // 调用快递单预打印

                                prevKD([n.code]);
                            };
                            n.isScan=true;
                            // 初始化包裹产品信息
                            if(n.products.length==0){
                                need.will_scan_data.forEach(nd=>{
                                    for(let i=1;i<=nd.num;i++){
                                    n.products.push({
                                        code:nd.code+nd.size,
                                        isScan:false
                                    });
                                };
                            });
                            };
                        };
                        // 赋值给当前包裹信息
                        return this.currentBag=n;
                    }
                });
                },
                // 检查产品
                checkProduct(code,batch){
                    // batch---是否批量
                    var state=false;
                    var isHas=false;
                    // 剩余该扫描产品数量
                    var noScanNum=0;
                    var batchNum=0;
                    if(this.currentBag.num>=this.currentBag.products.length){
                        this.error('该包裹已装满');
                        return false;
                    };
                    this.currentBag.products.forEach(n=>{
                        if(n.code==code){
                        isHas=true;
                        // 单次扫描校验
                        if(n.isScan==false && state==false && !batch){
                            n.isScan=true;
                            state=true;
                            // 更新已扫描商品统计
                            this.batchData.scanProducts+=1;
                            // 更新当前包裹num
                            this.currentBag.num=Number(this.currentBag.num)+1;
                        };
                        // 批量校验
                        if(batch && n.isScan==false && state==false){
                            batchNum+=1;
                        };
                    };
                    if(!n.isScan) noScanNum+=1;
                });
                    if(isHas==false){
                        // 该产品不在清单内!
                        this.error('该产品不在清单内');
                        return 'noHas';
                    };
                    if(state==false && !batch){
                        // 该产品已装满!
                        this.error('该产品已装满');
                        return false;
                    };
                    // 批量扫描数量校验
                    if(this.replace.num>batchNum && batch){
                        this.error('数量不正确, 该产品缺'+batchNum+'件');
                        // 修正数量
                        this.replace.num=batchNum;
                        return false;
                    };
                    // 剩余扫描数如果是0, 打印单子
                    if(noScanNum==0) return 'print';
                    // 商品正常
                    return true;
                },
                // 扫码提交
                submitCode(code,print,simple){
                    const that=this;
                    // 如果是简易扫描，使用同步，常规扫描使用异步
                    if(simple=='simple'){
                        simple=false;
                    }else{
                        simple=true;
                    };
                    console.log(simple);
                    $.ajax({
                        type: "get",
                        url: "/scan2.php",
                        data: {message:code,client_id:that.batchData.userId,batch_id:that.batchData.batchId,scan_type:that.batchData.scanType},
                        dataType: "json",
                        async: false,
                        success(data){
                            console.log(data);
                            if(data.code==1){
                                that.isScanProducts(data.data.collection_data);
                                if(print==='print'){
                                    // 打印产品清单
                                    printFashionList([that.currentBag.code]);

                                    // 打印快递单
                                    if(that.batchData.printKD=='快递到家') printKDList([that.currentBag.code]);

                                    // 当前包裹状态改为-已扫描
                                    that.screenTotal("scan");
                                    console.log('装包完成-打印清单');
                                    $.ajax({
                                        type: "get",
                                        url: "/scan2.php",
                                        data: {message:'over',client_id:that.batchData.userId,batch_id:that.batchData.batchId,scan_type:that.batchData.scanType},
                                        dataType: "json",
                                        success(res){
                                            if(res.code==1){
                                                // 更新以扫产品
                                            }
                                        }
                                    });
                                }
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
                // 开启换货
                openChange(){
                    this.replace.title='更换产品';
                    this.replace.scanCode='';
                    this.replace.state=true;
                    this.replace.editCode='';
                    this.replace.type='change';
                    scanState=false;
                },
                // 更改商品
                changeProduct(){
                    scanState=true;
                    const that=this;
                    if(that.replace.editCode=='') return;
                    $.ajax({
                        type: "get",
                        url: "/Behind/SendGoods/scanError",
                        data: {scan_sn:that.currentBag.code, o_fashion_code:that.replace.scanCode,r_fashion_code:that.replace.editCode},
                        async:false,
                        dataType: "json",
                        success(data){
                            console.log(data);
                            if(data.code==1){
                                // 关闭对话框
                                that.replace.state=false;
                                eui.prompts('更换成功!');
                                let state=false;
                                let isScanNum=0;
                                that.currentBag.products.forEach(n=>{
                                    if(n.code==that.replace.scanCode && n.isScan==false && state==false){
                                    // 显示替换的商品编码
                                    // n.code=that.replace.editCode;
                                    n.isScan=true;
                                    state=true;
                                };
                                if(n.isScan==true) isScanNum+=1;
                            });// 更新已扫描商品统计
                                that.batchData.scanProducts+=1;
                                // 更新当前包裹num
                                that.currentBag.num=Number(that.currentBag.num)+1;
                                // 提交
                                let statePrint=false;
                                // 如果装满了调打印
                                if(that.currentBag.products.length==isScanNum){
                                    statePrint='print';
                                    that.screenTotal("scan");
                                };
                                that.submitCode(that.replace.scanCode,statePrint);
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
                // 批量扫描
                batchScan(code){
                    this.replace.editCode='';
                    this.replace.title="手动扫描";
                    this.replace.state=true;
                    this.replace.type='batch';
                    this.replace.num=1;
                    // 当前包裹获取焦点
                    this.scanBefore(code);
                },
                // 批量扫描提交
                batchProduct(){
                    // 校验产品
                    let state=this.checkProduct(this.replace.editCode,true);
                    if(!state || this.replace.num<1) return;
                    // 批量扫描改扫描枪方法
                    for(let i=0;i<this.replace.num;i++) this.scanBefore(this.replace.editCode);
                    this.replace.state=false;
                    // let that=this;
                    // $.ajax({
                    //     type: "get",
                    //     url: "/Behind/SendGoods/batchSanPackage",
                    //     data: {package_code:that.currentBag.code, fashion_code:that.replace.editCode,num:that.replace.num},
                    //     dataType: "json",
                    //     success(data){
                    //         console.log(data);
                    //         if(data.code==1){
                    //             // 关闭对话框
                    //             that.replace.state=false;
                    //             eui.prompts('提交成功!');
                    //             let i=0;
                    //             that.currentBag.products.forEach(n=>{
                    //                 if(n.code==that.replace.editCode && n.isScan==false && i<=that.replace.num){
                    //                     n.isScan=true;
                    //                     i++;
                    //                 }
                    //             });
                    //             // 更新已扫描商品统计
                    //             that.batchData.scanProducts+=Number(that.replace.num);
                    //             // 更新当前包裹num
                    //             that.currentBag.num=Number(that.currentBag.num)+Number(that.replace.num);
                    //             mp3Success.play();
                    //         }else{
                    //             that.error(data.msg);
                    //             eui.popover({
                    //                 style:'warn',
                    //                 info:data.msg
                    //             });
                    //         }
                    //     },
                    //     error(){
                    //         // 异常
                    //         that.error('系统异常!');
                    //         eui.popover({
                    //             style:'danger',
                    //             info:'系统异常!'
                    //         });
                    //     }
                    // });
                },
                // 清空包裹
                clearBag(code){
                    const that=this;
                    eui.popover({
                        type:'confirm',
                        style:'danger',
                        info:'确定要清空包裹？？？',
                        okaycall(){
                            $.ajax({
                                type: "get",
                                url: "{{route('admin.myscan.index.clear_package')}}",
                                data: {one_code:code,batch_id:that.batchData.batchId},
                                dataType: "json",
                                success: function(data){
                                    if(data.code==0){
                                        that.currentBag.products.forEach(n=>{
                                            n.isScan=false;
                                    });
                                        that.currentBag.num=0;
                                    };
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
                // 以扫产品校验
                isScanProducts(code){
                    // 清空本地已扫描数据
                    this.currentBag.products.forEach(n=>{
                        n.isScan=false;
                });
                    // 倒入服务器已扫描数据
                    let list=code;
                    this.currentBag.num=list.length;
                    list.forEach(n=>{
                        let state=true;
                    this.currentBag.products.forEach(nn=>{
                        if(nn.isScan==false && nn.code==n && state){
                        state=false;
                        nn.isScan=true;
                    }
                });
                });
                },
                // 手动打印、缺货打印
                printOrder(con){
                    // 分类统计
                    this.screenTotal("que_scan");
                    const that=this;
                    printFashionListS([that.currentBag.code]);///打印产品清单
                    if(that.batchData.printKD=='快递到家')setTimeout( que_printKDList([that.currentBag.code],con), 2000);
                },
                // 计算分类数量
                screenTotal(n){
                    this.currentBag.state=n;
                    switch(n){
                        case 'scan':
                            this.screenNum.scan++;
                            break;
                        case 'que_scan':
                            this.screenNum.que_scan++;
                            break;
                    };
                    this.screenNum.no_scan--;
                },
                // 简易扫描
                simpleScan(code){
                    let pd=this.currentBag.products;
                    pd.forEach(n=>{
                        this.scanBefore(n.code,'simple');
                });
                },
                // 报错
                error(msg){
                    mp3Error.play();
                    eui.prompts(msg+'!');
                    console.log(msg);
                    return false;
                }
            },
            created(){
                // 赋值客户端id为当前时间戳
                this.batchData.userId="{{$user}}";
                // 需要扫描的包裹集合
                this.batchData.needScanBag=<?php
                echo $scaning_data['will_print_package'];
                ?>;
                const that=this;

                $.ajax({
                    url:"{!!route('admin.myscan.index.scan_list',['batch_id'=>$batch_data['batch_id'],'batch_source'=>$batch_data['batch_source']])!!}",
                    dataType:'json',
                    success:function(res){
                        res.data.no_scan.forEach(function(n,i){
                            that.bagList.push({
                                name:n.name,
                                code:n.one_code,
                                num:n.num,
                                state:'no_scan',
                                products:[],
                            });
                            that.screenNum.no_scan++;
                        });
                        res.data.que_scan.forEach(function(n,i){
                            that.bagList.push({
                                name:n.name,
                                code:n.one_code,
                                num:n.num,
                                state:'que_scan',
                                products:[],
                            });
                            that.screenNum.que_scan++;
                        });
                        res.data.scan.forEach(function(n,i){
                            that.bagList.push({
                                name:n.name,
                                code:n.one_code,
                                num:n.num,
                                state:'scan',
                                products:[],
                            });
                            that.screenNum.scan++;
                        });
                    }
                })

            }
        });
        // 绑定扫码枪回调
        Sweep.call=code=>{
            // 扫码数据导入vue
            if(scanState) return scanApp.scanBefore(code.toUpperCase());
            mp3Error.play();
        };
        // 测试用---模拟扫描
        function scan(code){
            if(scanState) return scanApp.scanBefore(code.toUpperCase());
            mp3Error.play();
        };
        // 搜索箱号---定位
        $('.z-box-btn').click(function(){
            var s=$(this).siblings('.z-search-ipt').val();
            if(!s) return;
            var list=$(this).parents('.scanning-wrap').find('.scanning-box-list li');
            list.removeClass('danger');
            list.each(function(){
                var code='';
                if(s.substring(0,2)=='XX'){
                    // 搜索编码
                    code=$(this).find('.code span').text();
                }else{
                    // 搜索姓名
                    code=$(this).find('.name span').text();
                }
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
    </script>














    <script>
        // 打印-------------------------------------------------------------------------------------

        var LODOP; //声明为全局变量


        ///TODO 打印产品清单  one_code 为学生的条码 为数组  示例 one_code=['XX6b5ff29202','XX6b5ff2920f']



        function printFashionList(one_code) {
            // alert('模拟打印');
            var batch_id="{{$batch_data['batch_id']}}";

            $.ajax({
                type: "get",
                url: "{{route('admin.myscan.index.print_list_scan')}}",
                data: {batch_id:batch_id, one_code:one_code,type:'fashion_list'},
                dataType: "json",
                async:true,
                success: function(data){
                    console.log(data);
                    if(data.code==0){
                        if(!data.data.scan_huan.length){
                            printFashionListPr(data.data);
                        }
                    }else{
                        eui.prompts('没有需要打印的');
                    }
                }
            });
        }
        /////打印产品清单

        /////打印产品清单
        function printFashionListPr(data) {
            LODOP=getLodop();

            var iPrinterCount=LODOP.GET_PRINTER_COUNT();///打印机个数
            for(var i=0;i<iPrinterCount;i++){

                print_name=LODOP.GET_PRINTER_NAME(i);
                if(print_name=='GP-L80160 Series'){
                    LODOP.SET_PRINTER_INDEX(i);
                    break;
                }

            };

//3 2300
            //
            // $.each(data,function (keys ,vv) {
            LODOP.SET_PRINT_PAGESIZE(0,1000,data.print_lengh,"A4");
            CreateOnePage(data);
            //});
            ///LODOP.PREVIEW();
            LODOP.PRINT();

        }

        function printFashionListS(one_code) {
            var batch_id="{{$batch_data['batch_id']}}";
            var batch_source = "{{$batch_data['batch_source']}}";
            $.ajax({
                type: "get",
                url: "{{route('admin.myscan.index.print_list_scan')}}",
                data: {batch_id:batch_id,one_code:one_code,type:'fashion_list',batch_source:batch_source},
                dataType: "json",
                async:true,
                success: function(data){

                    //console.log(data);
                    if(data.code==0){
                        printFashionListPr(data.data);
                    }else{
                        eui.prompts('没有需要打印的');
                    }
                }
            });
        }

        function CreateOnePage(data){
            LODOP.NewPage();
///////////商品内容
            var num=302;
//console.log(PRO);
            ////////////产品清单
            if(data.sell_fashions.length){
                LODOP.ADD_PRINT_TEXT(250,100,100,20,"产品清单");
                LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
                LODOP.SET_PRINT_STYLEA(0,"FontSize",13);
                LODOP.ADD_PRINT_TEXT(280,-2,596,20,"***********************************************");
                $.each(data.sell_fashions,function (key,v) {
                    LODOP.ADD_PRINT_TEXT(num,2,210,20,v.fashion_name +" X"+v.fashion_num + "      规格 "+v.fashion_size+"\r\n编码:"+v.fashion_code);
                    LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
                    LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
                    LODOP.ADD_PRINT_TEXT(num+28,-8,606,20,"*****************************************************");
                    num=num+50;
                });

            }


            ////////////产品清单


///////////////////换货清单
            if(data.scan_huan.length){


                LODOP.ADD_PRINT_TEXT(num+12,100,100,20,"换货清单");
                LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
                LODOP.SET_PRINT_STYLEA(0,"FontSize",13);
                LODOP.ADD_PRINT_TEXT(num+37,-2,596,20,"***********************************************");
                num=num+52;

                $.each(data.scan_huan,function (key,v) {
                    LODOP.ADD_PRINT_TEXT(num,2,210,20,v);
                    LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
                    LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
                    LODOP.ADD_PRINT_TEXT(num+28,-8,606,20,"*****************************************************");
                    num=num+50;
                });
            }


///////////////////换货

            ///////////////////缺货清单
            if(data.scan_que.length){
                LODOP.ADD_PRINT_TEXT(num+12,100,100,20,"缺货清单");
                LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
                LODOP.SET_PRINT_STYLEA(0,"FontSize",13);

                LODOP.ADD_PRINT_TEXT(num+37,-2,596,20,"***********************************************");
                num=num+52;

                $.each(data.scan_que,function (key,v) {
                    LODOP.ADD_PRINT_TEXT(num,2,210,20,v.name +" X"+v.num + "      规格 "+v.size+"\r\n编码:"+v.code);
                    LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
                    LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
                    LODOP.ADD_PRINT_TEXT(num+28,-8,606,20,"*****************************************************");
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


            LODOP.ADD_PRINT_TEXT(220,38,241,20,data.note);
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",7);
            LODOP.ADD_PRINT_TEXT(77,1,281,25,"哈芙琳—校服专家 高端定制");
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",13);
            LODOP.SET_PRINT_STYLEA(0,"Alignment",2);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(118,45,241,24,data.school_name);
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

            //LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(136,45,242,20,data.grade_name);
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(155,45,239,20,data.class_name);
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(173,53,233,20,data.name);
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_TEXT(192,1,67,20,"VIP号：");
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.ADD_PRINT_TEXT(191,53,229,20,data.vip);
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.ADD_PRINT_IMAGE(9,115,51,49,"<img border='0' src='http://halfrin.com/images/hha.jpg' />");
            LODOP.SET_PRINT_STYLEA(0,"Stretch",1);

/////////////////////临时注释
            LODOP.ADD_PRINT_TEXT(num+290,-41,328,40,"哈芙琳感谢亲对我们的支持，有问题请与我们联系");
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.SET_PRINT_STYLEA(0,"Alignment",2);
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

            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.ADD_PRINT_TEXT(num+260,90,183,20,"banchengren.taobao.com/");
            LODOP.ADD_PRINT_TEXT(num+260,0,100,20,"哈芙琳校园服饰：");
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");
            LODOP.SET_PRINT_STYLEA(0,"FontName","微软雅黑");


            /////////////////////临时注释



        };
        //////打印产品清单


        /////打印产品清单



        //////打印产品清单

        //TODO  打印快递清单 one_code 为学生的条码 为数组  示例 one_code=['XX6b5ff29202','XX6b5ff2920f']

        function printKDList(one_code) {
            var batch_id="{{$batch_data['batch_id']}}";

            $.ajax({
                type: "post",
                url: "{{route('admin.myscan.index.print_list')}}",
                data: {batch_id:batch_id, one_code:one_code,print_type:'k_d_list'},
                dataType: "json",
                success: function(data){
                    console.log(data);
                    if(data.code==0){
                        printKDListPr(data.data);
                    }else{
                        eui.prompts('没有需要打印的');
                    }
                }
            });
        }
        ////打印快递单
        function printKDListPr(data) {
            LODOP=getLodop();

            var iPrinterCount=LODOP.GET_PRINTER_COUNT();///打印机个数
            for(var i=0;i<iPrinterCount;i++){

                print_name=LODOP.GET_PRINTER_NAME(i);
                if(print_name=='QR-588 LABEL'){
                    LODOP.SET_PRINTER_INDEX(i);
                    break;
                }

            };

            CreateOneFormPage(data);
            //CreateOneFormPage(data);
            //LODOP.PREVIEW();
            // LODOP.PRINT();
        }
        ////打印快递单
        function CreateOneFormPage(data){
            // var obj=$('.print_area');
            $.each(data,function (k,v) {
                $.each(v,function (kk,vv) {
                    //console.log();
                    LODOP.NewPage();
                    LODOP.ADD_PRINT_HTM(0,0,400,700,vv);
                });
                LODOP.PRINT();
            });


            /*for (i = 1; i < 8; i++) {
                LODOP.NewPage();
                LODOP.ADD_PRINT_HTM(88,200,350,600,document.getElementById("kuaidi0").innerHTML);
            }*/
        };



        // 缺货打印-----产品清单
        function que_printFashionList(one_code) {
            var batch_id="{{$batch_data['batch_id']}}";
            var batch_source = "{{$batch_data['batch_source']}}";

            $.ajax({
                type: "get",
                url: "/Behind/SendGoods/printListScan",
                data: {batch_id:batch_id, one_code:one_code,type:'fashion_list',data_type:data_type},
                dataType: "json",
                success: function(data){
                    if(data.code==1){
                        printFashionListPrS(data.data);
                    }else{
                        eui.prompts('没有需要打印的');
                    }
                }
            });
        }
        // 快递单
        function que_printKDList(one_code,con) {
            var batch_id="{{$batch_data['batch_id']}}";
            var batch_source = "{{$batch_data['batch_source']}}";
            $.ajax({
                type: "post",
                url: "{{route('admin.myscan.index.print_list')}}",
                data: {batch_id:batch_id, one_code:one_code,print_type:'k_d_list',con:con,batch_source:batch_source},
                dataType: "json",
                async:true,
                success: function(data){
                    if(data.code==0){
                        printKDListPr(data.data);
                    }else{
                        eui.prompts('没有需要打印的');
                    }
                }
            });
        }


        //// TODO 设置快递获取方式 send_model传 old  或new
        /////设置快递获取方式
        function setSendModel(send_model) {
            $.ajax({
                type: "post",
                url: "/Behind/SendGoods/setSendModel",
                data:{send_model:send_model},
                dataType: "json",
                success: function(res){
                    eui.prompts(res.msg);
                    if(res.code==1){
                        $('.kdfs').text(send_model);
                    }
                }
            });
        }

        //// TODO 设置扫描模式 scan_model传 详细  或简化
        /////设置快递获取方式
        function setScanModel(scan_model) {
            $.ajax({
                type: "post",
                url: "/Behind/SendGoods/setScanModel",
                data: {scan_model:scan_model},
                dataType: "json",
                success: function(res){
                    eui.prompts(res.msg);
                    if(res.code==1){
                        $('.smfs').text(scan_model);
                        scanApp.scan_mode=scan_model;
                    }
                }
            });
        }



        // TODO  预先提取快递单   条形码 值  此函数在扫描包裹码后调用  异步 调用
        function prevKD(one_code) {
            var batch_id="{{$batch_data['batch_id']}}";

            $.ajax({
                type: "get",
                url: "{{route('admin.myscan.index.prev_k_d')}}",
                data: {batch_id:batch_id, one_code:one_code,type:'k_d_list'},
                dataType: "json",
                async:true,
                success: function(data){

                }
            });
        }



        ////toDO 缺货数据转为数据采集
        $('.scanning-que').click(function () {
            order_data_cate_id='{$order_data_cate_id}';
            batch_id="{$batch_data['id']}";
            order_xiao_shou_fa_huo_id="{$xiao_shou_fa_huo_id}";
            $.ajax({
                type: "get",
                url: "/Behind/SendGoods/convertQueDataWithNewBatch",
                data: {order_data_cate_id:order_data_cate_id, batch_id:batch_id, order_xiao_shou_fa_huo_id:order_xiao_shou_fa_huo_id},
                dataType: "json",
                success: function(data){
                    eui.prompts(data.msg);
                }
            });
        })
    </script>
@endsection