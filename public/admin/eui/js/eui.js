
"use strict";
// console.info('%cEUI开发文档 http://www.zjw7.com', 'color:#5be');

// Eui核心构造函数
const Eui=function(){
    // 获取eui.js部署的地址
    const js = document.scripts;
    for(let i=0;i<js.length;i++){
        let s=js[i].src;
        if(s.indexOf('eui.js')>=0||s.indexOf('eui-min.js')>=0){
            this.path=s.substring(0,s.lastIndexOf("/")+1);
        };
    };
    // init初始化
    this.init=c=>{
        if(c){
            // 局部初始化
            c.split(',').forEach(n=>{
                switch(n){
                    case '.eui-radio':
                    case '.eui-checkbox':
                    case '.eui-switch': eui.marquee($(n));
                        break;
                    case '.eui-select': eui.select($(n));
                        break;
                    case '.eui-file': eui.file($(n));
                        break;
                    case '.eui-upload': eui.uploadimg($(n));
                        break;
                    case '.eui-date': eui.optiondate($(n));
                        break;
                    case '.eui-tab': 
                        eui.tabcut({
                            wrap:'.eui-tab',
                            menu:'.eui-tab-menu',
                            main:'.eui-tab-main'
                        });
                        break;
                    case '.eui-nav': eui.nav($(n));
                        break;
                    case '.eui-form':
                        eui.marquee($('.eui-radio'));
                        eui.marquee($('.eui-checkbox'));
                        eui.marquee($('.eui-switch'));
                        eui.select($('.eui-select'));
                        eui.optiondate($('.eui-date'));
                        eui.uploadimg($('.eui-upload'));
                        eui.file($('.eui-file'));
                        break;
                    case '.eui-imgview':
                        $('.eui-imgview-img').off('click').click(function(){
                            eui.loading({
                                el:$('body'),
                                state:true,
                                bg:'rgba(40,48,56,0.7)',
                                color:'#fff'
                            });
                            const list=$(this).parents('.eui-imgview').find('img');
                            const index=$(this).parent().index();
                            eui.imgpopover({
                                list:list,
                                index:index
                            });
                            setTimeout(()=>{
                                eui.loading({
                                    el:$('body'),
                                    state:false
                                });
                            },300);
                        });
                        break;
                    case '.eui-tree':
                        $('.eui-tree .eui-icon-catalog').off().click(function(){
                            const s=$(this).parent('.eui-tree-fold').attr('eui');
                            if(s=='off'){
                                $(this).parent('.eui-tree-fold').attr('eui','on');
                            }else{
                                $(this).parent('.eui-tree-fold').attr('eui','off');
                            };
                            return false;
                        });
                        $('.eui-tree .eui-icon-item, .eui-tree .eui-tree-text').off().click(function(){
                            const s=$(this).siblings('.eui-tree-check').prop('checked');
                            if(s==undefined) return;
                            $(this).siblings('.eui-tree-check').prop('checked',!s);
                            if($(this).siblings('.eui-tree-context').length){
                                $(this).siblings('.eui-tree-context').find('.eui-tree-check').prop('checked',!s);
                            };
                            if(!$(this).parents('.eui-tree').attr('eui')=='no-subset'){
                                const sbs=$(this).parent('.eui-tree-fold').siblings('.eui-tree-fold').children('.eui-tree-check');
                                const leng=sbs.length+1;
                                let t=0;
                                if(!s) t++;
                                sbs.each(function(){
                                    if($(this).prop('checked')) t++;
                                });
                                if(t==leng){
                                    $(this).parents('.eui-tree-context').siblings('.eui-tree-check').prop('checked',true);
                                }else{
                                    $(this).parents('.eui-tree-context').siblings('.eui-tree-check').prop('checked',false)
                                };
                            };
                            return false;
                        });
                        break;
                    default:
                        return console.error('%c初始化失败，没找到'+c+'，请检测是否填写错误！', 'color:#f33');
                };
            });
        }else{
            eui.marquee($('.eui-radio'));
            eui.marquee($('.eui-checkbox'));
            eui.marquee($('.eui-switch'));
            eui.file($('.eui-file'));
            eui.select($('.eui-select'));
            eui.uploadimg($('.eui-upload'));
            eui.optiondate($('.eui-date'));

            eui.tabcut({
                wrap:'.eui-tab',
                menu:'.eui-tab-menu',
                main:'.eui-tab-main'
            });
            eui.nav($('.eui-nav'));
            $('.eui-imgview-img').off('click').click(function(){
                eui.loading({
                    el:$('body'),
                    state:true,
                    bg:'rgba(40,48,56,0.7)',
                    color:'#fff'
                });
                const list=$(this).parents('.eui-imgview').find('img');
                const index=$(this).parent().index();
                eui.imgpopover({
                    list:list,
                    index:index
                });
                setTimeout(()=>{
                    eui.loading({
                        el:$('body'),
                        state:false
                    });
                },300);
            });
        };
        // 图破替换
        $('img').one('error',function(){
            let ph=eui.path;
            $(this).attr("src", eui.path+"../images/transparency.png").addClass('eui-img-error');
        });
    };
    return;
};

// 模态窗
Eui.prototype.popover=dt=>{
    const z={
        tp:dt.type || 'alert',
        tt:dt.title || '通知',
        sy:dt.style || 'primary',
        okt:dt.okaytext || '确定',
        clt:dt.canceltext || '取消',
        ok:null,
        cl:null,
        okp:dt.okayparam,
        clp:dt.cancelparam,
        mg:dt.info,
        dom:'',
        that:null
    };
    // 拼接dom结构
    z.dom+=`<div class="eui-popover eui-popover-main">
            <div class="eui-popover-main-title" eui="${z.sy}">${z.tt}<i class="eui-popover-main-close eui-icon-close"></i></div>
            <div class="eui-popover-main-info">${z.mg}`;
            if(z.tp=='prompt') z.dom+=`<input class="eui-ipt eui-popover-ipt" placeholder="请输入...">`;
            z.dom+=`</div><div class="eui-popover-btn" eui="text-right"><button eui="${z.sy}" class="eui-btn eui-popover-okay">${z.okt}</button>`;
            if(z.tp=='confirm' || z.tp==prompt){
                z.dom+=`<button class="eui-btn eui-popover-cancel">${z.clt}</button></div>`;
            }else{
                z.dom+=`</div>`;
            };
    z.dom+=`</div>`;
    // 插入dom到页面
    z.that=$(z.dom);
    $('body').append(z.that);
    // 按钮绑定-确定、取消事件
    z.ok=z.that.find('.eui-popover-okay');
    z.cl=z.that.find('.eui-popover-cancel');
    // 回调
    if(dt.okaycall) z.ok.on('click',()=>dt.okaycall(z.okp,z.that.find('.eui-popover-ipt').val()));
    if(dt.cancelcall) z.cl.on('click',()=>dt.cancelcall(z.clp,z.that.find('.eui-popover-ipt').val()));
    // 进入动画
    setTimeout(()=>{
        z.that.addClass('on');
    },100);
    // 按钮绑定-移除对话框
    $('.eui-popover-main button,.eui-popover-main-close').on('click',()=>{
        z.that.removeClass('on');
        setTimeout(()=>{
            z.that.remove();
        },200);
    });
    return false;
};

// 自动消失的提示窗
Eui.prototype.prompts=info=>{
    // 先删除已存在窗口
    if($('.eui-popover-prompt').length) $('.eui-popover-prompt').remove();
    const dom=$(`<div class="eui-popover-prompt">${info}</div>`);
    $('body').append(dom);
    // 动画效果及延时关闭
    setTimeout(()=>dom.addClass('on'),100);
    setTimeout(()=>{
        dom.removeClass('on');
        setTimeout(()=>dom.remove(),200);
    },2500);
    return false;
};

// message消息提示
Eui.prototype.message=dt=>{
    // 生成dom
    const z={
        tp:dt.tag || '消息',
        sy:dt.style || 'primary',
        tt:dt.title || '通知消息',
        url:dt.url || '#',
        tg:dt.target || 'self',
        mg:dt.info,
        st:dt.hide,
        dom:'',
        that:null
    };
    z.dom=`<div class="eui-tags-message">
                <a href="${z.url}" target="_${z.tg}">
                    <span class="eui-tags-message-type" eui="${z.sy}"><em>${z.tp}</em></span>
                    <span class="eui-tags-message-title">${z.tt}</span>
                    <span class="eui-tags-message-info">${z.mg}</span>
                </a>`;
                if(z.st===false) z.dom+=`<a class="eui-tags-message-close">&times;</a>`;
    z.dom+=`</div>`;
    z.that=$(z.dom);
    $('body').append(z.that);
    
    // 排序和事件处理
    const b={
        leng:$('.eui-tags-message').length,
        // message 排序
        sq(){$('.eui-tags-message').each((i,n)=>{$(n).css('top',(i)*100+10);})},
        time:dt.time || 5000
    };
    // 动态高度
    z.that.css('top',(b.leng-1)*100+10);
    // 动画效果及延时关闭
    setTimeout(()=>z.that.addClass('on'),100);
    if(z.st!==false){
        // 自动关闭
        let st=false; //用于判断是否hover
        let tm=false; //用于判断是否超出删除时常
        z.that.hover(()=>{
            st=true; //进入hover
        },()=>{
            st=false; //退出hover
            if(tm){ //如果超时则删除
                setTimeout(()=>{
                    z.that.removeClass('on');
                },100);
                setTimeout(()=>{
                    z.that.remove();
                    // 重新排序
                    b.sq();
                },300);
            };
        });
        // 延时关闭
        setTimeout(()=>{
            tm=true; //超出删除时间了
            if(!st){ //如果不为hover则删除
                z.that.removeClass('on');
                setTimeout(()=>{
                    z.that.remove();
                    // 重新排序
                    b.sq();
                },200);
            }
        },b.time);
    }else{
        z.that.find('.eui-tags-message-close').click(()=>{
            // 手动关闭
            setTimeout(()=>{
                z.that.removeClass('on');
                setTimeout(()=>{
                    z.that.remove();
                    // 重新排序
                    b.sq();
                },200);
            },100);
        })
    }
    return false;
};

// radio、checkbox、switch
Eui.prototype.marquee=el=>{
    // 替换原生dom
    el.each((i,n)=>{
        const $n=$(n);
        //防止重复init
        if($n.parents('.eui-radio-wrap').length||$n.parents('.eui-checkbox-wrap').length||$n.parents('.eui-switch-wrap').length) return;
        const z={
            dom:'',
            tt:$n.attr('title') || '无标题',
            // 获取自定义属性
            o_atr:n.attributes,
            // 记录新自定义属性
            n_atr:''
        };
        // 遍历自定义属性
        for(let j=0;j<z.o_atr.length;j++){
            if(z.o_atr[j].name!="type") z.n_atr+=' '+z.o_atr[j].name+'="'+z.o_atr[j].value+'"';
        };
        if($n.hasClass('eui-radio')){
            // eui-radio
            z.dom=
            `<label class="eui-radio-wrap">
                <input ${z.n_atr} type="radio">
                <i class="eui-icon-radio"></i>
                <span>${z.tt}</span>
            </label>`;
        }else if($n.hasClass('eui-checkbox')){
            // eui-checkbox
            z.dom=
            `<label class="eui-checkbox-wrap">
                <input ${z.n_atr} type="checkbox">
                <i class="eui-icon-checkbox"></i>
                <span>${z.tt}</span>
            </label>`;
        }else{
            // eui-switch
            z.dom=
            `<label class="eui-switch-wrap">
                <input ${z.n_atr} type="checkbox">
                <div class="eui-switch-view">
                    <i class="eui-i-switch"></i>
                    <span class="eui-switch-info"></span>
                </div>
            </label>`;
        };
        // 替换原生dom结构
        $n.replaceWith(z.dom);
    });
    // 开关按钮事件
    if(el.hasClass('eui-switch')){
        $('.eui-switch').each(function(){
            // 截取开启关闭名称
            const z=v=>{
                let n;
                if(v.indexOf(',')>=0){
                    n=v.split(',');
                    n.forEach(c=>{
                        if(c.indexOf('|')>=0) n=c;
                    });
                }else{
                    n=v;
                };
                return n;
            };
            
            const txt=z($(this).attr('eui')).split('|');;
            const info=$(this).parent().find('.eui-switch-info');
            const that=$(this);
            // 写入开关名称
            const cge=()=>{
                if(that.is(':checked')){
                    info.text(txt[0]);
                }else{
                    info.text(txt[1]);
                };
            };
            // 初始化开关名称
            cge();
            // 绑定change
            that.off().change(cge);
        });
    };
    return false;
};

// select处理
Eui.prototype.select=el=>{
    const elm={
        wrap:'eui-select-wrap',
        open:'eui-select-open',
        slt:'eui-select',
        list:'eui-option-list',
        act:'eui-option',
        add:'eui-icon-add',
        rev:'eui-icon-editor',
        del:'eui-icon-delete',
        done:'eui-icon-complete',
        ipt:'eui-option-ipt'
    };
    el.each((i,n)=>{
        const $n=$(n);
        if($n.parents('.'+elm.wrap).length) return; //防止重复init
        const z={
            //获取自定义属性
            o_atr:n.attributes,
            //记录新自定义属性
            n_atr:'',
            // eui属性值
            z:null,
            dis:$n.attr('disabled') || '',
            dom:'',
            that:null,
            item:$n.children(),
            dd:'',
            ed:null,
            vl:null
        };
        // 遍历自定义属性
        for(let j=0;j<z.o_atr.length;j++){
            if(z.o_atr[j].name!='disabled' && z.o_atr[j].name!='value') z.n_atr+=' '+z.o_atr[j].name+'="'+z.o_atr[j].value+'"';
            if(z.o_atr[j].name=='eui') z.z=z.o_atr[j].value;
        };
        // dd列表生成
        z.item.each((i,op)=>{
            // 获取op自定义属性
            const b={
                o_atr:op.attributes,
                n_atr:'',
                op:$(op),
                vl:null,
                txt:null,
                cl:null,
                src:null,
                btn:''
            };
            // 遍历op自定义属性
            for(let j=0;j<b.o_atr.length;j++){
                if(b.o_atr[j].name!='class' && b.o_atr[j].name!='value') b.n_atr+=' '+b.o_atr[j].name+'="'+b.o_atr[j].value+'"';
            };

            b.vl=$.trim(b.op.val());
            b.txt=$.trim(b.op.text());
            b.cl=b.op.attr('class') || '';

            // 获取默认选中的op值
            if(b.op.is(':selected')){
                b.cl+=' eui-option';
                z.ed=b.txt;
                z.vl=b.vl;
            };
            // op图片
            if(b.n_atr.indexOf('eui-img')>=0){
                b.src=b.n_atr.split('"') || b.n_atr.split("'");
                b.txt=`<span class="eui-select-img"><img src="${b.src[1]}"></span><span class="eui-select-txt">${b.txt}</span>`;
            };

            // 删改按钮
            if(z.z && z.z.indexOf('del')!=-1 && b.n_atr.indexOf('disabled')==-1) b.btn+='<a class="'+elm.del+'"></a>';
            if(z.z && z.z.indexOf('rev')!=-1 && b.n_atr.indexOf('disabled')==-1) b.btn+='<a class="'+elm.rev+'"></a>';
            z.dd+=`<dd class="${b.cl}" ${b.n_atr} eui-val="${b.vl}">${b.txt+b.btn}</dd>`;
        });
        // 增加按钮
        if(z.z && z.z.indexOf('add')!=-1) z.dd+=`<dd class="eui-option-add" eui="text-center"><a class="${elm.add}"></dd>`;

        // 拼接整体结构
        z.dom=
        `<div class="${elm.wrap}">
            <input ${z.n_atr} type="text" value="${z.ed}" eui-val="${z.vl}" eui-txt="${z.ed}" readonly ${z.dis}>
            <i class="eui-select-arrow"></i>
            <dl class="${elm.list}">
                ${z.dd}
            </dl>
        </div>`;
        // 替换select
        z.that=$(z.dom);
        $n.replaceWith(z.that);
    });
    // 点击外部关闭
    $(document).on('click',(e)=>{
        let el=$(e.target);
        // 关闭select
        if(el.parents('.'+elm.wrap).length==0){
            $('.'+elm.wrap).removeClass(elm.open);
        };
    });
    // 开关事件
    $('.'+elm.wrap).off().on('click',function(){
        const slt=$(this).find('.'+elm.slt);
        if(slt.attr('disabled')) return;
        $('.'+elm.wrap).not($(this)).removeClass(elm.open);
        $(this).toggleClass(elm.open);
        // 自动调整位置
        const z=slt.attr('eui');
        if(!z || z.indexOf('down')<0 && z.indexOf('up')<0){
            // 计算出现位置
            eui.place({
                rel:slt,
                abs:$(this).find('.'+elm.list),
                interval:5,
                type:'select'
            });
        };
    });

    // 绑定增删改事件
    const ev=()=>{
        $('.'+elm.list+' .'+elm.add+',.'+elm.list+' .'+elm.rev+',.'+elm.list+' .'+elm.del+',.'+elm.list+' .'+elm.done).off().click(function(){
            const tp=$(this).attr('class');
            const dd=$(this).parent('dd');
            const txt=dd.text();
            if(tp==elm.add){
                dd.before(`<dd><input class="${elm.ipt} add" type="text"><a class="${elm.done}"></a></dd>`);
                dd.prev().children('.'+elm.ipt).focus();
            }else if(tp==elm.del){
                dd.remove();
            }else if(tp==elm.done){
                dd.find(elm.ipt).blur();
            }else{
                dd.html(`<input class="${elm.ipt}" type="text"><a class="${elm.done}"></a>`);
                dd.children('.'+elm.ipt).focus().val(txt);
            };
            $('.'+elm.list+' .'+elm.ipt).off().blur(function(){
                let v=$(this).val();
                // 新增却未输入，删除新增
                if(txt=='' && !$(this).hasClass('add')) return $(this).parent().remove();
                // 修改却未输入，还原值
                if(v=='') v=txt;
                const dd=$(this).parent('dd');
                const st=$(this).parents('.'+elm.wrap).find('.'+elm.slt);
                // 如果修改选中值，更新eui-select显示
                if(dd.hasClass(elm.act)) st.attr('eui-txt',v).val(v);
                // 更新dd
                const z=st.attr('eui');
                if(z && z.indexOf('del')!=-1) v+=`<a class="${elm.del}"></a>`;
                v+=`<a class="${elm.rev}"></a>`;
                dd.html(v);
                return ev();
            }).click(function(){
                return false;
            });
            return false;
        });
        // 选择事件
        $('.'+elm.list+' dd').off().on('click',function(){
            if($(this).attr('disabled')) return false;
            $(this).addClass(elm.act).siblings().removeClass(elm.act);
            $(this).parents('.'+elm.wrap).find('.'+elm.slt).val($(this).text()).attr({'eui-val':$(this).attr('eui-val'),'eui-txt':$(this).text()}).trigger('change');
        });
    };
    return ev();
};

// 美化file框
Eui.prototype.file=el=>{
    el.each((i,n)=>{
        const $n=$(n);
        const z=$n.attr('eui');
        if($n.prev().hasClass('eui-file-wrap')) return;
        let h=
        `
            <div class="eui-file-wrap" eui="${z}">
                <span class="eui-file-show">未选择文件</span>
                <a class="eui-file-preview eui-btn" eui="${z} primary" href="javascript:;">选择文件</a>
            </div>
        `;
        const $h=$(h);
        $n.before($h);
        $h.find('.eui-file-preview').click(function(){
            $h.next().click();
        });
        $n.change(function(e){
            let txt=e.target.value.split( '\\' ).pop();
            if(!txt) txt='未选择文件';
            $(this).prev().find('.eui-file-show').text(txt);
        });
    });
};

// tab切换、手风琴
Eui.prototype.tabcut=dt=>{
    // 初始化
    const el=$(dt.wrap);
    el.each(function(){
        // 初始
        let menu,main;
        if(dt.fold===true){
            menu=$(this).find(dt.menu);
            main=$(this).find(dt.main);
        }else{
            menu=$(this).find(dt.menu).children();
            main=$(this).find(dt.main).children();
        };
        // 防止重复初始化
        if(menu.hasClass('active')) return;
        // 初始化
        menu.eq(0).addClass('active');
        main.eq(0).addClass('active');
        // 事件类型，默认click，可设置值 hover
        let tar='click';
        if(dt.target==='hover') tar='mouseover';

        // 绑定事件
        menu.off().on(tar,function(){
            let index;
            if(dt.fold===true){
                index=$(this).parent('.eui-fold-item').index();
            }else{
                index=$(this).index();
            };
            menu.removeClass('active');
            $(this).addClass('active');
            main.removeClass('active');
            main.eq(index).addClass('active');
        });
    });
};

// uploadimg图片上传
Eui.prototype.uploadimg=el=>{
    // 图片转base64、压缩
    const uploadfile=(el,dt)=>{
        // dt.m是max上传图片张数上限, dt.s是size, dt,c是compress压缩系数
        const $n=$(el);
        // 获取父标签
        const $p=$n.parents('.eui-upload');

        // 校验一下文件格式
        const v=$n.val();
        if(!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG|JPEG)$/.test(v)){
            $n.val('');
            eui.prompts('图片类型必须是jpg,png,gif,jpeg中的一种!');
            return false;
        };
    
        // 校验一下文件大小
        const sz=dt.s*1024*1024;
        // 这里要用原生对象 el，不然files[0]报错！！！
        const f=el.files[0];
        if(f.size>sz){
            eui.prompts('图片大小必须小于'+dt.s+'M');
            return false;
        };
    
        // 获取图片blob数据
        const g=f=>{
            let u;
            if(window.createObjectURL!=undefined){ // basic
                u=window.createObjectURL(f);
            }else if(window.URL!=undefined){ // mozilla(firefox)
                u=window.URL.createObjectURL(f);
            }else if(window.webkitURL!=undefined){ // webkit or chrome
                u=window.webkitURL.createObjectURL(f);
            }
            return u;
        };
    
        const blob=g(f);
        let dom;
        // canvas绘制、转base64、图片压缩
        const base64Img=(b,ig)=>{
            // 生成一个img
            const img=new Image();
            img.src=b;
            img.onload=function(){
                //宽高比例
                const w=img.width;
                const h=img.height;
                const scale=w/h;
                // 默认图片质量为100%
                const quality=dt.c/10;
                //生成canvas
                const canvas=document.createElement('canvas');
                const ctx=canvas.getContext('2d');
                // 创建属性节点
                const anw=document.createAttribute("width");
                anw.nodeValue=w;
                const anh=document.createAttribute("height");
                anh.nodeValue=h;
                canvas.setAttributeNode(anw);
                canvas.setAttributeNode(anh);
                ctx.drawImage(this, 0, 0, w, h);
                // quality值越小，所绘制出的图像越模糊
                const bs64=canvas.toDataURL('image/jpeg',quality);
                let ldimg={}; //传给回调函数到父元素
                if(tp($n.parents('.eui-upload-item-main'))=='a'){
                    ldimg.item=$p.find('.eui-upload-img').last();
                    ldimg.img=ldimg.item.find('img');
                    ldimg.type='add';
                }else{
                    ldimg.item=$n.parents('.eui-upload-img');
                    ldimg.img=ig;
                    ldimg.type='update';
                };
                // 显示图片
                ldimg.img.attr('src',bs64).removeClass('eui-img-error');
                // 加载完图片，触发回调
                if(eui.uploadcall) eui.uploadcall(ldimg);
                // 重新绑定一下这组upload
                eui.uploadimg($p);
                // 退出loading
                setTimeout(()=>{
                    eui.loading({
                        el:$p,
                        state:false
                    });
                },100);
                return;
            };
        };
        // 判断是添加图片还是修改
        const tp=p=>{
            if(p.hasClass('eui-upload-img')){
                // 标记-修改
                return 'm';
            }else{
                // 标记-添加
                return 'a';
            };
        };
        if(tp($n.parents('.eui-upload-item-main'))=='a'){
            // 添加
            dom=`<div class="eui-upload-item">
                    <i class="eui-icon-close eui-upload-delete" eui="danger"></i>
                    <div class="eui-upload-item-main eui-upload-img">
                        <label>
                            <i class="eui-icon-search eui-img-see"></i>
                            <span>修改图片</span>
                            <input class="eui-upload-file" type="file">
                        </label>
                        <img src="${eui.path}../images/transparency.png">
                    </div>
                </div>`;
            const that=$(dom);
            // 判断数量max了没，到max了删除添加
            if($p.find('.eui-upload-img').length==dt.m-1){
                $n.parents('.eui-upload-add').parent().remove();
                $p.append(that);
            }else{
                $n.val('');
                $n.parents('.eui-upload-item').before(that);
            };
            // 加入loading
            eui.loading({
                el:that.find('.eui-upload-item-main'),
                state:true
            });
            // base64处理
            base64Img(blob);
        }else{
            // 加入loading
            eui.loading({
                el:$n.parents('.eui-upload-item-main'),
                state:true
            });
            // 修改
            const ig=$n.parent().siblings('img');
            ig.attr('src',eui.path+"../images/transparency.png");
            // base64处理
            base64Img(blob,ig);
        };
    };
    el.each((i,n)=>{
        const $n=$(n);
        //获取eui配置参数
        let z=[];
        if($n.attr('eui')) z=$n.attr('eui').split(',');
        const dt=z=>{
            let dt={};
            z.forEach(n=>{
                if(n.indexOf('max:')>=0){
                    dt.m=n.substring(4,n.length);
                }else if(n.indexOf('size:')>=0){
                    dt.s=n.substring(5,n.length);
                }else if(n.indexOf('compress:')>=0){
                    dt.c=n.substring(9,n.length);
                };
            });
            if(dt.m==undefined) dt.m=10; // 默认max:10
            if(dt.s==undefined) dt.s=2;  // 默认size:10
            if(dt.c==undefined) dt.c=10; // 默认compress:10
            // 返回eui-upload的eui参数
            return dt;
        };
        // eui配置参数
        const v=dt(z);

        const add=
        `<div class="eui-upload-item">
            <div class="eui-upload-item-main eui-upload-add">
                <label>
                    <i class="eui-icon-update"></i>
                    <span>上传图片</span><input class="eui-upload-file" type="file">
                </label>
            </div>
        </div>`;
        // 子元素初始化
        const c=$n.find('.eui-upload-item');
        c.each((ii,nn)=>{
            const $nn=$(nn);
            if(!$nn.find('.eui-upload-item-main').length){
                let ch='';
                const leng=$nn.find('img').length;
                if(leng){
                    for(let i=0;i<leng;i++){
                        ch+=
                        `<i class="eui-icon-close eui-upload-delete" eui="danger"></i><div class="eui-upload-item-main eui-upload-img">
                            <label>
                                <i class="eui-icon-search eui-img-see"></i>
                                <span>修改图片</span>
                                <input class="eui-upload-file" type="file">
                            </label>
                            <img src="${$nn.find('img').eq(i).attr('src')}">
                        </div>`
                    };
                }else{
                    ch=
                    `<div class="eui-upload-item-main eui-upload-add"><label>
                        <i class="eui-icon-update"></i>
                        <span>上传图片</span><input class="eui-upload-file" type="file">
                        </label>
                    </div>`;
                };
                // 渲染 
                $nn.html(ch);
            };
        });
        // 没有上传格并且没到max，自动添加 上传图片格
        if($n.find('.eui-upload-add').length==0 && v.m>c.length) $n.append(add);

        // 查看图片
        $n.find('.eui-img-see').off().click(function(){
            const list=el.find('img');
            const index=list.index($(this).parents('.eui-upload-item').find('img'));
            eui.loading({
                el:$('body'),
                state:true,
                bg:'rgba(40,48,56,0.7)',
                color:'#fff'
            });
            setTimeout(()=>{
                // 开启图片查看
                eui.imgpopover({list,index});
                setTimeout(()=>{
                    eui.loading({
                        el:$('body'),
                        state:false
                    });
                },300);
            },100);
            return false; // 阻止冒泡
        });
        // 删除图片
        $n.find('.eui-upload-delete').one('click',function(){
            $(this).parent().remove();
            if($n.find('.eui-upload-add').length<1){
                $n.append(add);
                eui.uploadimg($n);
                return false;
            };
        });
        // 绑定change
        const file=$n.find('.eui-upload-file');
        file.off().change(ev=>{
            // file发生改变执行uploadfile方法,传递当前file和父标签配置参数;
            uploadfile(ev.target,v);
        });
    });
};

// 图片列表弹出层、调用轮播
Eui.prototype.imgpopover=dt=>{
    if(dt.index==undefined) dt.index=0;
    const z={
        li:'',
        list:dt.list,
        index:dt.index,
        dom:'',
        that:null
    };
    // 循环出图片li
    z.list.each((i,n)=>{
        let c='';
        if(z.index==i) c='active';
        z.li+=`<li class="${c}"><img src="${$(n).attr('src')}"></li>`;
    });
    z.dom=`
    <div class="eui-shade"></div><div class="eui-imgpopover">
        <a class="eui-imgpopover-close eui-icon-close eui-btn" eui="danger"></a>
        <a class="eui-imgpopover-prev prev eui-icon-left"></a>
        <a class="eui-imgpopover-next next eui-icon-left"></a>
        <div class="eui-imgpopover-num">${z.index+1} / ${z.list.length}</div>
        <ul class="eui-imgpopover-main">
            ${z.li}
        </ul>
    </div>`;
    z.that=$(z.dom);
    // 插入页面
    $('body').append(z.that);
    // 绑定轮播
    eui.imgfocus({
        main:'.eui-imgpopover',
        item:'.eui-imgpopover-main li',
        index:z.index
    });
    // 关闭
    z.that.find('.eui-imgpopover-close').click(()=>{
        z.that.eq(0).removeClass('on');
        z.that.eq(1).removeClass('on');
        setTimeout(()=>{
            z.that.remove();
        },200);
    });
    // 入场动画
    setTimeout(()=>{
        z.that.eq(0).addClass('on');
        z.that.eq(1).addClass('on');
    },300);
};

// 图片查看、轮播
Eui.prototype.imgfocus=dt=>{
    const z={
        wrap:$('body').find(dt.main),
        item:null,
        min:0,
        max:null,
        index:dt.index || 0,
        play:null,
        hd:null,
        // 延迟时间
        time:dt.palytime || 3000,
        // 自动播放
        auto(){
            if(dt.autoplay){
                z.play=setInterval(()=>{
                    z.next();
                },z.time);
            };
        },
        // 下一页
        next(){
            if(z.index==z.max){
                // 是否循环
                if(dt.loop!==false){
                    z.index=z.min-1;
                }else{
                    return;
                };
            };
            z.index++;
            return z.targ(z.index);
        },
        // 上一页
        prev(){
            if(z.index==z.min){
                // 是否循环
                if(dt.loop!==false){
                    z.index=z.max+1;
                }else{
                    return;
                };
            };
            z.index--;
            return z.targ(z.index);
        },
        // 切换
        targ:i=>{
            clearInterval(z.play);
            z.auto();
            if(dt.hd===true){
                z.hd.removeClass('active');
                z.hd.eq(i).addClass('active');
            };
            z.item.removeClass('active');
            // 图片查看的数字标示
            if($('.eui-imgpopover-num').length) $('.eui-imgpopover-num').text((i+1)+'/'+z.item.length);
            return z.item.eq(i).addClass('active');
        }
    };
    z.item=z.wrap.find(dt.item);
    z.max=z.item.length-1;

    if(z.wrap.length>1) return console.error('imgfocus方法中绑定的main类不是唯一！');
    // 只有一张图就隐藏切换按钮
    if(!z.max) z.wrap.find('.prev,.next').hide();
    // 生成下标
    if(dt.hd===true){
        let li='';
        for(let i=0;i<=z.max;i++){
            let on='';
            if(i==z.index) on='active';
            li+=`<li class="${on}"></li>`;
        };
        z.wrap.find('.hd').html(li);
    };
    z.hd=z.wrap.find('.hd').children();
    // 先点亮一张
    z.item.eq(z.index).addClass('active');
    // 下标切换
    z.hd.click(function(){
        z.index=$(this).index();
        return z.targ(z.index);
    });
    // 切换事件
    z.wrap.find('.prev,.next').click(function(){
        if($(this).hasClass('prev')){
            // 上一张
            return z.prev();
        }else if($(this).hasClass('next')){
            // 下一张
            return z.next();
        };
    });
    // 关闭停止自动轮播
    z.wrap.find('.eui-imgpopover-close').click(()=>{
        return clearInterval(z.play);
    });
    // 开启自动播放
    z.auto();
};

// 日历控件
Eui.prototype.optiondate=el=>{
    const calendar=c=>{
        let $n = c;
        // eui-date绑定
        $n.on("click",function(){
            $('.eui-calendar').remove();
            let ts,up,lw,z;
            z=$n.attr('eui');
            if(!z) z=''; // 防止没设置eui属性时报错
            z.split(',').forEach(n=>{
                if(n.indexOf(':')){
                    let c=n.split(':');
                    switch(c[0]){
                        case 'times': ts=c[1];
                            break;
                        case 'upper': up=c[1];
                            break;
                        case 'lower': lw=c[1];
                    };
                };
            });
            // 参数初始化
            c.upper=up||NaN;
            c.lower=lw||NaN;
            c.times=ts||'false';
            // 上限参数
            if(c.upper){
                if(c.upper=='today'){
                    // 上限今天
                    c.upper=new Date();
                }else if(c.upper.indexOf('.')>=0||c.upper.indexOf('#')>=0){
                    let b=$(c.upper).val();
                    if(b){
                        c.upper=new Date(b);
                    };
                }else{
                    c.upper=new Date(c.upper);
                };
            };
            // 下限参数
            if(c.lower){
                c.lower.toString();
                if(c.lower=='today'){
                    // 如果下限是今天，今天也要可选
                    const d=new Date();
                    // 计算昨天
                    const y=d=>{
                        return d.setTime(d.getTime()-24*60*60*1000);
                    };
                    c.lower=new Date(y(d));
                }else if(c.lower.indexOf('.')>=0||c.lower.indexOf('#')>=0){
                    let b=$(c.lower).val();
                    if(b){
                        c.lower=new Date(b);
                    };
                }else{
                    c.lower=new Date(c.lower);
                };
            };
            cd($n);
            // 点击外部隐藏
            $(document).mouseup(e=>{
                if($(e.target).attr("class") != $n.attr("class") && $(e.target).parents('.eui-calendar').length==0&&$(e.target).parents('.eui-caltab-year,.eui-caltab-month').length==0){
                    that.remove();
                };
            });
            // 关闭
            $(document).on('click','.eui-calendar .close',()=>{
                that.remove();
            });
            // 现在时间
            $(document).on('click','.eui-calendar .now',()=>{
                $('.eui-calendar').siblings('.eui-date').val(vto());
                that.remove();
            });
        });
        // 输出现在时间
        let vto=()=>{
            let tod=new Date();
            let m=tod.getMonth()+1;
            if(m<10) m='0'+m;
            let d=tod.getDate();
            if(d<10) d='0'+d;
            let h,n,s,v;
            v=tod.getFullYear()+'-'+m+'-'+d;
            ttt=v;
            h=tod.getHours();
            if(h<10) h='0'+h;
            n=tod.getMinutes();
            if(n<10) n='0'+n;
            s=tod.getSeconds();
            if(s<10) s='0'+s;
            v+=' '+h+':'+n+':'+s;
            return v;
        };
        // 初始化
        let today,vday,e,f,q,that;
        const cd=n=>{
            let nv=$n.val();
            today = new Date();
            if(nv!=''){
                if(c.times=='true'){
                    const av=nv.split(' ');
                    nv=av[0];
                    hhh=' '+av[1];
                };
                vday = new Date(nv);
            }else{
                vday = today;
            };
            e = vday.getFullYear();
            f = vday.getMonth();
            q = vday.getDate();
            let k = `
            <div class='eui-calendar'>
                <div class='eui-calendar-main'>
                    <div class='eui-calendar-main-title'>
                        <a class='eui-prev-month eui-icon-return'></a>
                        <span class='eui-calendar-main-title-tit'>
                            <span class='eui-year-txt'>
                                <a class='currentYear'>${e}</a>\u5e74
                            </span>
                            <span class='eui-month-txt'>
                                <a class='currentMonth'>${f + 1}</a>\u6708
                            </span>
                        </span>
                        <a class='eui-next-month eui-icon-enter'></a>
                    </div>
                    <div class='eui-calendar-main-con'>
                        <div class='eui-calendar-reserve'>
                    </div>
                    <div class='eui-calendar-enabled'>
                        ${setD(e, f)}
                    </div>
                </div>
            </div>`;
            that=$(k);
            $n.parent('.eui-date-wrap').append(that);
    
            // 加载时分秒选择功能
            hns(c.times);

            // 计算出现位置
            eui.place({
                rel:n,
                abs:that,
                interval:5
            });
    
            // 绑定选择日期
            bindDay();
            // 绑定操作
            mp();
        };
        // 最终输出的时间
        let ttt='';
        let hhh=' 00:00:00';
        // 时分秒
        const hns=d=>{
            if(d!='true') return;
            let sp=$n.val().split(' ');
            if(sp.length>1){
                sp=sp[1].split(':');
            }else{
                sp=['00','00','00'];
            };
    
            let t=
            `<div class="eui-calendar-times" eui="text-left">
                <div class="eui-calendar-times-item eui-calendar-times-show">
                    <span class="eui-calendar-times-title">时间</span>
                    <span class="eui-calendar-times-con" eui="text-right"><i id="eui-calendar-times-show-hh">${sp[0]}</i>:<i id="eui-calendar-times-show-nn">${sp[1]}</i>:<i id="eui-calendar-times-show-ss">${sp[2]}</i></span>         
                </div>
                <div class="eui-calendar-times-item">
                    <span class="eui-calendar-times-title">小时</span>
                    <span class="eui-calendar-times-con"><b class="eui-calendar-times-minus">&minus;</b><b class="eui-calendar-times-plus">&plus;</b><input id="eui-range-hh" type="range" min="00" max="24" step="1" value="${sp[0]}"></span>
                </div>
                <div class="eui-calendar-times-item">
                    <span class="eui-calendar-times-title">分钟</span>
                    <span class="eui-calendar-times-con"><b class="eui-calendar-times-minus">&minus;</b><b class="eui-calendar-times-plus">&plus;</b><input id="eui-range-nn" type="range" min="00" max="60" step="1" value="${sp[1]}"></span>
                </div>
                <div class="eui-calendar-times-item">
                    <span class="eui-calendar-times-title">秒钟</span>
                    <span class="eui-calendar-times-con"><b class="eui-calendar-times-minus">&minus;</b><b class="eui-calendar-times-plus">&plus;</b><input id="eui-range-ss" type="range" min="00" max="60" step="1" value="${sp[2]}"></span>
                </div>
                <div class="eui-calendar-times-item eui-calendar-times-now">
                    <button class="eui-btn now" eui="sm,primary,pull-left">当前时间</button>
                    <button class="eui-btn close" eui="sm,pull-right">关闭</button>
                </div>
            </div>`;
    
            const $dom=$(t);
            that.find('.eui-calendar-main-con').append($dom);
    
            that.find('.eui-calendar-times-con b').click(function(){
                const ipt=$(this).siblings('input');
                let v=Number(ipt.val());
                if($(this).hasClass('eui-calendar-times-minus')&&v>ipt.attr('min')){
                    v-=1;
                }else if($(this).hasClass('eui-calendar-times-plus')&&v<ipt.attr('max')){
                    v+=1;
                };
                ipt.val(v);
                if(v.toString().length==1) v='0'+v;
                let m=gId(ipt.attr('id'));
                cg(v,m);
                f();
            });
    
            let gId=id=>{
                let m;
                switch(id){
                    case 'eui-range-hh': m='#eui-calendar-times-show-hh';
                        break;
                    case 'eui-range-nn': m='#eui-calendar-times-show-nn';
                        break;
                    default: m='#eui-calendar-times-show-ss';
                };
                return m;
            };
    
            // 获取时分秒显示值
            const h=$dom.find('#eui-range-hh');
            const n=$dom.find('#eui-range-nn');
            const s=$dom.find('#eui-range-ss');
    
            // 鼠标拖动滑块
            $dom.find('input[type="range"]').mousedown(function(){
                $(this).on('mousemove change',function(e){
                    let v=$(this).val();
                    if(v.length==1) v='0'+v;
                    let m=gId($(this).attr('id'));
                    cg(v,m);
                    // 输出
                    if(e.type=='change') f();
                });
            });
            // 改变数值
            let cg=(v,m)=>{
                m=$dom.find(m);
                m=$dom.find(m);
                // 动态显示值
                m.addClass('on').text(v);
            };
            // 鼠标放开滑块
            $dom.find('input[type="range"]').on('mouseup',function(){
                $(this).off('mousemove');
                // 输出
                f();
            });
            // 输出hhh数据
            const f=()=>{
                // 添加时分秒数据
                hhh=' ';
                $dom.find('.eui-calendar-times-show i').each((i,n)=>{
                    hhh+=$(n).text();
                    if($dom.find('.eui-calendar-times-show i').length!=i+1) hhh+=':';
                });
                $dom.find('.on').removeClass('on');
                // 如果还没选ttt，输出当天
                if(!ttt) vto();
                $n.val(ttt+hhh);
            };
        };
        // 绑定日期选择
        const bindDay=()=>{
            that.find(".eui-caltab-day a").mousedown(function(){
                let a = new Date(that.find(".currentYear").text() + "/" + that.find(".currentMonth").text() + "/1");
                let d = $(this).text();
                if($(this).hasClass("prevD")) {
                    // 如果选择上个月的天数
                    a.setMonth(a.getMonth() - 1);
                    a.setDate(d);
                    that.find(".eui-prev-month").triggerHandler("mousedown");
                }else if($(this).hasClass("nextD")) {
                    // 如果选择下个月的天数
                    a.setMonth(a.getMonth() + 1);
                    a.setDate(d);
                    that.find(".eui-next-month").triggerHandler("mousedown");
                };
                // 输出日期
                ttt = a.getFullYear() + "-" + (Number(a.getMonth() + 1) < 10 ? "0" + Number(a.getMonth() + 1) : Number(a.getMonth() + 1)) + "-" + (Number(d) < 10 ? "0" + d: d);
    
                $(".eui-calendar table a").removeClass("select");
                // 给已选日期加标记
                $(".eui-caltab-day a:contains('" + d + "')").each(function() {
                    d == $(this).text() && !$(this).hasClass("prevD") && !$(this).hasClass("nextD") && $(this).addClass("select");
                });
    
                if(c.times!='true'){
                    $n.val(ttt);
                    that.remove();
                }else{
                    $n.val(ttt+hhh);
                };
                return false;
            });
        };
        // 绑定月份选择
        const bindMonth=()=>{
            that.find(".eui-caltab-month a").mousedown(function() {
                const a = setD(Number(that.find(".currentYear").text()), Number($(this).attr("val")));
                that.find(".currentMonth").text(Number($(this).attr("val")) + 1);
                update('close',a);
                bindDay();
            });
        };
        // 选择年份选择
        const bindYear=()=>{
            that.find(".eui-caltab-year a").mousedown(function() {
                const a = setD(Number($(this).text()), Number(that.find(".currentMonth").text()) - 1);
                that.find(".currentYear").text(Number($(this).text()));
                update('close',a);
                bindDay();
            });
        };
        // 生成日期天数
        const setD=(a, b)=>{
            const newDate = new Date(a, b, 1);
            newDate.setDate(0);
            let d = 1;
            let h = newDate.getDate();
            newDate.setDate(1);
            newDate.setMonth(newDate.getMonth() + 1);
            let m = newDate.getDay();
            h = h - m + 1;
            newDate.setMonth(newDate.getMonth() + 1);
            newDate.setDate(0);
            let o = newDate.getDate(),
            g = "<table class='eui-caltab-day'><tr><th>\u65e5</th><th>\u4e00</th><th>\u4e8c</th><th>\u4e09</th><th>\u56db</th><th>\u4e94</th><th>\u516d</th></tr>",
            i = w(),
            l = "",
            p = "",
            t = "";
            let xx=5;
            if(m>4&&o>30) xx=6;
            for(let x = 0; x < xx; x++){
                g += "<tr>";
                for(let y = 0; y < 7; y++){
                    let j = x * 7 + y + 1 - m;
                    p = l = "";
                    if(c.lower != NaN && c.lower > new Date(newDate.getFullYear(), newDate.getMonth(), j+1) || c.upper != NaN && new Date(newDate.getFullYear(), newDate.getMonth(), j) > c.upper) 
                    if(0 < j && j <= o) {
                        if(today.getFullYear() == e && today.getMonth() == f && j == today.getDate()) l = "current";
                        g += `<td><span class="${l}">${j}</span></td>`;
                    }else if(j <= 0) {
                        if(today.getFullYear() == e && today.getMonth() - 1 == f && h == today.getDate()) l = "current";
                        g += `<td><span class="${l}">${h}</span></td>`;
                        h++;
                    }else{
                        if(j > o) {
                            if(today.getFullYear() == e && today.getMonth() + 1 == f && d == today.getDate()) l = "current";
                            g += `<td><span class="${l}">${d}</span></td>`;
                            d++;
                        };
                    }else if(0 < j && j <= o) {
                        if(today.getFullYear() == e && today.getMonth() == f && j == today.getDate()) l = "current";
                        if(newDate.getFullYear() == i.getFullYear() && newDate.getMonth() == i.getMonth() && j == i.getDate()) p = "select";
                        g += `<td><a class="${p} ${l}">${j}</a></td>`;
                    }else if(j <= 0) {
                        if(today.getFullYear() == e && today.getMonth() - 1 == f && h == today.getDate()) l = "current";
                        if(newDate.getFullYear() == i.getFullYear() && newDate.getMonth() - 1 == i.getMonth() && h == i.getDate()) p = "select";
                        g += `<td><a class="prevD ${p} ${l}">${h}</a></td>`;
                        h++;
                    }else if(j > o) {
                        if(today.getFullYear() == e && today.getMonth() + 1 == f && d == today.getDate()) l = "current";
                        if(newDate.getFullYear() == i.getFullYear() && newDate.getMonth() + 1 == i.getMonth() && d == i.getDate()) p = "select";
                        g += `<td><a class="nextD ${p} ${l}">${d}</a></td>`;
                        d++;
                    };
                    g = g.replace("class=' '","");
                };
                g += "</tr>";
            };
            g += "</table>";
            return g;
        };
        // 生成月份
        const setM=a=>{
            let b = w(),
            d = `<table class='eui-caltab-month'>
            <tr>
                <td><a val='0' ${a == b.getFullYear() && 0 == b.getMonth() ? "class='select'": ""} ${a == e && 0 == f ? "class='current'": ""}>\u4e00\u6708</a></td>
                <td><a val='1' ${a == b.getFullYear() && 1 == b.getMonth() ? "class='select'": ""} ${a == e && 1 == f ? "class='current'": ""}>\u4e8c\u6708</a></td>
                <td><a val='2' ${a == b.getFullYear() && 2 == b.getMonth() ? "class='select'": ""} ${a == e && 2 == f ? "class='current'": ""}>\u4e09\u6708</a></td>
                <td><a val='3' ${a == b.getFullYear() && 3 == b.getMonth() ? "class='select'": ""} ${a == e && 3 == f ? "class='current'": ""}>\u56db\u6708</a></td>
            </tr>
            <tr>
                <td><a val='4' ${a == b.getFullYear() && 4 == b.getMonth() ? "class='select'": ""} ${a == e && 4 == f ? "class='current'": ""}>\u4e94\u6708</a></td>
                <td><a val='5' ${a == b.getFullYear() && 5 == b.getMonth() ? "class='select'": ""} ${a == e && 5 == f ? "class='current'": ""}>\u516d\u6708</a></td>
                <td><a val='6' ${a == b.getFullYear() && 6 == b.getMonth() ? "class='select'": ""} ${a == e && 6 == f ? "class='current'": ""}>\u4e03\u6708</a></td>
                <td><a val='7' ${a == b.getFullYear() && 7 == b.getMonth() ? "class='select'": ""} ${a == e && 7 == f ? "class='current'": ""}>\u516b\u6708</a></td>
            </tr>
            <tr>
                <td><a val='8' ${a == b.getFullYear() && 8 == b.getMonth() ? "class='select'": ""} ${a == e && 8 == f ? "class='current'": ""}>\u4e5d\u6708</a></td>
                <td><a val='9' ${a == b.getFullYear() && 9 == b.getMonth() ? "class='select'": ""} ${a == e && 9 == f ? "class='current'": ""}>\u5341\u6708</a></td>
                <td><a val='10' ${a == b.getFullYear() && 10 == b.getMonth() ? "class='select'": ""} ${a == e && 10 == f ? "class='current'": ""}>\u5341\u4e00\u6708</a></td>
                <td><a val='11' ${a == b.getFullYear() && 11 == b.getMonth() ? "class='select'": ""} ${a == e && 11 == f ? "class='current'": ""}>\u5341\u4e8c\u6708</a></td>
            </tr>
            </table>`;
            return d;
        };
        // 生成年份
        const setY=a=>{
            a = Math.floor(a / 10) * 10;
            let b = "<table class='eui-caltab-year'>",
            d = w(),
            h = "",
            m = "",
            o = "";
            for(let g = 0; g < 3; g++) {
                b += "<tr>";
                for(let i = 0; i < 4; i++) {
                    m = h = "";
                    if(g + 1 * i + 1 != 1 && (g + 1) * (i + 1) != 12) {
                        if(a == d.getFullYear()) h = "select";
                        if(a == e) m = "current";
                        b += `<td><a class="${h} ${m}">${a}</a></td>`;
                        // b += "<td><a class='" + h + " " + m + "' >" + a + "</a></td>";
                        a++
                    }else if(g + 1 * i + 1 == 1) {
                        if(a - 1 == d.getFullYear()) h = "select";
                        if(a - 1 == e) m = "current";
                        b += `<td><a class="prevY ${h} ${m}" ${o}">${a - 1}</a></td>`;
                        // b += "<td><a class='prevY " + h + " " + m + "' " + o + ">" + (a - 1) + "</a></td>";
                    }else{
                        if(a == d.getFullYear()) h = "select";
                        if(a == e) m = "current";
                        b += `<td><a class="nextY ${h} ${m}" ${o}>${a}</a></td>`;
                        // b += "<td><a class='nextY " + h + " " + m + "' " + o + ">" + a + "</a></td>";
                    };
                };
                b += "</tr>";
            };
            b += "</table>";
            return b;
        };
        // 切换
        const update=(s,d,m)=>{
            const reserve=that.find(".eui-calendar-reserve");
            const enabled=that.find(".eui-calendar-enabled");
            if(s=='open'){
                reserve.html(d);
                reserve.css('top',35);
            }else if(s=='close'){
                reserve.empty();
                reserve.css('top','-100%');
                enabled.html(d);
            }else{
                if(m){
                    reserve.html(d);
                }else{
                    enabled.html(d);
                };
            };
        };
        // 格式化日期
        const w=()=>{
            const reg = /(\d\d\d\d)(\W)?(\d\d)(\W)?(\d\d)/g;
            let a = $n.val();
            a = a.replace(reg, "$1/$3/$5@").split("@")[0];
            return new Date(a);
        };
        // 操作绑定
        const mp=()=>{
            let d;
            that.find(".eui-prev-month").mousedown(()=>{
                if($('.eui-calendar').find(".eui-calendar-reserve > .eui-caltab-month").length > 0) {
                    d = setM(Number($('.eui-calendar').find(".currentYear").text()) - 1);
                    update('prev',d,true);
                    bindMonth();
                    $('.eui-calendar').find(".currentYear").text(Number($('.eui-calendar').find(".currentYear").text()) - 1);
                }else if($('.eui-calendar').find(".eui-calendar-reserve > .eui-caltab-year").length > 0) {
                    d = setY(Number($('.eui-calendar').find(".currentYear").text()) - 10);
                    update('prev',d,true);
                    bindYear();
                    $('.eui-calendar').find(".currentYear").text(Number($('.eui-calendar').find(".currentYear").text()) - 10);
                }else if($('.eui-calendar').find(".eui-calendar-enabled > .eui-caltab-day").length > 0) {
                    const a = $('.eui-calendar').find(".currentYear"),
                    b = $('.eui-calendar').find(".currentMonth"),
                    d = setD(Number(a.text()), Number(b.text()) - 2);
                    update('prev',d);
                    if(Number(b.text()) != 1) b.text(Number(b.text()) - 1);
                    else{
                        a.text(Number(a.text()) - 1);
                        b.text("12");
                    }
                    bindDay();
                };
                return false;
            });
            that.find(".eui-next-month").mousedown(()=>{
                if(that.find(".eui-calendar-reserve > .eui-caltab-month").length > 0) {
                    d = setM(Number(that.find(".currentYear").text()) + 1);
                    update('next',d,true);
                    bindMonth();
                    that.find(".currentYear").text(Number(that.find(".currentYear").text()) + 1)
                }else if(that.find(".eui-calendar-reserve > .eui-caltab-year").length > 0) {
                    d = setY(Number(that.find(".currentYear").text()) + 10);
                    update('next',d,true);
                    bindYear();
                    that.find(".currentYear").text(Number(that.find(".currentYear").text()) + 10)
                }else if(that.find(".eui-calendar-enabled > .eui-caltab-day").length > 0) {
                    const a = that.find(".currentYear"),
                    b = that.find(".currentMonth"),
                    d = setD(Number(a.text()), Number(b.text()));
                    update('next',d);
                    if(Number(b.text()) != 12) b.text(Number(b.text()) + 1);
                    else{
                        a.text(Number(a.text()) + 1);
                        b.text("1");
                    }
                    bindDay();
                };
                return false;
            });
            that.find(".eui-month-txt").mousedown(()=>{
                const a = setM(Number(that.find(".currentYear").text()));
                update('open',a);
                bindMonth();
                return false;
            });
            that.find(".eui-year-txt").mousedown(()=>{
                const a = setY(Number(that.find(".currentYear").text()));
                update('open',a);
                bindYear();
                return false;
            });
        };
    };
    // input处理，添加icon以及绑定点击事件
    el.each((i,n)=>{
        const $n=$(n);
        if($n.parent('.eui-date-wrap').length) return; // 防止重复init
        // 获取自定义属性
        const o_ab=n.attributes;
        let o_ar='';
        for(let j=0;j<o_ab.length;j++){
            o_ar+=' '+o_ab[j].name+'="'+o_ab[j].value+'"';
        };
        const dom=
            `<div class="eui-date-wrap">
                <input ${o_ar} readonly>
                <i class="eui-icon-date"></i>
            </div>`;
            const that=$(dom);
        $n.replaceWith(that);
        
        // 绑定插件
        calendar(that.find('.eui-date'));
        // 清空事件
        that.find('.eui-icon-date').click(()=>{
            const dat=that.find('.eui-date');
            if(dat.val()==''){
                that.find('.eui-date').click();
            }else{
                dat.val('');
            };
        });
    });
};

// 调整上下位置，用于日历控件和下拉框
Eui.prototype.place=dt=>{
    const z={
        rel:dt.rel,
        abs:dt.abs,
        int:dt.interval || 0,
        z:dt.rel.attr('eui'),
        tp:dt.type,
        // 获取浏览器、滚动条高度
        w:$(window).height(),
        s:$(document).scrollTop(),
        // 获取rel、abs高
        rh:dt.rel.outerHeight(),
        ah:dt.abs.outerHeight(),
        // rel相对文档高度
        rp:dt.rel.offset().top,
        // 
        h:null,
        ub:null,
        // 最终top值
        top:0
    };

    // 上下可显示空间
    z.ub={
        u:z.rp-z.s,
        d:z.s+z.w-z.rp-z.rh
    };
    // 判断方向
    z.h=z.ah+z.int;
    if(!z.z) z.z='';
    if(z.ub.d<z.h && z.ub.u>z.h){
        // 上方
        if(z.tp=='select'){
            z.rel.attr('eui',z.z+',top');
        }else{
            z.top=-(z.h);
        };
    }else{
        // 下方
        if(z.tp=='select'){
            z.rel.attr('eui',z.z.replace(',top',''));
        }else{
            z.top=z.rh+z.int;
        };
    };
    if(z.tp!='select') return z.abs.css('top',z.top);
};

// nav导航
Eui.prototype.nav=el=>{
    el.each((i,n)=>{
        const $n=$(n);
        // 防止重复init
        if($n.find('.eui-nav-sliding').length) return;
        // 添加下划线元素
        $n.append('<div class="eui-nav-sliding"></div>');

        const z={
            s:$n.find('.eui-nav-sliding'),
            li:$n.find('li'),
            // ul左距
            uw:$n.offset().left,
            sto:null
        };
        
        setTimeout(()=>{
            z.s.attr('eui','anim-normal');
        },100);
        
        // 更新ul左距，避免浏览器调整后下划线位置不准
        $(window).resize(()=>{
            z.uw=$n.offset().left;
        });

        // 初始化下划线位置和长度
        z.s.css({
            width:$n.find('.active').innerWidth(),
            left:$n.find('.active').offset().left-z.uw
        });
        
        // hover事件和click更改active
        z.li.hover(function(){
            // 下划线移动并改变长度
            const w=$(this).innerWidth();
            z.s.css({width:w,left:$(this).offset().left-z.uw});
            // 展开二级
            $(this).find('.eui-nav-child').addClass('open');
            $(this).siblings().find('.eui-nav-child').removeClass('open');
            clearTimeout(z.sto);
        },function(){
            // 下划线移动回active处,延时500ms
            const $at=$n.find('.active');
            const that=$(this);
            z.sto=setTimeout(function(){
                z.s.css({width:$at.innerWidth(),left:$at.offset().left-z.uw});
                // 收起二级
                that.find('.eui-nav-child').removeClass('open');
            },500);
        }).click(function(){
            $(this).addClass('active').siblings().removeClass('active');
        });
    });
};

// linkage联动
Eui.prototype.linkage=dt=>{
    // 分割select元素  获取数据
    const z={
        slt:dt.el.split(','),
        data:dt.data,
        // 接收selected索引，遍历data，对比索引输出数据
        sc(ii,x){
            // 获取对应省份所有数据
            const ct=z.data[x[0]];
            // 遍历x数据索引
            z.slt.forEach((n,i)=>{
                // 更新change事件之后的select
                if(i>ii){
                    let dd=ct[i];
                    if(i>1)dd=dd[x[i-1]];
                    let op='';
                    dd.forEach((p,z)=>{
                        op+=`<option value="${p}">${p}</option>`;
                    });
                    $(n).html(op);
                };
            });
            // 回调，筛除初始调用
            if(dt.call && x!='0,0,0') dt.call();
        },
        // change调用此函数，获取selected索引值，传给sc输出数据
        cg(ii){
            let x=[];
            for(let i=0;i<z.slt.length;i++){
                // 如果change的是一级，初始化其他的索引为0
                let dx;
                if(ii==0 && i==0 || ii!=0){
                    dx=$(z.slt[i]+' option:selected').index();
                }else{
                    dx=0;
                };
                x.push(dx);
            };
            // 传个change的select索引，不更新上级，传下级数据索引
            z.sc(ii,x);
        },
        // 初始化输出一级option
        int(){
            let op='';
            z.data.forEach(n=>{
                op+='<option value="'+n[0]+'">'+n[0]+'</option>';
            });
            $(z.slt[0]).html(op);
            // 默认选择第一项
            if($(z.slt[0]).attr('data-val')){
                $(z.slt[0]).val($(z.slt[0]).attr('data-val'));
                z.cg(0);
                if($(z.slt[1]).attr('data-val')){
                    $(z.slt[1]).val($(z.slt[1]).attr('data-val'));
                    z.cg(1);
                };
                if($(z.slt[2]).attr('data-val')) $(z.slt[2]).val($(z.slt[2]).attr('data-val'));
            }else{
                z.cg(0);
            };
        },
        init:dt.init
    };
    if(z.slt.length<2) return; // 没找到一级select，返回
    
    // 初始化一级
    z.int();

    // select绑定change事件，并传元素索引，辨别触发的是第几个select
    z.slt.forEach((n,i)=>{
        $(n).off().on('change',function(){
            z.cg(i);
        });
    });
    if(z.init && z.init[0]){
        z.init.forEach(function(n,i){
            if(z.init[i]) $(z.slt[i]).find('option[value='+n+']').prop('selected',true);
            if(i==0) $(z.slt[0]).change();
        });
    }
};

// 分页器
Eui.prototype.paging=dt=>{
    const z={
        el:$(dt.el),
        // 总数据量/分割数=总页数（最后一页）
        total:Math.ceil(dt.amount/dt.divide),
        show:dt.show,
        prev:dt.prev,
        first:dt.first,
        last:dt.last,
        next:dt.next,
        // 核心方法
        pg(current){
            let dom='';
            // 是否载入prev和first
            if(current!=1 && z.prev!=''){
                dom+='<a class="prev" href="javascript:;">'+z.prev+'</a>';
            };
            if(current!=1 && z.first!='' && z.total>z.show){
                dom+='<a class="first" href="javascript:;">'+z.first+'</a>';
            };
            // 缩进值
            const rt=parseInt((z.show-1)/2);
    
            // 循环开始条件值
            let st=1;
            if(current>rt) st=current-rt;
            if(current>z.total-rt) st=z.total-z.show+1;
    
            // 生成分页按钮
            for(let i=st;i<st+z.show;i++){
                // 如果超出总数退出循环
                if(i>z.total) return;
                // 如果是当前页
                if(i==current){
                    dom+='<a class="active" href="javascript:;">'+i+'</a>';
                }else if(i>0){
                    dom+='<a href="javascript:;">'+i+'</a>';
                };
            };
    
            // 是否载入last和next
            if(current!=z.total && z.last!='' && z.total>z.show){
                dom+='<a class="last" href="javascript:;">'+z.last+'</a>';
            };
            if(current!=z.total && z.next!=''){
                dom+='<a class="next" href="javascript:;">'+z.next+'</a>';
            };
            z.el.html(dom);
        }
    };
    // 如果总页数=1,就隐藏掉分页器
    if(z.total==1) return z.el.hide();

    if(z.show===undefined) z.show=5;
    if(z.prev===undefined) z.prev='上一页';
    if(z.first===undefined) z.first='首页';
    if(z.last===undefined) z.last='尾页';
    if(z.next===undefined) z.next='下一页';
    
    // 初始化
    z.pg(dt.current);

    // 切换事件
    $(document).on('click',dt.el+' a',function(){
        const c=$(this).attr('class');
        let index=0;
        if(c=='active') return;
        if(c==undefined){
            index=Number($(this).text());
        }else{
            index=Number($(this).siblings('.active').text());
        };
        // 功能按钮操作
        switch(c){
            case 'prev': index--;
                break;
            case 'next': index++;
                break;
            case 'first': index=1;
                break;
            case 'last': index=z.total;
                break;
        };
        // 防错拦截
        if(index<1||index>z.total) return;
        // 重置分页器
        z.pg(index);
        // 启动回调
        if(dt.callback) dt.callback(index);
    });
};

// loading加载
Eui.prototype.loading=dt=>{
    const z={
        el:dt.el,
        state:dt.state,
        bg:dt.bg,
        color:dt.color,
        ld:'',
        that:null
    };
    if(typeof z.el=='string') z.el=$(z.el);
       
    if(z.state){
        if(z.el.children().hasClass('eui-loading')) return; // 防止重复加载loading
        z.ld=`<div class="eui-loading open" style="background:${z.bg};"><i class="eui-icon-loading" style="color:${z.color}!important"></i></div>`;
        if(z.el.css('position')=='static') z.el.css('position','relative');
        z.that=$(z.ld);
        z.el.append(z.that);
    }else{
        z.ld=z.el.find('.eui-loading');
        if(z.ld.length){
            z.ld.removeClass('open');
            setTimeout(()=>{
                z.ld.remove();
            },400);
        };
    };
};

// 进度条
Eui.prototype.progress=dt=>{
    const z={
        el:dt.el,
        type:dt.type,
        max:dt.max,
        bg:dt.bg || 'rgba(255,255,255,0.9)',
        // 随机增长加载值，1～5
        v:()=>Math.floor(Math.random()*5+1),
        // 随机时间间隔，100～300
        t:()=>Math.floor(Math.random()*300+1)
    };
    if(typeof z.el=='string') z.el=$(z.el);
    if(!z.max) z.max=80;
    // 进度增长
    const rise=(bar,d)=>{
        let p=Number(bar.find('.eui-progress-bar').attr('eui-progress'));
        let time=z.t();
        if(d==100){
            if(p==d) return;
            p=100;
        }else{
            if(p>d) return;
        };
        // 进度递增 防止超出 
        p+=z.v();
        if(p>z.max) p=z.max;
        if(p>100) p=100;
        bar.find('.eui-progress-bar').attr('eui-progress',p).css('width',p+'%');
        if(z.type!='head') bar.find('.eui-progress-info').html(`${p}<i class="eui-progress-mark">%</i>`);
        if(p==100){
            setTimeout(()=>{
                bar.remove();
            },300);
        }else{
            setTimeout(()=>{
                rise(bar,d);
            },time);
        };
        return;
    };
    // 是否创建进度条
    if(!z.el.children('.eui-progress-wrap').length){
        let that;
        if(z.type!='head'){
            const pgs=
            `<div class="eui-progress-wrap" style="background:${z.bg}">
                <div class="eui-progress">
                    <div class="eui-progress-bar" eui-progress="0"><span class="eui-progress-info">0<i class="eui-progress-mark">%</i></span></div>
                </div>
            </div>`;
            that=$(pgs);
            z.el.append(that);
            // 进度条宽高和定位
            const cc=()=>{
                const c={};
                c.w=z.el.innerWidth();
                c.h=z.el.innerHeight();
                c.l=z.el.offset().left;
                c.t=z.el.offset().top;
                that.css({
                    width:c.w,
                    height:c.h,
                    left:c.l,
                    top:c.t
                });
            };
            cc();
            $(window).resize(cc);
        }else{
            const pgs=
            `<div class="eui-progress-wrap eui-progress-head">
                <div class="eui-progress">
                    <div class="eui-progress-bar" eui-progress="0"></span></div>
                </div>
            </div>`;
            that=$(pgs);
            z.el.append(that);
        };
        rise(that,z.max);
    }else{
        const that=z.el.children('.eui-progress-wrap');
        rise(that,z.max);
    };
};

/*
2017-09-03
表单校验模块
*/

Eui.prototype.validate=dt=>{
	// 非法名字
	const ne = ["妈", "爸", "爹", "爷", "姐", "哥", "瞧瞧", "你猜", "高富帅", "白富美", "王者荣耀", "武则天", "秦始皇", '英雄联盟', "屌丝", "先生", "女士", "小姐", "帅哥", "美女", "啊啊", "奥巴马", "毛泽东", "尼玛", "你", "我", "草", "泥", "痴", "狗", "猫", "喵", "蛋", "主任", "老师", "师傅", "医生", "教授", "老", "猪", "呵呵", "贱", "二", "三", "四", "五", "六", "七", "八", "九", "壹", "贰", "叁", "肆", "伍", "陆", "柒", "捌", "玖", "拾", "佰", "仟", "傻"];
	// 百家姓数组
	const bjx = ["赵","钱","孙","李","周","吴","郑","王","冯","陈","褚","卫","蒋","沈","韩","杨","朱","秦","尤","许","何","吕","施","张","孔","曹","严","华","金","魏","陶","姜","戚","谢","邹","喻","柏","水","窦","章","云","苏","潘","葛","奚","范","彭","郎","鲁","韦","昌","马","苗","凤","花","方","俞","任","袁","柳","酆","鲍","史","唐","费","廉","岑","薛","雷","贺","倪","汤","滕","殷","罗","毕","郝","邬","安","常","乐","于","时","傅","皮","卞","齐","康","伍","余","元","卜","顾","孟","平","黄","和","穆","萧","尹","姚","邵","湛","汪","祁","毛","禹","狄","米","贝","明","臧","计","伏","成","戴","谈","宋","茅","庞","熊","纪","舒","屈","项","祝","董","梁","杜","阮","蓝","闵","席","季","麻","强","贾","路","娄","危","江","童","颜","郭","梅","盛","林","刁","锺","徐","邱","骆","高","夏","蔡","田","樊","胡","凌","霍","虞","万","支","柯","昝","管","卢","莫","经","房","裘","缪","干","解","应","宗","丁","宣","贲","邓","郁","单","杭","洪","包","诸","左","石","崔","吉","钮","龚","程","嵇","邢","滑","裴","陆","荣","翁","荀","羊","於","惠","甄","麴","家","封","芮","羿","储","靳","汲","邴","糜","松","井","段","富","巫","乌","焦","巴","弓","牧","隗","山","谷","车","侯","宓","蓬","全","郗","班","仰","秋","仲","伊","宫","宁","仇","栾","暴","甘","钭","历","戎","祖","武","符","刘","景","詹","束","龙","叶","幸","司","韶","郜","黎","蓟","溥","印","宿","白","怀","蒲","邰","从","鄂","索","咸","籍","赖","卓","蔺","屠","蒙","池","乔","阳","郁","胥","能","苍","双","闻","莘","党","翟","谭","贡","劳","逄","姬","申","扶","堵","冉","宰","郦","雍","却","璩","桑","桂","濮","牛","寿","通","边","扈","燕","冀","僪","浦","尚","农","温","别","庄","晏","柴","瞿","阎","充","慕","连","茹","习","宦","艾","鱼","容","向","古","易","慎","戈","廖","庾","终","暨","居","衡","步","都","耿","满","弘","匡","国","文","寇","广","禄","阙","东","欧","殳","沃","利","蔚","越","夔","隆","师","巩","厍","聂","晁","勾","敖","融","冷","訾","辛","阚","那","简","饶","空","曾","毋","沙","乜","养","鞠","须","丰","巢","关","蒯","相","查","后","荆","红","游","竺","权","逮","盍","益","桓","公","万俟","司马","上官","欧阳","夏侯","诸葛","闻人","东方","赫连","皇甫","尉迟","公羊","澹台","公冶","宗政","濮阳","淳于","单于","太叔","申屠","公孙","仲孙","轩辕","令狐","钟离","宇文","长孙","慕容","司徒","司空","召","有","舜","丛","岳","寸","贰","皇","侨","彤","竭","端","赫","实","甫","集","象","翠","狂","辟","典","良","函","芒","苦","其","京","中","夕","之","章佳","那拉","冠","宾","香","果","纳喇","乌雅","范姜","碧鲁","张廖","张简","图门","太史","公叔","乌孙","完颜","马佳","佟佳","富察","费莫","蹇","称","诺","来","多","繁","戊","朴","回","毓","税","荤","靖","绪","愈","硕","牢","买","但","巧","枚","撒","泰","秘","亥","绍","以","壬","森","斋","释","奕","姒","朋","求","羽","用","占","真","穰","翦","闾","漆","贵","代","贯","旁","崇","栋","告","休","褒","谏","锐","皋","闳","在","歧","禾","示","是","委","钊","频","嬴","呼","大","威","昂","律","冒","保","系","抄","定","化","莱","校","么","抗","祢","綦","悟","宏","功","庚","务","敏","捷","拱","兆","丑","丙","畅","苟","随","类","卯","俟","友","答","乙","允","甲","留","尾","佼","玄","乘","裔","延","植","环","矫","赛","昔","侍","度","旷","遇","偶","前","由","咎","塞","敛","受","泷","袭","衅","叔","圣","御","夫","仆","镇","藩","邸","府","掌","首","员","焉","戏","可","智","尔","凭","悉","进","笃","厚","仁","业","肇","资","合","仍","九","衷","哀","刑","俎","仵","圭","夷","徭","蛮","汗","孛","乾","帖","罕","洛","淦","洋","邶","郸","郯","邗","邛","剑","虢","隋","蒿","茆","菅","苌","树","桐","锁","钟","机","盘","铎","斛","玉","线","针","箕","庹","绳","磨","蒉","瓮","弭","刀","疏","牵","浑","恽","势","世","仝","同","蚁","止","戢","睢","冼","种","涂","肖","己","泣","潜","卷","脱","谬","蹉","赧","浮","顿","说","次","错","念","夙","斯","完","丹","表","聊","源","姓","吾","寻","展","出","不","户","闭","才","无","书","学","愚","本","性","雪","霜","烟","寒","少","字","桥","板","斐","独","千","诗","嘉","扬","善","揭","祈","析","赤","紫","青","柔","刚","奇","拜","佛","陀","弥","阿","素","长","僧","隐","仙","隽","宇","祭","酒","淡","塔","琦","闪","始","星","南","天","接","波","碧","速","禚","腾","潮","镜","似","澄","潭","謇","纵","渠","奈","风","春","濯","沐","茂","英","兰","檀","藤","枝","检","生","折","登","驹","骑","貊","虎","肥","鹿","雀","野","禽","飞","节","宜","鲜","粟","栗","豆","帛","官","布","衣","藏","宝","钞","银","门","盈","庆","喜","及","普","建","营","巨","望","希","道","载","声","漫","犁","力","贸","勤","革","改","兴","亓","睦","修","信","闽","北","守","坚","勇","汉","练","尉","士","旅","五","令","将","旗","军","行","奉","敬","恭","仪","母","堂","丘","义","礼","慈","孝","理","伦","卿","问","永","辉","位","让","尧","依","犹","介","承","市","所","苑","杞","剧","第","零","谌","招","续","达","忻","六","鄞","战","迟","候","宛","励","粘","萨","邝","覃","辜","初","楼","城","区","局","台","原","考","妫","纳","泉","老","清","德","卑","过","麦","曲","竹","百","福","言","第五","佟","爱","年","笪","谯","哈","墨","南宫","赏","伯","佴","佘","牟","商","西门","东门","左丘","梁丘","琴","后","况","亢","缑","帅","微生","羊舌","海","归","呼延","南门","东郭","百里","钦","鄢","汝","法","闫","楚","晋","谷梁","宰父","夹谷","拓跋","壤驷","乐正","漆雕","公西","巫马","端木","颛孙","子车","督","仉","司寇","亓官","鲜于","锺离","盖","逯","库","郏","逢","阴","薄","厉","稽","闾丘","公良","段干","开","光","操","瑞","眭","泥","运","摩","伟","铁","迮","付"];
    // 所有eui-rule元素
    const ipt=$(dt.form).find('[eui-rule]');
    // 获取eui-rule参数
	const getv=z=>{
		let ru={};
		const v=z.split(',');
		v.forEach(n=>{
            // 单独处理port值
            if(n.indexOf('port:')!=-1){
                // 处理port配置值
                ru.port=n.substring(n.indexOf(':')+1,n.length).split('][');
                const pd=[];
                ru.port.forEach(nn=>{
                    const lt=nn.indexOf('[');
                    const rt=nn.indexOf(']');
                    if(lt!=-1) nn=nn.substring(lt+1,nn.length);
                    if(rt!=-1) nn=nn.substring(0,rt);
                    pd.push(nn);
                });
                ru.port_type=pd[0];
                ru.port_url=pd[1];
                ru.port_key=pd[2];
                ru.port_ignore=pd[3] || '';
            }else{
                const r=n.split(':');
                switch(r[0]){
                    case 'rule': ru.rule=r[1];
                        break;
                    case 'required': ru.required=true;
                        break;
                    case 'length':
                        if(r[1].indexOf('~')!=-1){
                            const t=r[1].split('~');
                            ru.min=Number(t[0]);
                            ru.max=Number(t[1]);
                        }else{
                            ru.leng=Number(r[1]);
                        };
                        break;
                    case 'interval':
                        if(r[1].indexOf('~')!=-1){
                            const t=r[1].split('~');
                            ru.minval=Number(t[0]);
                            ru.maxval=Number(t[1]);
                        }else{
                            ru.interval=Number(r[1]);
                        };
                        break;
                    case 'title': ru.title=r[1];
                        break;
                    case 'point':
                        if(r[1].indexOf('~')!=-1){
                            const t=r[1].split('~');
                            ru.minpit=Number(t[0]);
                            ru.maxpit=Number(t[1]);
                        }else{
                            ru.point=Number(r[1]);
                        };
                        break;
                    case 'tip': ru.tip=r[1];
                        break;
                    case 'contrast':
                        if(r[1].indexOf('~')!=-1){
                            const t=r[1].split('~');
                            ru.contrast=t[0];
                            ru.contrast_type=t[1];
                        }else{
                            ru.contrast=r[1];
                        };
                        break;
                };
            };
		});
		return ru;
	};
	// 公共校验
	const vd=(n,ru)=>{
        // 如果文本框为禁用状态,直接通过
        if(n.attr('disabled')) return verify.pass++;
        // 取值
		const v=n.val();
		// 非必选值为空，跳过
		if(v=='' && !ru.required) return verify.pass++;
		// 判断校验类别
		let vy;
		switch(ru.rule){
			case 'name':
				vy=verify.name;
				if(!ru.title) ru.title='姓名';
				if(ru.min==undefined) ru.min=2;
				if(ru.max==undefined) ru.max=6;
				break;
			case 'phone':
				vy=verify.phone;
				if(!ru.title) ru.title='手机号';
				ru.leng=11;
				break;
			case 'number':
				vy=verify.number;
				if(!ru.title) ru.title='数字';
				break;
			case 'email':
				vy=verify.email;
				if(!ru.title) ru.title='邮箱';
				break;
			case 'idcard':
				vy=verify.idcard;
				if(!ru.title) ru.title='身份证';
				ru.leng=18;
				break;
			case 'chinese':
				vy=verify.chinese;
				if(!ru.title) ru.title='内容';
				break;
			case 'checkbox':
				vy=verify.checkbox;
				if(!ru.title) ru.title='选项';
				break;
			case 'select':
				vy=verify.select;
				if(!ru.title) ru.title='下拉框';
                break;
            case 'account':
				vy=verify.account;
				if(!ru.title) ru.title='账号';
                break;
            case 'img':
				vy=verify.img;
				if(!ru.title) ru.title='图片';
				break;
			default: if(!ru.title) ru.title='';
        };
		// 如果是checkbox\select\img类型,直接去调用校验方法
		if(ru.rule=='checkbox' || ru.rule=='select' || ru.rule=='img'){
            return vy(n,ru);
        };
		// 公用校验规则
		// 必选为空
		if(v=='' && ru.required) return verify.error(n,ru,'不能为空！');
		// 是否为默认值
		if(dt.illegality){
			for(let i in dt.illegality){
				if(dt.illegality[i]==v) return verify.error(n,ru,'不能为默认值！');
			};
		};
        // 长度校验
        let leng=v.length;
        if(v.indexOf('.')>0) leng-=1; //包含小数点,长度-1
		if(ru.min!=undefined && leng<ru.min) return verify.error(n,ru,'长度不足！');
		if(ru.max!=undefined && leng>ru.max) return verify.error(n,ru,'长度超出！');
        if(ru.leng!=undefined && leng!=ru.leng) return verify.error(n,ru,'长度不正确！');
        // 对比,默认对比是否相同
        if(ru.contrast!=undefined){
            // 默认相同
            let contrast_type=true;
            if(ru.contrast_type=='false') contrast_type=false;
            const contrast=$(ru.contrast).val();
            // 判断是否相同或不同
            if(contrast!=v && contrast_type) return verify.error(n,ru,'不一致！');
            if(contrast==v && !contrast_type) return verify.error(n,ru,'不能相同！');
        };
        // 删除原有错误提示
        n.siblings('.eui-validate-error,.eui-validate-succee').remove();
        // 是否指定校验方法
		if(vy){
            // 调用对应校验方法,未通过校验则return,防止执行下面的远程校验
            if(vy(n,ru)===false) return;
        }else{
		    // 没指定校验方法，直接通过
            verify.pass++;
        };
        // 判断是否需要远程校验，没通过pass--，因为前面的通过校验已经++了
        if(ru.port) if(verify.port(n,ru)===false) return verify.pass--;
	};
	// 验证大法
	const verify={
		pass:0,
		name(n,ru){
			const v=n.val();
			const reg = /^[\u4E00-\u9FA5]+$/;
			// 是否为中文
			if(!reg.test(v)) return verify.error(n,ru,'必须是中文！');
			// 非法姓名判断
			for(let i=0;i<ne.length;i++){
				if(v.indexOf(ne[i])>=0) return verify.error(n,ru,'不合法！');
			};
			// 百家姓判断
			if(bjx.indexOf(v.substr(0,1))!=-1||bjx.indexOf(v.substr(0,2))!=-1){
				return verify.pass++;
			}else{
				return verify.error(n,ru,'姓氏非百家姓！');
			};
        },
        chinese(n,ru){
			const v=n.val();
            const reg = new RegExp("[`~!@#$^&*()=|{}':;',\\[\\].<>/?~！@#￥……&*（）——|{}【】‘；：”“'。，、？]");
            const reg2 = /^[\u4E00-\u9FA5]+$/;
            // 是否含符号
			if(reg.test(v)) return verify.error(n,ru,'不能包含符号！');
			// 是否为中文
            if(!reg2.test(v)) return verify.error(n,ru,'必须是中文！');
			return verify.pass++;
        },
        account(n,ru){
            const v=n.val();
            const reg = /^([a-z]|[A-Z])[0-9a-zA-Z_]+$/;
            if(!reg.test(v)) return verify.error(n,ru,'格式不正确！');
            return verify.pass++;
        },
		phone(n,ru){
			const v=n.val();
			const reg = /^(((13[0-9]{1})|(14[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
			if(isNaN(v)) return verify.error(n,ru,'无效！');
			if(!reg.test(v)) return verify.error(n,ru,'不正确！');
			return verify.pass++;
		},
		number(n,ru){
			const v=n.val();
			// 是否为有效数字
			if(isNaN(v)) return verify.error(n,ru,'无效！');
			// 是否为正数
            if(v<0) return verify.error(n,ru,'不能为负数！');
            // 小数点位数判断
            if(ru.minpit!=undefined || ru.maxpit!=undefined || ru.point!=undefined){
                // 判断是否存在小数点
                if(v.indexOf('.')==-1){
                    let p=ru.point || ru.minpit+'~'+ru.maxpit;
                    return verify.error(n,ru,'请保留'+p+'位小数！');
                }else{
                    const pit=v.split('.')[1].length;
                    if(ru.point && pit!=ru.point) return verify.error(n,ru,'请保留'+ru.point+'位小数！');
                    if(pit<ru.minpit || pit>ru.maxpit) return verify.error(n,ru,'请保留'+ru.minpit+'～'+ru.maxpit+'位小数！');
                }
            };
            // 数值区间判断
            if(ru.minval!=undefined && v<ru.minval) return verify.error(n,ru,'不能小于'+ru.minval+'！');
            if(ru.maxval!=undefined && v>ru.maxval) return verify.error(n,ru,'不能大于'+ru.maxval+'！');
            if(ru.interval!=undefined && v!=ru.interval) return verify.error(n,ru,'必须等于'+ru.interval+'！');
            return verify.pass++;
		},
		email(n,ru){
			const v=n.val();
			// 规则校验
			const reg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
			if(!reg.test(v)){
				return verify.error(n,ru,'无效！');
			};
			return verify.pass++;
		},
		idcard(n,ru){
			let v=n.val();
			// 小写x转大写
			if(v.substring(17)=='x') v=v.substring(0,17)+'X';

			//身份证省的编码 
			const idcity={ 
				11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",
				21:"辽宁",22:"吉林",23:"黑龙江",31:"上海",32:"江苏", 
				33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南", 
				42:"湖北",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆", 
				51:"四川",52:"贵州",53:"云南",54:"西藏",61:"陕西",62:"甘肃", 
				63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外" 
			};
			//取身份证前两位,校验省份 
			if(!idcity[v.substr(0,2)]) return verify.error(n,ru,'省份不正确！');
			
			// 校验出生日期
			const oy=v.substr(6,4) + '/' + v.substr(10,2) + '/'+v.substr(12,2);
			const oday = new Date(oy);
			const now_year = new Date().getFullYear();
			let om=(oday.getMonth()+1);
			let od=oday.getDate();
			if(om<10) om='0'+om;
			if(od<10) od='0'+od;
			if(oy!=oday.getFullYear() + '/' + om + '/' + od) return verify.error(n,ru,'日期不正确！');
			//判断年份的范围（1岁到120岁之间)
			const tm = now_year - oday.getFullYear(); 
			if(tm < 1 || tm > 120) return verify.error(n,ru,'出生年份不正确！');

			// 最后一位校验码
			const arr_i = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2]; 
			const arr_c = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];
			let ct = 0;
			for(let i = 0; i < 17; i ++){ 
				ct += v.substr(i, 1) * arr_i[i];
			};
			const vn = arr_c[ct % 11]; 
			if(vn != v.substr(17, 1)) return verify.error(n,ru,'号码不正确！');
			return verify.pass++;
		},
		checkbox(n,ru){
			const leng=n.find(':checked').length;
            // 必选判断
            if(leng==0 && ru.required) return verify.error(n,ru,'必须选择！');
            // 选择数量判断
            if(ru.min!=undefined && leng<ru.min) return verify.error(n,ru,'选项不足'+ru.min+'个！');
            if(ru.max!=undefined && leng>ru.max) return verify.error(n,ru,'选项超出'+ru.max+'个！');
            if(ru.leng!=undefined && leng!=ru.leng) return verify.error(n,ru,'选项必须为'+ru.leng+'个！');
			n.siblings('.eui-validate-error,.eui-validate-succee').remove();
			return verify.pass++;
		},
		select(n,ru){
			const v=n.find(':selected').text();
			// 必选判断
			if(!ru.required) return verify.pass++;
			// 判断是否非法值
			if(dt.illegality){
				for(let i in dt.illegality){
					if(dt.illegality[i]==v) return verify.error(n,ru,'不能选默认项！');
				};
			};
			n.siblings('.eui-validate-error,.eui-validate-succee').remove();
			return verify.pass++;
        },
        img(n,ru){
            const leng=n.find('img').length;
            // 图片数量判断
            if(ru.min!=undefined && leng<ru.min) return verify.error(n,ru,'不足'+ru.min+'张！');
            if(ru.max!=undefined && leng>ru.max) return verify.error(n,ru,'超出'+ru.max+'张！');
            if(ru.leng!=undefined && leng!=ru.leng) return verify.error(n,ru,'必须为'+ru.leng+'张！');
            n.siblings('.eui-validate-error,.eui-validate-succee').remove();
            return verify.pass++;
        },
        port(n,ru){
            const v=n.val();
            let pt=''; // 记录校验结果
			$.ajax({
				type:ru.port_type,
				url:ru.port_url,
				data:ru.port_key+'='+v+'&'+ru.port_ignore,
				async:false, //同步进行
				success:function(data){
					return pt=data;
				},
				error:function(){
					return pt='ajax_error';
				}
            });
            // 判断ajax校验结果
            if(pt!=''){
                if(pt=='ajax_error'){
                    return verify.error(n,ru,'校验时服务器异常！');
                }else if(pt=='success'){
                    // 成功提示
                    return verify.error(n,ru,v+'可以使用！',true);
                }else{
                    return verify.error(n,ru,v+'不能使用！');
                };
            };
        },
		error(n,ru,info,status){
            // 提示信息
			let tip='';
			if(ru.tip){
				tip=ru.tip;
			}else{
				tip=ru.title+info;
            };
            // 获取样式配置
			let pt='bottom';
			if(dt.position) pt=dt.position;
			// 渲染
			const $pt=n.parent();
            if($pt.css('position')=='static') $pt.css('position','relative');
            n.siblings('.eui-validate-error,.eui-validate-succee').remove();
            let ty='error';
            if(status) ty='succee';
            $pt.append(`<div class="eui-validate-${ty} eui-validate-${pt}">${tip}</div>`);
            if(status){
                // 成功
                return true;
            }else{
                // 错误
                return false;
            };
        }
	};
    // 判断是否绑定blur事件
	if(dt.blur!=false){ 
        $(document).on('blur',dt.form+' [eui-rule]',function(){
            // 只读的输入框和select元素,blur时不校验,防止eui-select等元素校验时机错误
            if($(this).attr('readonly') || $(this).prop("tagName")=='SELECT') return;
            // 获取配置
            let ru=getv($(this).attr('eui-rule')); 
            // 调用公共校验
            vd($(this),ru);
        });
    };
    // 获得焦点去除提示
    $(document).on('focus',dt.form+' [eui-rule]',function(){
        // 只读的输入框和select元素,blur时不校验,防止eui-select等元素校验时机错误
        $(this).siblings('.eui-validate-error').remove();
    });
	// 提交按钮，触发所有blur事件，再判断验证是否全部通过
	$(dt.submit).click(()=>{
		// 启动所绑表单所有ipt的blur事件，再次校验全部ipt
        verify.pass=0;
        // 提交前重新获取并校验一次表单，防止一些动态加入的ipt没有绑定上，导致校验数量与总数量不符
        const ipt=$(dt.form).find('[eui-rule]');
        ipt.each(function(){
            // 获取配置
            let ru=getv($(this).attr('eui-rule'));
            // 开启校验
            vd($(this),ru);

            if($(this).attr('readonly') || $(this).prop("tagName")=='SELECT'){
                // 绑定change后删除错误提示
                $(this).one('mouseup',function(){
                    $(this).siblings('.eui-validate-error,.eui-validate-succee').remove();
                });
            };
        });
        // 比对需要校验的元素数量与通过数量是否一致
		if(verify.pass==ipt.length && verify.pass!=0){
			// 校验通关，执行函数
			dt.succee();
		}else{
            if(dt.error) return dt.error();
        };
	});
};

/*
2018-01-02
多窗口
*/
Eui.prototype.tabwin=dt=>{
    const z={
        l:typeof dt.menu=='string'? $(dt.menu) : dt.menu,
        w:typeof dt.main=='string'? $(dt.main) : dt.main,
        list:[],
        cs:'on',
        max:10,
        start:dt.start,
        end:dt.end,
        close:dt.close,
        only:dt.only,
        initial:dt.initial,
        switch:dt.switch,
        disable:dt.disable,
        error:dt.error,
        tabwinId:0
    };
    if(dt){
        if(dt.active) z.cs=dt.active;
        if(dt.max) z.max=dt.max;
    }else{
        return;
    };
    // 清空class
    const cl=()=>{
        z.l.children('.eui-tabwin-label').removeClass(z.cs);
        z.w.children('.eui-tabwin-main').removeClass(z.cs);
    };
    // 加载方法
    const load=(obj,st,ed)=>{
        // 载入页面
        const txt=$.trim(obj.title);
        const url=$.trim(obj.url);
        let not=1;
        let xx;
        z.list.forEach((n,i)=>{
            if(n.name==txt){
                not=0;
                xx=i;
            };
        });
        if(not){
            if(z.list.length>=z.max) return eui.prompts('开启的页面过多，请先关闭一些！');
            z.list.push({name:txt,url:url});
            cl();
            if(txt==z.only){
                z.l.append(`<a class="eui-tabwin-label ${z.cs} eui-tabwin-only" eui-tabwin-title="${txt}" eui-tabwin-url="${url}" href="javascript:;"><span>${txt}</span></a>`);
            }else{
                z.l.append(`<a class="eui-tabwin-label ${z.cs}" eui-tabwin-title="${txt}" eui-tabwin-url="${url}" href="javascript:;"><span>${txt}</span><i class="eui-icon-close"></a>`);
            };
            if(obj.type=='iframe'){
                z.w.append(`<div class="eui-tabwin-main eui-tabwin-iframe ${z.cs}" eui-tabwin-id="${z.tabwinId}" data-id="${obj.id}" eui-tabwin-title="${txt}" eui-tabwin-url="${url}"><iframe id="iframe${z.tabwinId}" src="${url}"></iframe></div>`);
                if(z.start) z.start({name:txt,url:url});
                if(st) st({name:txt,url:url});
                $(`#iframe${z.tabwinId}`).load(function(){
                    if(z.end) z.end({name:txt,url:url});
                    if(ed) ed({name:txt,url:url});
                });
            }else{
                z.w.append(`<div class="eui-tabwin-main ${z.cs}" eui-tabwin-id="${z.tabwinId}" data-id="${obj.id}" eui-tabwin-title="${txt}" eui-tabwin-url="${url}"></div>`);
                if(z.start) z.start({name:txt,url:url});
                if(st) st({name:txt,url:url});
                z.w.find('.eui-tabwin-main:last').load(url,function(responseTxt,statusTxt,xhr){
                    if(z.end) z.end({name:txt,url:url});
                    if(ed) ed({name:txt,url:url});
                    if(statusTxt=='error' && z.error) z.error($(this));
                });
            };
            z.tabwinId++;
            // 绑定tab切换
            z.l.children('.eui-tabwin-label').off().on('click',function(ev){
                ev.stopPropagation();
                const xs=$(this).index();
                cl();
                z.w.children('.eui-tabwin-main').eq(xs).addClass(z.cs);
                z.l.children('.eui-tabwin-label').eq(xs).addClass(z.cs);
            });
            // 绑定删除
            z.l.find('.eui-icon-close').off().on('click',function(ev){
                ev.stopPropagation();
                close($(this).parent().index());
            });
        }else{
            // 切换页面
            cl();
            z.w.children('.eui-tabwin-main').eq(xx).addClass(z.cs);
            z.l.children('.eui-tabwin-label').eq(xx).addClass(z.cs);
            // 切换后回调事件
            if(z.switch) z.switch();
        };
    };
    // 关闭方法
    const close=index=>{
        if(z.close) z.close(z.list[index]);
        z.list.splice(index,1);
        if(z.l.children('.eui-tabwin-label').eq(index).hasClass(z.cs)){
            z.w.children('.eui-tabwin-main').eq(index).remove();
            z.l.children('.eui-tabwin-label').eq(index).remove();
            if(index==0) index=1;
            cl();
            z.w.children('.eui-tabwin-main').eq(index-1).addClass(z.cs);
            z.l.children('.eui-tabwin-label').eq(index-1).addClass(z.cs);
        }else{
            z.w.children('.eui-tabwin-main').eq(index).remove();
            z.l.children('.eui-tabwin-label').eq(index).remove();
        };
    };
    // 刷新页面
    const update=(url,txt,id,type)=>{
        if(type=='iframe'){
            const ft=$(window.top.document);
            const pt=ft.find('.eui-tabwin-main.'+z.cs);
            const ifm=pt.find('iframe');
            if(txt){
                ft.find('.eui-tabwin-label.active span').text(txt).attr('eui-tabwin-title',txt).attr('eui-tabwin-url',url);
                ft.find('.eui-tabwin-main.active').attr('eui-tabwin-title',txt).attr('eui-tabwin-url',url);
            }
            if(z.start) z.start({name:txt,url:url});
            ifm.attr('src',url);
            ifm.load(function(){
                if(z.end) z.end({name:txt,url:url});
            });
        }else{
            const pt=$('.eui-tabwin-main.'+z.cs);
            pt.empty();
            pt.attr('data-id',id).attr('eui-tabwin-url',url);
            if(z.start) z.start({name:txt,url:url});
            pt.load(url,function(responseTxt,statusTxt,xhr){
                if(z.end) z.end({name:txt,url:url});
                if(statusTxt=='error' && z.error) z.error($(this));
            });
        }
    };
    // 防止重复绑定
    $('[data-tabwin-open],[data-tabwin-update],[data-tabwin-iframe]').off('click');
    // 绑定新增tab和刷新
    $(document).on('click','[data-tabwin-open]',function(){
        if($(this).hasClass(z.disable)) return false;
        const title=$(this).attr('data-tabwin-title');
        load({
            title:title || $(this).text(),
            url:$(this).attr('data-tabwin-open'),
            id:$(this).attr('data-id')
        });
    });
    $(document).on('click','[data-tabwin-iframe]',function(){
        if($(this).hasClass(z.disable)) return false;
        const title=$(this).attr('data-tabwin-title');
        load({
            title:title || $(this).text(),
            url:$(this).attr('data-tabwin-iframe'),
            id:$(this).attr('data-id'),
            type:'iframe'
        });
    });
    $(document).on('click','[data-tabwin-update]',function(){
        var txt=$(this).attr('data-tabwin-title') || $(this).text() || $('.eui-tabwin-label.active').attr('data-tabwin-title');
        if(!$(this).parents('.eui-tabwin-main').length || $('.eui-tabwin-main.active').hasClass('eui-tabwin-iframe')){
            // iframe
            update($(this).data('tabwin-update'),txt,$(this).attr('data-id'),'iframe');
        }else{
            update($(this).data('tabwin-update'),txt,$(this).attr('data-id'));
        }
    });
    // 默认打开页面
    if(z.initial && z.initial.title && z.initial.url) load(z.initial,z.initial.start,z.initial.end);
};

/*
2018-01-12
弹出层
*/
Eui.prototype.layer=dt=>{
    const z={
        // 加载地址
        url:dt.url,
        id:dt.id,
        style:'',
        st:dt.start,
        ed:dt.end,
        error:dt.error,
        type:dt.type || 'ajax',
        tit:dt.title || '详情页',
        dom:'',
        html:dt.html,
        body:dt.body || 'body'
    };
    if(!$('.eui-layer').length) z.dom+='<div class="eui-shade"></div>';
    z.dom+=
    `<div class="eui-layer" data-layer-urlData="${z.url}" id="${z.id}" style="${z.style}">
        <div class="eui-layer-title"><h2>${z.tit}</h2><a class="eui-layer-close eui-icon-close" href="javascript:;"></a></div>
        <div class="eui-layer-main">
            <div class="eui-layer-content"></div>
        </div>
    </div>`;
    const that=$(z.dom);
    $(z.body).append(that);
    setTimeout(()=>$('.eui-shade,.eui-layer').addClass('on'),200);
    if(z.st) z.st();
    // iframe加载
    if(dt.type=='iframe'){
        that.find('.eui-layer-content').html(
            `<iframe src="${z.url}" width="100%" height="99%" frameborder="0"></iframe>`
        );
        if(z.ed) z.ed();
    }else if(dt.type=='html'){
        that.find('.eui-layer-content').html(z.html);
        if(z.ed) z.ed();
    }else{
        that.find('.eui-layer-content').load(z.url,function(responseTxt,statusTxt,xhr){
            if(z.ed) z.ed();
            if(statusTxt=='error' && z.error) z.error($(this));
        });
    };
    that.find('.eui-layer-close').off().click(function(){
        $(this).parents('.eui-layer').removeClass('on');
        if($('.eui-layer.on').length==0) $('.eui-shade').removeClass('on');
        setTimeout(()=>{
            that.remove();
        },200);
    });
};

// 下拉搜索
Eui.prototype.search=dt=>{
    const z={
        el:dt.el,
        url:dt.url,
        type:dt.type,
        conditions:dt.conditions,
        li:'',
        set:null,
        set2:null
    };
    const $n=$(z.el).addClass('eui-pointer eui-search-ipt').attr('readonly',true);
    const leng=$('.eui-search').length;
    const that=$(`<div class="eui-search eui-search-${leng}"><input type="text" class="eui-ipt eui-choice" placeholder="请输入搜索关键词" eui="sm" /><ul class="eui-search-list"></ul></div>`);
    $('body').append(that);
    const list=that.find('.eui-search-list');
    const ipt=that.find('.eui-choice');
    // 后台搜索
    const ajx=()=>{
        const sv=ipt.val();
        if(!sv) return;
        list.children().remove();
        eui.loading({
            el:'.eui-search-'+leng,
            bg:'none',
            state:true
        });
        // 拼接数据
        let pm='{';
        pm+=$(z.el).attr('name')+':"'+sv+'",';
        $(z.conditions).each(function(i){
            const k=$(this).attr('name');
            let v='';
            if($(this).attr('type')=='radio' || $(this).attr('type')=='checkbox'){
                if($(this).prop('checked')){
                    v=$(this).val();
                    pm+=k+':"'+v+'",';
                }
            }else{
                v=$(this).val();
                pm+=k+':"'+v+'",';
            }
        });
        pm+='}';
        pm=eval('('+pm+')');
        $.ajax({
            url:z.url,
            type:z.type || 'POST',
            data:pm,
            success:function(data){
                eui.loading({
                    el:'.eui-search-'+leng,
                    state:false
                });
                list.children().remove();
                if(data.state==0){
                    let li='';
                    data.content.forEach(n=>{
                        li+=`<li data-id="${n.id}">${n.name}</li>`;
                    });
                    li=$(li);
                    list.append(li);
                    li.mousedown(function(){
                        $(z.el).val($(this).text()).attr({'eui-val':$(this).data('id'),'eui-txt':$(this).text()});
                        that.hide(0);
                    });
                }else{
                    list.append('<li class="normal">没有匹配的结果！</li>');
                }
            },
            error:function(){
                eui.loading({
                    el:'.eui-search-'+leng,
                    state:false
                });
                list.children().remove();
                list.append(`<li class="normal">系统繁忙，请稍后再试试！</li>`);
            }
        });
    };

    $n.mousedown(function(){
        clearTimeout(z.set2);
        ipt.val('');
        list.children().remove();
        // 获取输入框位置、宽高
        const es={
            l:$(this).offset().left,
            t:$(this).offset().top,
            w:$(this).outerWidth(),
            h:$(this).outerHeight()
        };
        // 设置搜索框宽高、位置
        that.css({
            'left':es.l,
            'top':es.t+es.h,
            'width':es.w
        });
        // 显示
        that.slideDown(300,function(){
            ipt.focus();
        });
    });
    // 点击外部关闭
    $(document).on('click',(e)=>{
        let el=$(e.target);
        if(!el.hasClass('eui-search-ipt') && el.parents('.eui-search').length==0){
            $('.eui-search').hide(0);
        };
    });
    // 输入
    ipt.keyup(function(){
        clearTimeout(z.set);
        z.set=setTimeout(ajx,300);
    });
};

// 表格排序
Eui.prototype.tablesort=()=>{
    const sort={
        index:0,
        lift:'',
        run(type,lift,index,pt){
            if(!type) return;
            const $trs=pt.find('tbody tr');
            // 提升参数,排序类型中要用到
            sort.index=index;
            sort.lift=lift;
            // 时间排序
            if(type=='time') $trs.sort(sort.time);
            // 字符长度排序
            if(type=='length') $trs.sort(sort.leng);
            // 名称排序
            if(type=='name') $trs.sort(sort.name);
            // 价格排序
            if(type=='price') $trs.sort(sort.price);

            // 重写列表序号
            if(pt.find('th:eq(0)').text()=='序号'){
                $trs.each((i,n)=>{
                    $(n).find('td:eq(0)').text(i+1);
                });
            };
            pt.find('tbody').html($trs.detach());
        },
        // 时间排序
        time(a,b){
            const av=$(a).find('td:eq('+sort.index+')').text();
            const bv=$(b).find('td:eq('+sort.index+')').text();
            if(sort.lift=='up'){
                if(parseInt(Date.parse(av)) < parseInt(Date.parse(bv))){
                    return -1;
                }else{
                    return 1;
                };
            }else{
                if(parseInt(Date.parse(av)) < parseInt(Date.parse(bv))){
                    return 1;
                }else{
                    return -1;
                };
            }
        },
        // 长度排序
        leng(a,b){
            const av=$(a).find('td:eq('+sort.index+')').text();
            const bv=$(b).find('td:eq('+sort.index+')').text();
            if(sort.lift=='up'){
                if(av.length < bv.length || av==bv){
                    return -1;
                }else{
                    return 1;
                };
            }else{
                if(av.length < bv.length || av==bv){
                    return 1;
                }else{
                    return -1;
                };
            }
        },
        // 名称排序
        name(a,b){
            const av=$(a).find('td:eq('+sort.index+')').text();
            const bv=$(b).find('td:eq('+sort.index+')').text();
            if(sort.lift=='up'){
                if(av < bv || av==bv){
                    return -1;
                }else{
                    return 1;
                };
            }else{
                if(av < bv || av==bv){
                    return 1;
                }else{
                    return -1;
                };
            }
        },
        // 价格排序
        price(a,b){
            const av=Number($(a).find('td:eq('+sort.index+')').find('.price').length ? $(a).find('td:eq('+sort.index+')').find('.price').text() : $(a).find('td:eq('+sort.index+')').text());
            const bv=Number($(b).find('td:eq('+sort.index+')').find('.price').length ? $(b).find('td:eq('+sort.index+')').find('.price').text() : $(b).find('td:eq('+sort.index+')').text());
            if(sort.lift=='up'){
                if(av < bv || av==bv){
                    return -1;
                }else{
                    return 1;
                };
            }else{
                if(av < bv || av==bv){
                    return 1;
                }else{
                    return -1;
                };
            }
        }
    }
    $('.eui-table').each((i,n)=>{
        const $n=$(n);
        const $th=$n.find('[eui-sort]');
        // 给每个th[eui-sort]添加升降序按钮,并绑定事件
        $th.each((ii,nn)=>{
            const $nn=$(nn);
            if($nn.find($('.eui-sort-btn')).length) return;
            const btn=$('<a class="eui-sort-btn eui-icon-unfold" href="javascript:;">');
            $nn.append(btn);
            const type=$nn.attr('eui-sort');
            const index=$nn.index();
            btn.click(function(){
                if($(this).hasClass('eui-icon-unfold')){
                    $(this).removeClass('eui-icon-unfold');
                    $(this).addClass('eui-icon-packup');
                    // 升序
                    sort.run(type,'down',index,$n);
                }else{
                    $(this).removeClass('eui-icon-packup');
                    $(this).addClass('eui-icon-unfold');
                    // 降序
                    sort.run(type,'up',index,$n);
                };
            });
        });
    });
};
// 实例化Eui
const eui=new Eui();