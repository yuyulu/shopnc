var store_id = getQueryString('store_id');
var keyword = decodeURIComponent(getQueryString('keyword'));
var order_key = getQueryString('key');
var order_val = getQueryString('order');
var price_from = getQueryString('price_from');
var price_to = getQueryString('price_to');
var stc_id = getQueryString('stc_id');
var prom_type = getQueryString('prom_type');
var load_class_storegoodslist = new ncScrollLoad();
var isload_goods = false;

$(function() {
    // 商品展示形式
    $('#show_style').click(function(){
        var product_content_obj = $('[nc_type="product_content"]');
        if ($(product_content_obj).hasClass('grid')) {
            $(this).find('span').removeClass('browse-grid').addClass('browse-list');
            $(product_content_obj).removeClass('grid').addClass('list');
        } else {
            $(this).find('span').addClass('browse-grid').removeClass('browse-list');
            $(product_content_obj).addClass('grid').removeClass('list');
        }
    });

    // 综合排序显示隐藏
    $('#sort_default').click(function(){
        if ($('#sort_inner').hasClass('hide')) {
            $('#sort_inner').removeClass('hide');
        } else {
            $('#sort_inner').addClass('hide');
        }
    });
    $('#sort_inner').find('a').click(function(){
        $('#sort_inner').addClass('hide').find('a').removeClass('cur');
        var text = $(this).addClass('cur').text();
        $("#sort_default").addClass('current').html(text + '<i></i>');
        $('#sort_salesnum').removeClass('current');
    });

    // 销量优先排序
    $('#sort_salesnum').click(function(){
        order_val = 2;
        order_key = 3;
        $(this).addClass('current');
        $("#sort_default").removeClass('current');
        $('#sort_inner').addClass('hide').find('a').removeClass('cur');
        get_list();
    });

    /*$('#nav_ul').find('a').click(function(){
        $(this).addClass('current').parent().siblings().find('a').removeClass('current');
        if (!$('#sort_inner').hasClass('hide') && $(this).parent().index() > 0) {
            $('#sort_inner').addClass('hide');
        }
    });*/

    $('#product_list').on('click', '[nc_type="goods_more_link"]',function(){
        var goods_id = $(this).attr('param_id');
        if (goods_id <= 0) { $.sDialog({skin: "green", content: '参数错误', okBtn: false, cancelBtn: false}); return false; }
        var key = getCookie('key');
        if (!key) {//未登录直接显示
            $("#goods_more_"+goods_id).show();
        }
        //查询是否已收藏
        var obj = $(this);
        //防止多次点击
        if ($(obj).hasClass('goods_more_loading')) { return; }
        $(obj).addClass('goods_more_loading');
        //如果已经加载不再重复查询
        if($("#goods_more_"+goods_id).hasClass('goods_more_has')){
            $("#goods_more_"+goods_id).show();
            $(obj).removeClass('goods_more_loading');
            return;
        }
        $.ajax({
            type: 'post',
            url: ApiUrl + "/index.php?act=member_favorites&op=favorites_info",
            data: {key:key, fav_id:goods_id},
            dataType: 'json',
            success: function(result) {
                if (result.datas.favorites_info) {
                    $("#goods_more_"+goods_id+" [nc_type='goods_cancelfav']").show();
                    $("#goods_more_"+goods_id+" [nc_type='goods_addfav']").hide();
                }else{
                    $("#goods_more_"+goods_id+" [nc_type='goods_cancelfav']").hide();
                    $("#goods_more_"+goods_id+" [nc_type='goods_addfav']").show();
                }
                $("#goods_more_"+goods_id).addClass('goods_more_has');
                $("#goods_more_"+goods_id).show();
                $(obj).removeClass('goods_more_loading');
            }
        });
    }).on('click', '[nc_type="goods_more_con"]', function(){
        var goods_id = $(this).attr('param_id');
        $("#goods_more_"+goods_id).hide();
    }).on('click', '[nc_type="goods_addfav"]',function(){
        var goods_id = $(this).attr('param_id');
        favoriteGoods(goods_id);
        $(this).hide();
        $("#goods_more_"+goods_id+" [nc_type='goods_cancelfav']").show();
    }).on('click', '[nc_type="goods_cancelfav"]',function(){
        var goods_id = $(this).attr('param_id');
        dropFavoriteGoods(goods_id);
        $(this).hide();
        $("#goods_more_"+goods_id+" [nc_type='goods_addfav']").show();
    });

    $.animationLeft({
        valve : '#search_adv',
        wrapper : '.nctouch-full-mask'
    });

    $('#search_submit').click(function(){
        var is_choose = false;
        if ($('#price_from').val() != '') {
            price_from = $('#price_from').val();
            is_choose = true;
        }else{
            price_from = '';
        }
        if ($('#price_to').val() != '') {
            price_to = $('#price_to').val();
            is_choose = true;
        }else{
            price_to = '';
        }
        if (is_choose) {
            $("#search_adv").addClass('current');
            get_list();
        }else{
            $("#search_adv").removeClass('current');
        }
        //隐藏筛选页面
        $(".nctouch-full-mask").addClass('hide').removeClass('left');
    });

    $('input[nctype="price"]').on('blur',function(){
        if ($(this).val() != '' && ! /^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test($(this).val())) {
            $(this).val('');
        }
    });
    $('#reset').click(function(){
        $('input[nctype="price"]').val('');
    });

    //加载列表
    get_list();
});

//渲染list
function get_list(change_param, is_init){
    if (isload_goods) {
        $.sDialog({skin: "green", content: "搜索过于频繁，请稍后再进行搜索", okBtn: false, cancelBtn: false});
        return false;
    }
    isload_goods = true;
    if (!is_init) {//初始化页面
        is_init = false;
    }
    if (is_init==false && change_param) {
        if (change_param.keyword) {
            keyword = change_param.keyword;
        }
        if (change_param.price_from) {
            price_from = change_param.price_from;
        }
        if (change_param.price_to) {
            price_to = change_param.price_to;
        }
        if (change_param.stc_id) {
            stc_id = change_param.stc_id;
        }
        if (change_param.prom_type) {
            prom_type = change_param.prom_type;
        }
    }
    if (change_param) {
        if (change_param.order_key) {
            order_key = change_param.order_key;
        }
        if (change_param.order_val) {
            order_val = change_param.order_val;
        }
    }
    param = {};
    param.store_id = store_id;
    if (is_init==false) {
        if (keyword) {
            param.keyword = keyword;
        }
        if (price_from) {
            param.price_from = price_from;
        }
        if (price_to) {
            param.price_to = price_to;
        }
        if (stc_id) {
            param.stc_id = stc_id;
        }
        if (prom_type) {
            param.prom_type = prom_type;
        }
    } else {
        price_from = '';
        price_to = '';
    }
    if (order_key) {
        param.key = order_key;
    }
    if (order_val) {
        param.order = order_val;
    }
    load_class_storegoodslist.loadInit({
        'url':ApiUrl + '/index.php?act=store&op=store_goods',
        'getparam':param,
        'tmplid':'goods_list_tpl',
        'containerobj':$("#product_list"),
        data : {key:order_key, order:order_val},
        'iIntervalId':true,
        callback:function(){
            isload_goods = false;
        }
    });
}