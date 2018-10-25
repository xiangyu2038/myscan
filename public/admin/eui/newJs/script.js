// 本地存储模块
let userStorage={};

// 初始化存储数据
if(localStorage.userStorage) userStorage=JSON.parse(localStorage.userStorage);

// 添加数据
const addStorage=(k,v)=>{
    const key=k;
    userStorage[key]=v;
    // 更新缓存
    updateStorage();
};
// 删除数据
const reStorage=k=>{
    const key=k;
    delete userStorage[key];
    // 更新缓存
    updateStorage();
};
// 更新缓存
const updateStorage=()=>localStorage.userStorage=JSON.stringify(userStorage);

// 菜单开关--------------------------------
$('.z-header .z-menu').click(function(){
    if($('.z-header .z-menu i').hasClass('z-open')){
        // 打开菜单
        $('.z-header .z-menu i').removeClass('z-open');
        $('.z-nav-list').removeClass('z-open').addClass('z-sub');
        $('.z-main-wrap').removeClass('z-open');
        $('.z-nav-wrap').removeClass('z-open');
        addStorage('menu',true);
    }else{
        // 关闭菜单
        $('.z-header .z-menu i').addClass('z-open');
        $('.z-nav-list').addClass('z-open').removeClass('z-sub');
        $('.z-main-wrap').addClass('z-open');
        $('.z-nav-wrap').addClass('z-open');
        addStorage('menu',false);
    }
});

// 左边菜单，点击主菜单跳转第一个子a标签，如果有多个子菜单则不做操作
$('.z-nav-list li > a').click(function(){
    const sub=$(this).siblings('.z-subnav').find('a');
    if(sub.length==1){
        sub.eq(0).click();
    };
});

// 退出登录----------------------------
$('.z-user-out').click(function(){
    const out=window.location.host+$(this).data('out');
    eui.popover({
        type:'confirm',
        style:'danger',
        info:'您确定要退出登录？',
        okaytext:'退出',
        canceltext:'算了',
        okaycall(){
            window.location.href='http://'+out;
        }
    });
});

// top问候语-------------------------------------------
const greetings={
    now:new Date(),
    good(){
        const h=this.now.getHours();
        if(h>6 && h<11) return '上午好，';
        if(h>11 && h<13) return '中午吃饱饭，';
        if(h>13 && h<18) return '下午好，';
        if(h>18) return '下班早回家，';
    }
}
$('.z-greetings').text(greetings.good());

// 菜单----------------------------------------
$('.z-nav-list li').click(function(){
    $(this).siblings().removeClass('on');
    $(this).addClass('on');
});

// 禁用按钮
$(document).on('click','.disabled',function(e){
    e.preventDefault();
    return false;
});

// 多窗口tab--------------------------------------------------------

// 检测标签页是否太多
const tabDetection=()=>{
    const tabW=$('.z-main-tab').innerWidth()-50;
    const listW=$('.z-main-tab-list').width();
    if(listW>tabW){
        return true;
    };
    return false;
};

// 页头返回按钮数据
let pageRecord=[];
eui.tabwin({
    active:'active',
    menu:'.z-main-tab-list',
    main:'.z-main-context',
    max:99,
    start(){
        // 检测是否打开控制器
        if(tabDetection()){
            $('.z-main-tab-btn').fadeIn(300);
            $('.z-main-tab').addClass('on');
            $('.z-main-tab-list').animate({'left':$('.z-main-tab').innerWidth()-50-$('.z-main-tab-list').width()-20},200);
        };
        eui.loading({
            el:'.eui-tabwin-main.active',
            state:true
        });
        eui.progress({
            el:'.eui-tabwin-main.active',
            bg:'none'
        });
    },
    end(dt){
        eui.init('.eui-form');
        tableAuto(dt);
        eui.loading({
            el:$('.eui-tabwin-main.active'),
            state:false
        });
        eui.progress({
            el:$('.eui-tabwin-main.active'),
            max:100
        });
    },
    switch(){
        // 切换时,如果开启了控制器,且焦点页签不可见,自动移动到焦点页签位置
        if(tabDetection()){
            // 可视范围
            const show=$('.z-main-tab').innerWidth()-50+parseInt($('.z-main-tab-list').css('left'));
            // 焦点页签位置与宽度
            const lf=$('.eui-tabwin-label.active').position().left;
            const w=$('.eui-tabwin-label.active').innerWidth();
            if(show<(lf+w)){
                $('.z-main-tab-list').animate({'left':-(lf+w-show)},200);
            };
        };
    },
    error(obj){
        loadError('error',obj);
    },
    disable:'disabled',
    only:'我的主页',
    initial:{
        title:'我的主页',
        url:'/Behind/Home/index',
        // url:"/Behind/Custom/sales_order",
        end(){
            // 第一个页面加载完后，初始化菜单状态
            if(userStorage.menu){
                $('.z-header .z-menu i').removeClass('z-open');
                $('.z-nav-list').removeClass('z-open').addClass('z-sub');
                $('.z-main-wrap').removeClass('z-open');
                $('.z-nav-wrap').removeClass('z-open');
            }else{
                $('.z-header .z-menu i').addClass('z-open');
                $('.z-nav-list').addClass('z-open').removeClass('z-sub');
                $('.z-main-wrap').addClass('z-open');
                $('.z-nav-wrap').addClass('z-open');
            }
        }
    },
    close(dt){
        // 添加页签删除记录
        pageRecord.push(dt);
        // 检测是否打开控制器
        setTimeout(function(){
            const tabW=$('.z-main-tab').innerWidth()-50;
            const listW=$('.z-main-tab-list').width();
            if(!tabDetection()){
                $('.z-main-tab-btn').fadeOut(300);
                $('.z-main-tab-list').animate({left:0},300);
                $('.z-main-tab').removeClass('on');
            };
        },100);
    }
});

// 搜索菜单功能-----------------------------------------------
$('.z-search input').keyup(function(){
    $('.z-search-list').empty();
    const v=$(this).val();
    if(v){
        $('.z-search-list').show(0);
        let li='';
        $('.z-nav-list [data-tabwin-open]').each(function(){
            if($(this).text().indexOf(v)>=0){
                li+='<li><a data-tabwin-open="'+$(this).data('tabwin-open')+'">'+$(this).text()+'</li>';
            };
        });
        if(li){
            $('.z-search-list').html(li);
        }else{
            $('.z-search-list').html('<li>没有搜索到！</li>');
        };
    }else{
        $('.z-search-list').hide(0);
    };
}).blur(function(){
    setTimeout(function(){
        $('.z-search-list').hide(0);
    },300);
});

// 弹出层-------------------------------
$(document).on('click','[data-layer-url]',function(){
    if($(this).hasClass('disabled')) return false;
    const tit=$(this).data('layer-title');
    const url=$(this).data('layer-url');
    const id=$(this).data('id');
    eui.layer({
        title:tit,
        url:url,
        id:id,
        body:$('.z-main-context').length ? '.z-main-context' : 'body',
        start(){
            eui.loading({
                el:'.eui-layer-main',
                state:true
            });
        },
        end(){
            eui.loading({
                el:'.eui-layer-main',
                state:false
            });
        },
        error(obj){
            loadError('error',obj);
        }
    });
});

// 搜索、分页功能--------------------------------------------
const zUpdate=function(obj){
    if(obj.find('.eui-tab-main .eui-tab-item.active').length) obj=obj.find('.eui-tab-main .eui-tab-item.active');
    const pt=obj.find('.z-tool form');
    let url='';
    if(obj.hasClass('eui-tab-item')){
        url=obj.attr('data-tab-url');
    }else{
        url=pt.attr('action')+'/tab/1';
    };
    let data='/';
    pt.find('select,input').each(function(){
        const type=$(this)[0].localName;
        if(type=='input' || type=='select'){
            // 普通input
            if($(this).attr('eui-val')){
                data+=$(this).attr('name')+'/'+$(this).attr('eui-val')+'/';
            }else if($(this).val()){
                data+=$(this).attr('name')+'/'+$.trim($(this).val())+'/';
            };
        }else if(type=='div'){
            const ipt=$(this).find('input');
            if(ipt.attr('eui-val')){
                data+=ipt.attr('name')+'/'+ipt.attr('eui-val')+'/';
            }else if(ipt.val()){
                data+=ipt.attr('name')+'/'+$.trim(ipt.val())+'/';
            };
        };
    });
    if(obj.hasClass('eui-layer')){
        obj.find('.eui-layer-content').load(url+data);
    }else{
        tabUpdate(url+data);
    };
};

// 列表搜索模块，获取搜索参数
$(document).on('click','.z-search-btn',function(){
    if($(this).parents('.eui-layer').length){
        zUpdate($(this).parents('.eui-layer'));
    }else{
        zUpdate($(this).parents('.eui-tabwin-main'));
    };
});

const getPage=(obj,n)=>{
    const pt=obj.parents('.eui-tabwin-main');
    const num=n || obj.data('pages-num');
    const old=pt.find('.eui-page .active').data('pages-num');
    const form=pt.find('.z-tool form');
    // 页码
    form.find('[name=current_page]').val(num);
    // 分割
    form.find('[name=per_page]').val(pt.find('.z-page-data .eui-select').val());
};

// 分页按钮
$(document).on('click','[data-pages-num]',function(){
    if($(this).hasClass('active')) return;
    getPage($(this));
    // 执行分页
    zUpdate($(this).parents('.eui-tabwin-main'));
});

// 分割 选择
$(document).on('change','.z-page-data .eui-select',function(){
    getPage($(this),1);
    // 执行分页
    zUpdate($(this).parents('.eui-tabwin-main'));
});

// 列表删除数据--------------------------------
const deleteList=(id,url,update)=>{
    if($(update).hasClass('disabled')) return;
    eui.popover({
        type:'confirm',
        style:'danger',
        info:'您确定要删除这条信息？',
        okaycall(){
            $.post(url,{id:id}, function (data) {
                if (data.state == 0) {
                    eui.prompts('删除成功！');
                    if(update){
                        if($(update).parents('.z-table-item-delete').length){
                            $(update).parents('.z-table-item-delete').remove();
                        }else{
                            $(update).parents('tr').remove();
                        };
                    };
                }else{
                    eui.prompts(data.content);
                }
            });
        }
    });
};

// 增改接口----------------------------------------------
// 获取表单数据，拼接键值对，序列化
const getForm=fm=>{
    const ipt=fm.find('.eui-ipt,.eui-select,.eui-textarea,.eui-checkbox,.eui-radio,.eui-switch,.eui-upload,.eui-date,.eui-tree-check,.eui-linkage');
    let data='';
    ipt.each(function(){
        // data-skip 跳过
        if($(this).attr('data-skip')=='') return;
        // name为空 跳过
        const nm=$(this).attr("name");
        if(!nm || nm=='file') return;

        let v=null;
        if($(this).attr('eui-val')!=undefined){
            v=$(this).attr('eui-val');
        }else if($(this).hasClass('eui-checkbox') || $(this).hasClass('eui-radio') || $(this).hasClass('eui-tree-check')){
            if($(this).prop('checked')){
                v=$.trim($(this).val());
            }else{
                return;
            }
        }else if($(this).hasClass('eui-upload')){
            const img=[]; 
            $(this).find('img').each(function(){
                const src=$(this).attr('src');
                const str=src.split(',')[0];
                if(str=='data:image/jpeg;base64') img.push(src);
            });
            if(img) v=img;
        }else if($(this).hasClass('eui-switch')){
            if($(this).prop('checked')){
                v=2;
            }else{
                v=1;
            }
        }else{
            v=$.trim($(this).val());
        };
        v=encodeURI(v);
        data+=nm+'='+v+'&';
    });
    return data;
}

// 状态控制-----------------------------------------------------
let ajaxStatus=false;
// 增、改ajax接口封装
const Ajax=dt=>{
    if(ajaxStatus) return;

    // 成功
    const suc=json=>{
        ajaxStatus=false;
        if(json.state==0){
            // 去除弹出层
            $('.eui-layer.on .eui-layer-close').click();
            tabUpdate();
            eui.popover({
                style:'done',
                info:json.content || '提交成功！'
            });
            eui.loading({
                el:'.eui-layer-main',
                state:false
            });
        }else{
            eui.popover({
                style:'danger',
                info:json.content || '提交失败！'
            });
            eui.loading({
                el:'.eui-layer-main',
                state:false
            });
            eui.loading({
                el:'.eui-layer.on',
                state:false
            });
        }
        if(dt.success) dt.success(json);
    };

    const err=()=>{
        ajaxStatus=false;
        eui.popover({
            style:'warn',
            info:'系统异常，请稍后再试！'
        });
        eui.loading({
            el:'.eui-layer.on',
            state:false
        });
        eui.loading({
            el:'.eui-layer-main',
            state:false
        });
    };

    ajaxStatus=true;
    if(dt.file){
        // 文件提交
        const datas=getForm($(dt.form || '.eui-form'));
        const formdata=new FormData();
        formdata.append('file', $(dt.file)[0].files[0]);
        formdata.append('datas', datas);
        $.ajax({
            url: dt.url,
            type: "post",
            data: formdata,
            //关闭序列化
            processData: false,
            cache : false,
            contentType: false,
            success(data){
                suc(data);
            },
            error(){
                err();
            }
        });
    }else{
        // 普通数据提交
        let data=dt.data || getForm($(dt.form || '.eui-form'));
        if(!data){
            eui.popover({
                style:'warn',
                info:'数据异常,提交失败！'
            });
            return ajaxStatus=false;
        };
        eui.loading({
            el:'.eui-layer-main',
            state:true
        });
        $.ajax({
            type:dt.type || 'POST',
            url:dt.url,
            data:data,
            dataType:'json',
            timeout: dt.timeout || 15000,
            success(data){
                console.log(data);
                suc(data);
            },
            error(){
                err();
            }
        });
    }
};

/**
 * 接口调用方法
 * Ajax({
 *      type    POST或GET，可不写，默认POST
 *      url     地址
 *      form    填写form表单的.class或#id
 *      success 成功回调
 *      error   失败回调
 * })
 *
 * 备注：用于弹出层提交数据，自动获取表单数据，成功or失败 自动处理后续动作，防止重复提交，可添加回调函数自定义回调事件
 */

//  刷新tab页------------------------
const tabUpdate=url=>{
    // 刷新
    if($('.eui-tabwin-main.active .eui-tab-main .eui-tab-item.active').length && $('.eui-tabwin-main.active .eui-tab-main .eui-tab-item.active').data('tab-url')){
        let page = url || $('.eui-tabwin-main.active .eui-tab-main .eui-tab-item.active').data('tab-url');
        // 刷新tab
        $('.eui-tabwin-main.active .eui-tab-main .eui-tab-item.active').empty();
        eui.loading({
            el:'.eui-tabwin-main.active .eui-tab-main .eui-tab-item.active',
            state:true
        });
        $('.eui-tabwin-main.active .eui-tab-main .eui-tab-item.active').load(page,function(){
            eui.init('.eui-select,.eui-date');
        });
    }else{
        let page = url || $('.eui-tabwin-main.active').attr('eui-tabwin-url');
        // 刷新tabwin
        const p=$('<p data-tabwin-update="'+page+'"></p>');
        $('.eui-tabwin-main.active').append(p);
        p.click().remove();
    }
}

// 数据状态修改----------------------------------------
let editStateS=false;
// 修改状态
const editState=(dt,url,text,style,btn)=>{
    if($(btn).hasClass('disabled')) return;
    if(editStateS) return;
    editStateS=true;
    if(typeof dt=='string' || typeof dt=='number') dt={id:dt};
    eui.popover({
        type:'confirm',
        style:style,
        info:'您确定要'+text+'吗？',
        okaycall(){
            $.ajax({
                type: 'POST',
                url: url,
                data: dt,
                dataType:'json',
                success: function (data) {
                    editStateS = false;
                    if (data.state == 0) {
                        eui.prompts('操作成功！');
                        tabUpdate();
                    } else {
                        eui.popover({
                            type: 'confirm',
                            style: 'warn',
                            info: '操作失败，请稍后再试！'
                        });
                    }
                },
                error: function () {
                    editStateS = false;
                }
            });
        },
        cancelcall(){
            editStateS=false;
        }
    });
}

// 校徽\图片查看-------------------------------------
$(document).on('click','.z-show-img',function(){
    eui.imgpopover({
        list:$(this).find('img')
    });
});

// 表单校验并绑定提交--------------------------------------
const submit=dt=>{
    eui.init('.eui-form');
    eui.validate({
        form:dt.form || '.eui-form',       // 填写form或父容器class或id
        submit:dt.btn || '.z-submit-btn',   // 填写提交按钮class或id
        position:'right',       // 提示窗出现的位置,默认bottom,可选right
        illegality:[
            '--请选择--',
            '请选择'
        ],
        succee(){
            // 提交
            eui.loading({
                el:'.eui-layer.on',
                state:true
            });
            // 提交
            if(dt.popover){
                eui.popover({
                    type:dt.popover.type,
                    info:dt.popover.info,
                    style:dt.popover.style,
                    okaycall(){
                        Ajax({
                            url:dt.url,
                            form:dt.form,
                            type:dt.type || 'POST',
                            file:dt.file,
                            timeout:dt.timeout,
                            success:dt.success,
                            error:dt.error
                        });
                    }
                });
            }else{
                Ajax({
                    url:dt.url,
                    form:dt.form,
                    type:dt.type || 'POST',
                    file:dt.file,
                    timeout:dt.timeout,
                    success:dt.success,
                    error:dt.error
                });
            }
        },
        error(){
            eui.prompts('表单未填写完整，请检查！');
        }
    });
}
/**
 * submit方法
 * dt.validate 为false情况下不开启表单校验
 * dt.from     表单
 * dt.url      提交地址
 * dt.btn      提交按钮
 */

//  tab二级页面
const tabLoad=el=>{
    eui.init('.eui-tab');
    // 主动加载内页
    // $(el).children('.eui-tab-item').each(function(i){
    //     const url=$(this).data('tab-url');
    //     if(url){
    //         eui.loading({
    //             el:$(this),
    //             state:true
    //         });
    //         $(this).empty().load(url,function(responseTxt,statusTxt,xhr){
    //             if(statusTxt=='success'){
    //                 eui.init('.eui-select,.eui-date');
    //             };
    //             if(statusTxt=='error'){
    //                 loadError('error',$(this));
    //             };
    //         });
    //     };
    // });
};

$(document).on('click','.eui-tab .eui-tab-menu .eui-tab-item',function(){
    var index=$(this).index();
    var item=$(this).parents('.eui-tab').find('.eui-tab-main .eui-tab-item');
    if(item.eq(index).attr('eui-tab-state')!='true' && item.eq(index).data('tab-url')){
        eui.loading({
            el:item.eq(index),
            state:true
        });
        item.eq(index).load(item.eq(index).data('tab-url')).attr('eui-tab-state','true');
    };
});

// 加载错误，提示
const loadError=(state,obj,text)=>{
    let html='';
    let error=`
        <div class="z-page-results error">
            <div class="z-page-results-main">
                <h2>错误</h2>
                <p>系统又在偷懒了！</p>
                <p>页面找不到了！一会再试试，或者去怒怼程序猿！</p>
            </div>
        </div>`;
    let success=`
        <div class="z-page-results success">
            <div class="z-page-results-main">
                <h2>成功</h2>
                <p>提交成功了！</p>
                <p>巴拉巴拉巴拉巴拉巴拉巴拉巴拉巴拉～</p>
            </div>
        </div>`;
    switch(state){
        case 'error':
            html=error;
            break;
        case 'success':
            html=success;
            break;
    };
    return obj.html(html);
}

// tab标签左右滚动-------------------------------------------------------
let tabLiseSet=null; // 定时器
$('.z-main-tab-btn').mousedown(function(){
    const tabW=$('.z-main-tab').innerWidth()-50;
    const listW=$('.z-main-tab-list').width();
    if(listW>tabW){
        let w=parseInt($('.z-main-tab-list').css('left'));
        if($(this).hasClass('eui-icon-return')){
            // 左
            tabLiseSet=setInterval(()=>{
                w+=10;
                if(w>0) return;
                $('.z-main-tab-list').css('left',w);
            },10);
        }else{
            // 右
            tabLiseSet=setInterval(()=>{
                w-=10;
                if(w<-(listW-tabW)-20) return;
                $('.z-main-tab-list').css('left',w);
            },10);
        };
    };
}).mouseup(function(){
    clearInterval(tabLiseSet);
});

$('.z-head-return').click(function(){
    const leng=pageRecord.length-1;
    const pg=pageRecord[leng];
    let m=$(`<a data-tabwin-open="${pg.url}" href="javascript:;">${pg.name}</a>`);
    $('body').append(m);
    m.click().remove();
    pageRecord.splice(leng,1);
});

$('.z-head-reload').click(function(){
    tabUpdate();
});

// 快捷键操作
$(document).keydown(function(event){
    // esc 关闭弹出层
    if(event.keyCode==27 && !event.ctrlKey){
        $('.eui-layer.on .eui-layer-close').click();
    };
    // // shift+w 关闭当前标签页和弹出层---与扫码枪冲突
    // if(event.keyCode==87 && event.shiftKey && !event.ctrlKey){
    //     $('.eui-tabwin-label.active i').click();
    //     if($('.eui-layer.on').length){
    //         $('.eui-layer.on .eui-layer-close').click();
    //     };
    // };
    // // shift+r 打开上一个关闭的标签页
    // if(event.keyCode==82 && event.shiftKey && pageRecord.length && !event.ctrlKey){
    //     const leng=pageRecord.length-1;
    //     const pg=pageRecord[leng];
    //     let m=$(`<a data-tabwin-open="${pg.url}" href="javascript:;">${pg.name}</a>`);
    //     $('body').append(m);
    //     m.click().remove();
    //     pageRecord.splice(leng,1);
    // };
    // // shift+e 刷新当前标签页
    // if(event.keyCode==69 && event.shiftKey && !event.ctrlKey){
    //     tabUpdate();
    // };
    // // shift+s 搜索框获得焦点
    // if(event.keyCode==83 && event.shiftKey && !event.ctrlKey){
    //     $('.eui-tabwin-main.active .z-search-ipt').focus();
    //     setTimeout(function(){$('.eui-tabwin-main.active .z-search-ipt').val('')},100);
    // };
    // // shift+下 下一页
    // if(event.keyCode==40 && event.shiftKey && !event.ctrlKey){
    //     $('.eui-tabwin-main.active .eui-page-next').click().remove();
    // };
    // // shift+上 上一页
    // if(event.keyCode==38 && event.shiftKey && !event.ctrlKey){
    //     $('.eui-tabwin-main.active .eui-page-prev').click().remove();
    // };
    // // shift+左 首页
    // if(event.keyCode==37 && event.shiftKey && !event.ctrlKey){
    //     $('.eui-tabwin-main.active .eui-page-first').click().remove();
    // };
    // // shift+右 末页
    // if(event.keyCode==39 && event.shiftKey && !event.ctrlKey){
    //     $('.eui-tabwin-main.active .eui-page-last').click().remove();
    // };
});

// layer关闭按钮
$(document).on('click','.eui-layer-shut',function(){
    $(this).parents('.eui-layer').find('.eui-layer-close').click();
});

// 复制功能
$(document).on('click','.copy-link',function(){
    const text=$(this).data('copy-url');
    const ipt=$(`<input type="text" value="${text}">`);
    $('body').append(ipt);
    // 选中
    ipt.get(0).select();
    // 复制
    if(document.execCommand("Copy", "false", null) || document.execCommand('copy')){
        eui.prompts('复制成功, 可直接Ctrl+v粘贴链接');
    }else{
        eui.prompts('复制失败, 您的浏览器不支持');
    };
    ipt.remove();
});


// table信息全显示
$('body').append('<div class="z-table-information"></div>');
$(document).on({
    mouseenter:function(e){
        $('.z-table-information').text($(this).text()).stop().fadeIn(100).css({'top':e.pageY-$('.z-table-information').innerHeight()-15,'left':e.pageX});
    },
    mouseleave:function(){
        $('.z-table-information').stop().fadeOut(100);
    },
    mousemove:function(e){
        
        $('.z-table-information').css({'top':e.pageY-$('.z-table-information').innerHeight()-15,'left':e.pageX});
    }
},'.z-table-w100,.z-table-w150,.z-table-w200,.z-table-w250,.z-table-w300,.z-table-w350');


// 富文本编辑器提交
localStorage.ueditor='cancel';
const submitEditor=url=>{
    if(!url) return;
    localStorage.ueditor='submit';
    localStorage.ueditor_url=url;
    const set=setInterval(()=>{
        if(localStorage.ueditor_state=='ok'){
            $('.eui-layer.on .eui-layer-close').click();
            eui.popover({
                style:'done',
                info:'提交成功！'
            });
            clearInterval(set);
            localStorage.removeItem('ueditor_state');
        };
    },500);
}

// 表格宽度自适应
const tableAuto=dt=>{
    let obj=null;
    if(dt){
        obj=$('[eui-tabwin-title='+dt.name+']').find('.z-table-w100,.z-table-w150,.z-table-w200,.z-table-w250,.z-table-w300,.z-table-w350');
    }else{
        obj=$('.z-table-w100,.z-table-w150,.z-table-w200,.z-table-w250,.z-table-w300,.z-table-w350');
    };
    obj.each(function(){
        const nw=$(this).parent('td').width();
        const ow=$(this).width();
        if(nw>ow){
            $(this).css('width',nw);
        }else{
            const cs=$(this).attr('class');
            $(this).css('width',cs.split('w')[1]);
        };
    });
};
$(window).resize(function(){
    tableAuto();
});

// 折叠表格-----------------------------------------------
$(document).on('click','.fold-table thead',function(){
    let pt=$(this).parents('.fold-table');
    console.log(pt)
    if(pt.hasClass('active')){
        pt.removeClass('active');
    }else{
        pt.addClass('active');
    };
});

$(document).on('click','.search-fold-open',function(){
    if($(this).hasClass('active')){
        $(this).removeClass('active');
    }else{
        $(this).addClass('active');
    };
});

$(document).on('click','.search-fold',function(ev){
    event.stopPropagation();
    return false;
});