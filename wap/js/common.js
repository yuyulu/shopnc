function getQueryString(name){
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
	var r = window.location.search.substr(1).match(reg);
	if (r!=null) return r[2]; return '';
}

function addCookie(name,value,expireHours){
	var cookieString=name+"="+escape(value)+"; path=/";
	//判断是否设置过期时间
	if(expireHours>0){
		var date=new Date();
		date.setTime(date.getTime()+expireHours*3600*1000);
		cookieString=cookieString+";expires="+date.toGMTString();
	}
	document.cookie=cookieString;
}

function getCookie(name){
	var strcookie=document.cookie;
	var arrcookie=strcookie.split("; ");
	for(var i=0;i<arrcookie.length;i++){
	var arr=arrcookie[i].split("=");
	if(arr[0]==name)return unescape(arr[1]);
	}
	return null;
}

function delCookie(name){//删除cookie
	var exp = new Date();
	exp.setTime(exp.getTime() - 1);
	var cval=getCookie(name);
	if(cval!=null) document.cookie= name + "="+cval+"; path=/;expires="+exp.toGMTString();
}

function checkLogin(state){
	if(state == 0){
		location.href = WapSiteUrl+'/tmpl/member/login.html';
		return false;
	}else {
		return true;
	}
}

function contains(arr, str) {
    var i = arr.length;
    while (i--) {
           if (arr[i] === str) {
           return true;
           }
    }
    return false;
}

function buildUrl(type, data) {
    switch (type) {
        case 'keyword':
            return WapSiteUrl + '/tmpl/product_list.html?keyword=' + encodeURIComponent(data);
        case 'special':
            return WapSiteUrl + '/special.html?special_id=' + data;
        case 'goods':
            return WapSiteUrl + '/tmpl/product_detail.html?goods_id=' + data;
        case 'url':
            return data;
    }
    return WapSiteUrl;
}

function errorTipsShow(html) {
    $(".error-tips").html(html).show();
    setTimeout(function(){
        errorTipsHide();
    }, 3000);
}

function errorTipsHide() {
    $(".error-tips").html("").hide();
}

function writeClear(o) {
    if (o.val().length > 0) {
        o.parent().addClass('write');
    } else {
        o.parent().removeClass('write');
    }
    btnCheck(o.parents('form'));
}

function btnCheck(form) {
    var btn = true;
    form.find('input').each(function(){
        if ($(this).hasClass('no-follow')) {
            return;
        }
        if ($(this).val().length == 0) {
            btn = false;
        }
    });
    if (btn) {
        form.find('.btn').parent().addClass('ok');
    } else {
        form.find('.btn').parent().removeClass('ok');
    }
}

/**
 * 取得默认系统搜索关键词
 * @param cmd
 */
function getSearchName() {
	var keyword = decodeURIComponent(getQueryString('keyword'));
	if (keyword == '') {
	    if(getCookie('deft_key_value') == null) {
	        $.getJSON(ApiUrl + '/index.php?act=index&op=search_hot_info', function(result) {
	        	var data = result.datas.hot_info;
	        	if(typeof data.name != 'undefined') {
	            	$('#keyword').attr('placeholder',data.name);
	            	$('#keyword').html(data.name);
	            	addCookie('deft_key_name',data.name,1);
	            	addCookie('deft_key_value',data.value,1);
	        	} else {
	            	addCookie('deft_key_name','',1);
	            	addCookie('deft_key_value','',1);
	        	}
	        })
	    } else {
	    	$('#keyword').attr('placeholder',getCookie('deft_key_name'));
	    	$('#keyword').html(getCookie('deft_key_name'));
	    }		
	}
}
// 免费领代金券
function getFreeVoucher(tid) {
    var key = getCookie('key');
    if (!key) { checkLogin(0); return; }
    $.ajax({
        type:'post',
        url:ApiUrl+"/index.php?act=member_voucher&op=voucher_freeex",
        data:{tid:tid,key:key},
        dataType:'json',
        success:function(result){
            checkLogin(result.login);
            var msg = '领取成功';
            var skin = 'green';
            if(result.datas.error){
                msg = '领取失败：' + result.datas.error;
                skin = 'red';
            }
            $.sDialog({
                skin:skin,
                content: msg,
                okBtn:false,
                cancelBtn:false
            });
        }
    });
}

// 登陆后更新购物车
function updateCookieCart(key) {
    var cartlist = decodeURIComponent(getCookie('goods_cart'));
    if (cartlist) {
        $.ajax({
            type:'post',
            url:ApiUrl+'/index.php?act=member_cart&op=cart_batchadd',
            data:{key:key, cartlist:cartlist},
            dataType:'json',
            async:false
        });
        delCookie('goods_cart');
    }
}
/**
 * 查询购物车中商品数量
 * @param key
 * @param expireHours
 */
function getCartCount(key, expireHours) {
    var cart_count = 0;
    if (getCookie('key') !== null && getCookie('cart_count') === null) {
        var key = getCookie('key');
        $.ajax({
            type:'post',
            url:ApiUrl+'/index.php?act=member_cart&op=cart_count',
            data:{key:key},
            dataType:'json',
            async:false,
            success:function (result) {
                if (typeof(result.datas.cart_count) != 'undefined') {
                    addCookie('cart_count',result.datas.cart_count,expireHours);
                    cart_count = result.datas.cart_count;
                }
            }
        });
    } else {
        cart_count = getCookie('cart_count');
    }
    if (cart_count > 0 && $('.nctouch-nav-menu').has('.cart').length > 0) {
        $('.nctouch-nav-menu').has('.cart').find('.cart').parents('li').find('sup').show();
        $('#header-nav').find('sup').show(); 
    }
}
/**
 * 查询是否有新消息
 */
function getChatCount() {
    if ($('#header').find('.message').length > 0) {
        var key = getCookie('key');
        if (key !== null) {
            $.getJSON(ApiUrl+'/index.php?act=member_chat&op=get_msg_count', {key:key}, function(result){
                if (result.datas > 0) {
                    $('#header').find('.message').parent().find('sup').show();
                    $('#header-nav').find('sup').show();
                }
            });
        }
        $('#header').find('.message').parent().click(function(){
            window.location.href = WapSiteUrl+'/tmpl/member/chat_list.html';
        });
    }
}

$(function() {
    $('.input-del').click(function(){
        $(this).parent().removeClass('write').find('input').val('');
        btnCheck($(this).parents('form'));
    });
	
	// radio样式
	$('body').on('click', 'label', function(){
	    if ($(this).has('input[type="radio"]').length > 0) {
	        $(this).addClass('checked').siblings().removeAttr('class').find('input[type="radio"]').removeAttr('checked');
	    } else if ($(this).has('[type="checkbox"]')) {
	        if ($(this).find('input[type="checkbox"]').prop('checked')) {
	            $(this).addClass('checked');
	        } else {
	            $(this).removeClass('checked');
	        }
	    }
  	});
    // 滚动条通用js
    if ($('body').hasClass('scroller-body')) {
        new IScroll('.scroller-body', { mouseWheel: true, click: true });
    }
    
    // 右上侧小导航控件
    $('#header').on('click', '#header-nav', function(){
        if ($('.nctouch-nav-layout').hasClass('show')) {
            $('.nctouch-nav-layout').removeClass('show');
        } else {
            $('.nctouch-nav-layout').addClass('show');
        }
    });
    $('#header').on('click', '.nctouch-nav-layout',function(){
        $('.nctouch-nav-layout').removeClass('show');
    });
    $(document).scroll(function(){
        $('.nctouch-nav-layout').removeClass('show');
    });
    getSearchName();
    getCartCount();
    getChatCount();// 导航右侧消息
    

    //回到顶部
    $(document).scroll(function(){
        set();
    });
    $('.fix-block-r,footer').on('click', ".gotop",function (){
        btn = $(this)[0];
        this.timer=setInterval(function(){
            $(window).scrollTop(Math.floor($(window).scrollTop()*0.8));
            if($(window).scrollTop()==0) clearInterval(btn.timer,set);
        },10);
    });
    function set(){$(window).scrollTop()==0 ? $('#goTopBtn').addClass('hide') : $('#goTopBtn').removeClass('hide');}
});
(function($) {
    $.extend($, {
        /**
         * 滚动header固定到顶部
         */
        scrollTransparent: function(options) {
            var defaults = {
                    valve : '#header',          // 动作触发
                    scrollHeight : 50
            }
            var options = $.extend({}, defaults, options);
            function _init() {
                $(window).scroll(function(){
                    if ($(window).scrollTop() <= options.scrollHeight) {
                        $(options.valve).addClass('transparent').removeClass('posf');
                    } else {
                        $(options.valve).addClass('posf').removeClass('transparent');
                    }
                });
                
            }
            return this.each(function() {
                _init();
            })();
        },

    /**
     * 选择地区
     * 
     * @param $
     */
        areaSelected: function(options) {
            var defaults = {
                    success : function(data){}
                }
            var options = $.extend({}, defaults, options);
            var ASID = 0;
            var ASID_1 = 0;
            var ASID_2 = 0;
            var ASID_3 = 0;
            var ASNAME = '';
            var ASINFO = '';
            var ASDEEP = 1;
            var ASINIT = true;
            function _init() {
                if ($('#areaSelected').length > 0) {
                    $('#areaSelected').remove();
                }
                var html = '<div id="areaSelected">'
                    + '<div class="nctouch-full-mask left">'
                    + '<div class="nctouch-full-mask-bg"></div>'
                    + '<div class="nctouch-full-mask-block">'
                    + '<div class="header">'
                    + '<div class="header-wrap">'
                    + '<div class="header-l"><a href="javascript:void(0);"><i class="back"></i></a></div>'
                    + '<div class="header-title">'
                    + '<h1>选择地区</h1>'
                    + '</div>'
                    + '<div class="header-r"><a href="javascript:void(0);"><i class="close"></i></a></div>'
                    + '</div>'
                    + '</div>'
                    + '<div class="nctouch-main-layout">'
                    + '<div class="nctouch-single-nav">'
                    + '<ul id="filtrate_ul" class="area">'
                    + '<li class="selected"><a href="javascript:void(0);">一级地区</a></li>'
                    + '<li><a href="javascript:void(0);" >二级地区</a></li>'
                    + '<li><a href="javascript:void(0);" >三级地区</a></li>'
                    + '</ul>'
                    + '</div>'
                    + '<div class="nctouch-main-layout-a"><ul class="nctouch-default-list"></ul></div>'
                    + '</div>'
                    + '</div>'
                    + '</div>'
                    + '</div>';
                $('body').append(html);
                _getAreaList();
                _bindEvent();
                _close();
            }

            function _getAreaList() {
                $.ajax({//获取区域列表
                    type:'get',
                    url:ApiUrl+'/index.php?act=area&op=area_list',
                    data:{area_id:ASID},
                    dataType:'json',
                    async:false,
                    success:function(result){
                        if (result.datas.area_list.length == 0) {
                            _finish();
                            return false;
                        }
                        if (ASINIT) {
                            ASINIT = false
                        } else {
                            ASDEEP++;
                        }
                        $('#areaSelected').find('#filtrate_ul').find('li').eq(ASDEEP-1).addClass('selected').siblings().removeClass('selected');
                        checkLogin(result.login);
                        var data = result.datas;
                        var area_li = '';
                        for(var i=0;i<data.area_list.length;i++){
                            area_li += '<li><a href="javascript:void(0);" data-id="' + data.area_list[i].area_id + '" data-name="' + data.area_list[i].area_name + '"><h4>' + data.area_list[i].area_name + '</h4><span class="arrow-r"></span> </a></li>';
                        }
                        $('#areaSelected').find(".nctouch-default-list").html(area_li);
                        if (typeof(myScrollArea) == 'undefined') {
                            if (typeof(IScroll) == 'undefined') {
                                $.ajax({
                                    url: WapSiteUrl+'/js/iscroll.js',
                                    dataType: "script",
                                    async: false
                                  });
                            }
                            myScrollArea = new IScroll('#areaSelected .nctouch-main-layout-a', { mouseWheel: true, click: true });
                        } else {
                            myScrollArea.refresh();
                        }
                    }
                });
                return false;
            }
            
            function _bindEvent() {
                $('#areaSelected').find('.nctouch-default-list').off('click', 'li > a');
                $('#areaSelected').find('.nctouch-default-list').on('click', 'li > a', function(){
                    ASID = $(this).attr('data-id');
                    eval("ASID_"+ASDEEP+"=$(this).attr('data-id')");
                    ASNAME = $(this).attr('data-name');
                    ASINFO += ASNAME + ' ';
                    var _li = $('#areaSelected').find('#filtrate_ul').find('li').eq(ASDEEP);
                    _li.prev().find('a').attr({'data-id':ASID, 'data-name':ASNAME}).html(ASNAME);
                    if (ASDEEP == 3) {
                        _finish();
                        return false;
                    }
                    _getAreaList();
                });
                $('#areaSelected').find('#filtrate_ul').off('click', 'li > a');
                $('#areaSelected').find('#filtrate_ul').on('click', 'li > a', function(){
                    if ($(this).parent().index() >= $('#areaSelected').find('#filtrate_ul').find('.selected').index()) {
                        return false;
                    }
                    ASID = $(this).parent().prev().find('a').attr('data-id');
                    ASNAME = $(this).parent().prev().find('a').attr('data-name');
                    ASDEEP = $(this).parent().index();
                    ASINFO = '';
                    for (var i=0; i<$('#areaSelected').find('#filtrate_ul').find('a').length; i++) {
                        if (i < ASDEEP) {
                            ASINFO += $('#areaSelected').find('#filtrate_ul').find('a').eq(i).attr('data-name') + ' ';
                        } else {
                            var text = '';
                            switch (i) {
                            case 0:
                                text = '一级地区'
                                break;
                            case 1:
                                text = '二级地区'
                                break;
                            case 2:
                                text = '三级地区';
                                break;
                            }
                            $('#areaSelected').find('#filtrate_ul').find('a').eq(i).html(text);
                        }
                    }
                    _getAreaList();
                });
            }
            
            function _finish() {
                var data = {area_id:ASID,area_id_1:ASID_1,area_id_2:ASID_2,area_id_3:ASID_3,area_name:ASNAME,area_info:ASINFO};
                options.success.call('success', data);
                if (!ASINIT) {
                    $('#areaSelected').find('.nctouch-full-mask').addClass('right').removeClass('left');
                }
                return false;
            }
            
            function _close() {
                $('#areaSelected').find('.header-l').off('click', 'a');
                $('#areaSelected').find('.header-l').on('click', 'a',function(){
                    $('#areaSelected').find('.nctouch-full-mask').addClass('right').removeClass('left');
                });
                return false;
            }
            
            return this.each(function() {
                return _init();
            })();
        },
        


        /**
         * 从右到左动态显示隐藏内容
         * 
         */
        animationLeft: function(options) {
            var defaults = {
                    valve : '.animation-left',          // 动作触发
                    wrapper : '.nctouch-full-mask',    // 动作块
                    scroll : ''     // 滚动块，为空不触发滚动
            }
            var options = $.extend({}, defaults, options);
            function _init() {
                $(options.valve).click(function(){
                    $(options.wrapper).removeClass('hide').removeClass('right').addClass('left');

                    if (options.scroll != '') {
                        if (typeof(myScrollAnimationLeft) == 'undefined') {
                            if (typeof(IScroll) == 'undefined') {
                                $.ajax({
                                    url: WapSiteUrl+'/js/iscroll.js',
                                    dataType: "script",
                                    async: false
                                });
                            }
                            myScrollAnimationLeft = new IScroll(options.scroll, { mouseWheel: true, click: true });
                        } else {
                            myScrollAnimationLeft.refresh();
                        }
                    }
                });
                $(options.wrapper).on('click', '.header-l > a', function(){
                    $(options.wrapper).addClass('right').removeClass('left');
                });
                
            }
            return this.each(function() {
                _init();
            })();
        },

        /**
         * 从下到上动态显示隐藏内容
         * 
         */
        animationUp: function(options) {
            var defaults = {
                    valve : '.animation-up',          // 动作触发，为空直接触发
                    wrapper : '.nctouch-bottom-mask',    // 动作块
                    scroll : '.nctouch-bottom-mask-rolling',     // 滚动块，为空不触发滚动
                    start : function(){},       // 开始动作触发事件
                    close : function(){}        // 关闭动作触发事件
            }
            var options = $.extend({}, defaults, options);
            function _animationUpRun() {
                options.start.call('start');
                $(options.wrapper).removeClass('down').addClass('up');

                if (options.scroll != '') {
                    if (typeof(myScrollAnimationUp) == 'undefined') {
                        if (typeof(IScroll) == 'undefined') {
                            $.ajax({
                                url: WapSiteUrl+'/js/iscroll.js',
                                dataType: "script",
                                async: false
                              });
                        }
                        myScrollAnimationUp = new IScroll(options.scroll, { mouseWheel: true, click: true });
                    } else {
                        myScrollAnimationUp.refresh();
                    }
                }
            }
            return this.each(function() {
                if (options.valve != '') {
                    $(options.valve).on('click', function(){
                        _animationUpRun();
                    });
                } else {
                    _animationUpRun();
                }
                $(options.wrapper).on('click', '.nctouch-bottom-mask-bg,.nctouch-bottom-mask-close', function(){
                    $(options.wrapper).addClass('down').removeClass('up');
                    options.close.call('close');
                });
            })();
        }
    });
})(Zepto);

/**
 * 异步上传图片
 */
$.fn.ajaxUploadImage = function(options) {
    var defaults = {
        url : '',
        data : {},
        start : function(){},     // 开始上传触发事件
        success : function(){}
    }
    var options = $.extend({}, defaults, options);
    var _uploadFile;
    function _checkFile() {
          //文件为空判断
          if (_uploadFile === null || _uploadFile === undefined) {
              alert("请选择您要上传的文件！");
              return false;
          }
//           
//          //检测文件类型
//          if(_uploadFile.type.indexOf('image') === -1) {
//              alert("请选择图片文件！");
//              return false;
//          }
//           
//          //计算文件大小
//          var size = Math.floor(_uploadFile.size/1024);
//          if (size > 5000) {
//              alert("上传文件不得超过5M!");
//              return false;
//          };
          return true;
    };
    return this.each(function() {
        $(this).on('change', function(){
            var _element = $(this);
            options.start.call('start', _element);
            _uploadFile = _element.prop('files')[0];
            if (!_checkFile) return false; 
            try {
                //执行上传操作
                var xhr = new XMLHttpRequest();
                xhr.open("post",options.url, true);
                xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {
                        returnDate = $.parseJSON(xhr.responseText);
                        options.success.call('success', _element, returnDate);
                    };
                };
                //表单数据
                var fd = new FormData();
                for (k in options.data) {
                    fd.append(k, options.data[k]);
                }
                fd.append(_element.attr('name'), _uploadFile);
                //执行发送
                result = xhr.send(fd);
            } catch (e) {
                console.log(e);
                alert(e);
            }
        });
    });
}

function loadSeccode(){
    $("#codekey").val('');
    //加载验证码
    $.ajax({
        type:'get',
        url:ApiUrl+"/index.php?act=seccode&op=makecodekey",
        async : false,
        dataType: 'json',
        success:function(result){
            $("#codekey").val(result.datas.codekey);
        }
    });
    $("#codeimage").attr('src',ApiUrl+'/index.php?act=seccode&op=makecode&k='+$("#codekey").val()+'&t=' + Math.random());
}
/**
 * 收藏店铺
 */
function favoriteStore(store_id){
    var key = getCookie('key');
    if (!key) {
        checkLogin(0);
        return;
    }
    if (store_id <= 0) {
        $.sDialog({skin: "green", content: '参数错误', okBtn: false, cancelBtn: false});
        return false;
    }
    var return_val = false;
    $.ajax({
        type: 'post',
        url: ApiUrl + '/index.php?act=member_favorites_store&op=favorites_add',
        data: {key: key, store_id: store_id},
        dataType: 'json',
        async: false,
        success: function(result) {
            if (result.code == 200) {
                // $.sDialog({skin: "green", content: "收藏成功！", okBtn: false, cancelBtn: false});
                return_val = true;
            } else {
                $.sDialog({skin: "red", content: result.datas.error, okBtn: false, cancelBtn: false});
            }
        }
    });
    return return_val;
}
/**
 * 取消收藏店铺
 */
function dropFavoriteStore(store_id){
    var key = getCookie('key');
    if (!key) {
        checkLogin(0);
        return;
    }
    if (store_id <= 0) {
        $.sDialog({skin: "green", content: '参数错误', okBtn: false, cancelBtn: false});
        return false;
    }
    var return_val = false;
    $.ajax({
        type: 'post',
        url: ApiUrl + '/index.php?act=member_favorites_store&op=favorites_del',
        data: {key: key, store_id: store_id},
        dataType: 'json',
        async: false,
        success: function(result) {
            if (result.code == 200) {
                // $.sDialog({skin: "green", content: "已取消收藏！", okBtn: false, cancelBtn: false});
                return_val = true;
            } else {
                $.sDialog({skin: "red", content: result.datas.error, okBtn: false, cancelBtn: false});
            }
        }
    });
    return return_val;
}
/**
 * 收藏商品
 */
function favoriteGoods(goods_id){
    var key = getCookie('key');
    if (!key) {
        checkLogin(0);
        return;
    }
    if (goods_id <= 0) {
        $.sDialog({skin: "green", content: '参数错误', okBtn: false, cancelBtn: false});
        return false;
    }
    var return_val = false;
    $.ajax({
        type: 'post',
        url: ApiUrl + '/index.php?act=member_favorites&op=favorites_add',
        data:{key:key,goods_id:goods_id},
        dataType: 'json',
        async: false,
        success: function(result) {
            if (result.code == 200) {
                // $.sDialog({skin: "green", content: "收藏成功！", okBtn: false, cancelBtn: false});
                return_val = true;
            } else {
                $.sDialog({skin: "red", content: result.datas.error, okBtn: false, cancelBtn: false});
            }
        }
    });
    return return_val;
}
/**
 * 取消收藏商品
 */
function dropFavoriteGoods(goods_id){
    var key = getCookie('key');
    if (!key) { checkLogin(0); return; }
    if (goods_id <= 0) {
        $.sDialog({skin: "green", content: '参数错误', okBtn: false, cancelBtn: false}); return false;
    }
    var return_val = false;
    $.ajax({
        type: 'post',
        url: ApiUrl + '/index.php?act=member_favorites&op=favorites_del',
        data: {key: key, fav_id: goods_id},
        dataType: 'json',
        async: false,
        success: function(result) {
            if (result.code == 200) {
                // $.sDialog({skin: "green", content: "已取消收藏！", okBtn: false, cancelBtn: false});
                return_val = true;
            } else {
                $.sDialog({skin: "red", content: result.datas.error, okBtn: false, cancelBtn: false});
            }
        }
    });
    return return_val;
}
/**
 * 动态加载css文件
 * @param css_filename css文件路径
 */
function loadCss(css_filename) {
    var link = document.createElement('link');
    link.setAttribute('type', 'text/css');
    link.setAttribute('href', css_filename);
    link.setAttribute('href', css_filename);
    link.setAttribute('rel', 'stylesheet');
    css_id = document.getElementById('auto_css_id');
    if (css_id) {
        document.getElementsByTagName('head')[0].removeChild(css_id);
    }
    document.getElementsByTagName('head')[0].appendChild(link);
}
/**
 * 动态加载js文件
 * @param script_filename js文件路径
 */
function loadJs(script_filename) {
    var script = document.createElement('script');
    script.setAttribute('type', 'text/javascript');
    script.setAttribute('src', script_filename);
    script.setAttribute('id', 'auto_script_id');
    script_id = document.getElementById('auto_script_id');
    if (script_id) {
        document.getElementsByTagName('head')[0].removeChild(script_id);
    }
    document.getElementsByTagName('head')[0].appendChild(script);
}