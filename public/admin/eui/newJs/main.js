// 禁用按钮
$(document).on('click','.disabled',function(e){
    e.preventDefault();
    return false;
});
var father=$(window.top.document);
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

eui.tabwin({
    active:'active',
    menu:father.find('.z-main-tab-list'),
    main:father.find('.z-main-context'),
    max:99,
    error(obj){
        loadError('error',obj);
    },
    start(){
        const ft=$(window.top.document);
        const pt=ft.find('.eui-tabwin-main.active');
        // 检测是否打开控制器
        eui.loading({
            el:pt,
            state:true
        });
        eui.progress({
            el:pt,
            bg:'none'
        });
    },
    end(dt){
        const ft=$(window.top.document);
        const pt=ft.find('.eui-tabwin-main.active');
        eui.init('.eui-form');
        tableAuto(dt);
        eui.loading({
            el:pt,
            state:false
        });
        eui.progress({
            el:pt,
            max:100
        });
    }
});

// 搜索、分页功能--------------------------------------------
const zUpdate=url=>{
    const form=$('.z-tool-pagedata');
    let data='';
    let ipt=form.find('[eui=pull-right] input');
    let wh=url.indexOf('?');
    if(wh<0) url+='?';
    ipt.each(function(){
        let v=$(this).val();
        if($(this).attr('eui-val')) v=$(this).attr('eui-val');
        if(wh>=0){
            data+='&'+$(this).attr('name')+'='+v;
        }else{
            data+=$(this).attr('name')+'='+v+'&';
        };
    });
    tabUpdate(url+data);
};

//  刷新tab页------------------------
const tabUpdate=url=>{
    // 刷新
    father.find('.eui-tabwin-main.active').find('iframe').attr('src',url);
}

// 列表搜索模块，获取搜索参数
// $(document).on('click','.z-search-btn',function(){
//     const url=father.find('.eui-tabwin-main.active').attr('eui-tabwin-url');
//     zUpdate(url);
// });

const getPage=(obj,n)=>{
    const num=n || obj.data('pages-num');
    const old=$('.eui-page .active').data('pages-num');
    const form=$('.z-tool-pagedata');
    // 页码
    form.find('[name=current_page]').val(num);
    // 分割
    form.find('[name=per_page]').val($('.z-page-data .eui-select').val());
};

// 分页按钮
$(document).on('click','[data-pages-num]',function(){
    if($(this).hasClass('active')) return;
    getPage($(this));
    const url=father.find('.eui-tabwin-main.active').attr('eui-tabwin-url');
    // 执行分页
    zUpdate(url);
});

// 分割 选择
$(document).on('change','.z-page-data .eui-select',function(){
    getPage($(this),1);
    const url=father.find('.eui-tabwin-main.active').attr('eui-tabwin-url');
    // 执行分页
    zUpdate(url);
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
                    eui.prompts('删除失败，稍后再试！');
                }
            });
        }
    });
};

$(document).keydown(function(event){
    // esc 关闭弹出层
    if(event.keyCode==27 && !event.ctrlKey){
        $('.eui-layer.on .eui-layer-close').click();
    };
});

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
        if(!nm) return;

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
            el:'.eui-layer-main',
            state:false
        });
    };

    ajaxStatus=true;
    if(dt.file){
        // 文件提交
        const formdata=new FormData();
        formdata.append('file', $(dt.file)[0].files[0]);
        $.ajax({
            url: dt.url,
            type: "post",
            data: formdata,
            //关闭序列化
            processData: false,
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
const tableAuto=(dt)=>{
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