// 2018-07-23
// 扫码枪封装
var Sweep={
    // 存储扫描编码
    code:'',
    // 间隔1500毫秒则清空code重新记录
    delay:1000,
    // 触发发送事件的code码,默认回车键13
    sedCode:13,
    // code下限长度
    leng:4,
    // 发送
    call(code){
        console.log(code);
    },
    // 记录按键时间戳
    time:null,
    // 运行键盘按钮
    runTime(e){
        var keyCode = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
        //回车事件处理 -> 扫描枪扫完条码后会自动执行一次回车事件
        if(keyCode == Sweep.sedCode && Sweep.code.length>=Sweep.leng){
            //执行完条码处理后，清空条码
            Sweep.call(Sweep.code);
            Sweep.code='';
        }else{
            //扫码过程中，条码keyCode中会出现功能键码，因此需要屏蔽掉
            if(
                keyCode != 8 && 
                keyCode != 9 && 
                keyCode != 12 && 
                keyCode != 16 && 
                keyCode != 17 && 
                keyCode != 18 && 
                keyCode != 20 && 
                keyCode != 32 && 
                keyCode != 37 && 
                keyCode != 38 && 
                keyCode != 39 && 
                keyCode != 40 && 
                keyCode != 45 && 
                keyCode != 91 && 
                keyCode != 93 && 
                keyCode != 116 && 
                keyCode != 117 && 
                keyCode != 118 && 
                keyCode != 119 && 
                keyCode != 120 && 
                keyCode != 121 && 
                keyCode != 122 && 
                keyCode != 123 && 
                keyCode != 186 && 
                keyCode != 187 && 
                keyCode != 188 && 
                keyCode != 189 && 
                keyCode != 190 && 
                keyCode != 191 && 
                keyCode != 192 && 
                keyCode != 219 &&
                keyCode != 220 && 
                keyCode != 221
            ){  
                // 获取或赋予上一次触发时间
                if(!Sweep.time) Sweep.time=new Date().getTime();
                // 获取时间差
                var difference=new Date().getTime()-Sweep.time;
                // 时间差小于1.5秒,则判断为重新扫描
                if(difference>Sweep.delay){
                    Sweep.code='';
                }
                Sweep.code+=e.key;
                // 重置Sweep.time为当前时间
                Sweep.time=new Date().getTime();
            }
        }
    },
    // WebSocket模块
    ws:null,
    wsUrl:'',
    // 初始化,配置填充与重新绑定
    init(dt){
        if(dt){
            Sweep.wsUrl=dt.wsUrl;
            Sweep.delay=dt.delay||1500;
            Sweep.sedCode=dt.sedCode||13;
        };
        $(document).unbind('keyup');
        $(document).on('keyup',function(e){
            //时间戳设置初始值
            e = e||event;
            Sweep.runTime(e);
        });
        if(Sweep.wsUrl) Sweep.wx=new WebSocket(Sweep.wsUrl);
    }
};

// 判断浏览器是否有焦点
var widFocus=()=>{
    $('body').append(`
        <div class="danger-shadow" style="
            box-shadow: -20px 20px 50px red inset;
            position: fixed;
            width: 120%;
            height: 100%;
            left: 0;
            top: 0;
            z-index: 10000000;
            display: none;
        ">
        </div>
    `);
    if($(window).is(":focus")==false) $('.danger-shadow').fadeIn(200);
    $(window).one('click',function(){
        $('.danger-shadow').fadeOut(200);
    });
    $(window).focus(function(){
        // 获取焦点
        $('.danger-shadow').fadeOut(200);
    });
    $(window).blur(function(){
        // 失去焦点
        $('.danger-shadow').fadeIn(200);
    });
};