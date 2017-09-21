//处理商品上新返回的数据
function tidyStoreNewGoodsData(goodsData){
    if (goodsData.goods_list.length <= 0) {
        return goodsData;
    }
    var obj = $('#newgoods').find('[addtimetext="'+goodsData.goods_list[0].goods_addtime_text+'"]');
    var curr_date = '';
    $.each(goodsData.goods_list,function(index,item){
        if (curr_date != item.goods_addtime_text && obj.html() == null) {
            goodsData.goods_list[index].goods_addtime_text_show = item.goods_addtime_text;
            curr_date = item.goods_addtime_text;
        }
    });
    return goodsData;
}

$(function() {
    var key = getCookie('key');
    var store_id = getQueryString("store_id");
    if(!store_id){
        window.location.href = WapSiteUrl+'/index.html';
    }
    $("#goods_search").attr('href','store_search.html?store_id='+store_id);
    $("#store_categroy").attr('href','store_search.html?store_id='+store_id);
    $("#store_intro").attr('href','store_intro.html?store_id='+store_id);

    //显示轮播
    function  slidersShow(){
        $('#store_sliders').each(function() {
            if ($(this).find('.item').length < 2) {
                return;
            }
            Swipe(this, {
                startSlide: 2,
                speed: 400,
                auto: 3000,
                continuous: true,
                disableScroll: false,
                stopPropagation: false,
                callback: function(index, elem) {},
                transitionEnd: function(index, elem) {}
            });
        });
    }

    //加载店铺详情
    $.ajax({
        type: 'post',
        url: ApiUrl + "/index.php?act=store&op=store_info",
        data: {key: key, store_id: store_id},
        dataType: 'json',
        success: function(result) {
            var data = result.datas;
            //显示页面title
            var title = data.store_info.store_name + ' - 店铺首页';
            document.title = title;
            //店铺banner
            var html = template.render('store_banner_tpl', data);
            $("#store_banner").html(html);
            //显示收藏按钮
            if (data.store_info.is_favorate) {
                $("#store_notcollect").hide();
                $("#store_collected").show();
            }else{
                $("#store_notcollect").show();
                $("#store_collected").hide();
            }
            //banner 背景图
            if (data.store_info.mb_title_img) {
                $('.store-top-bg .img').css('background-image', 'url('+data.store_info.mb_title_img+')');
            }else{//输出随机的背景图
                var topBgs = [];
                topBgs[0] = WapSiteUrl + "/images/store_h_bg_01.jpg";
                topBgs[1] = WapSiteUrl + "/images/store_h_bg_02.jpg";
                topBgs[2] = WapSiteUrl + "/images/store_h_bg_03.jpg";
                topBgs[3] = WapSiteUrl + "/images/store_h_bg_04.jpg";
                topBgs[4] = WapSiteUrl + "/images/store_h_bg_05.jpg";
                var randomBgIndex = Math.round( Math.random() * 4 );
                $('.store-top-bg .img').css('background-image', 'url('+ topBgs[randomBgIndex] +')');
            }
            //店铺轮播图
            if (data.store_info.mb_sliders.length > 0) {
                var html = template.render('store_sliders_tpl', data);
                $("#store_sliders").html(html);
                slidersShow();
            }else{
                $("#store_sliders").parent().hide();
            }
            //联系客服
            $('#store_kefu').click(function(){
                window.location.href = WapSiteUrl+'/tmpl/member/chat_info.html?t_id=' + result.datas.store_info.member_id;
            });
            //店主推荐
            var html = template.render('goods_recommend_tpl', data);
            $("#goods_recommend").html(html);
        }
    });

    //加载商品排行
    $('#goods_rank_tab').find('a').click(function(){
        $('#goods_rank_tab').find('li').removeClass('selected');
        $(this).parent().addClass('selected').siblings().removeClass("selected");

        var data_type = $(this).attr('data-type');
        var ordertype = data_type+'desc';
        var shownum = 3;

        $("[nc_type='goodsranklist']").hide();
        $("#goodsrank_"+data_type).show();

        //如果已加载过数据则不重复加载
        if ($("#goodsrank_"+data_type).html()) {
            return;
        }

        //加载商品列表
        $.ajax({
            type: 'post',
            url: ApiUrl + '/index.php?act=store&op=store_goods_rank',
            data: {store_id: store_id, ordertype:ordertype, num:shownum},
            dataType: 'json',
            success: function(result) {
                if (result.code == 200) {
                    var html = template.render('goodsrank_'+data_type+'_tpl', result.datas);
                    $("#goodsrank_"+data_type).html(html);
                }
            }
        });
    });
    $('#goods_rank_tab').find("a[data-type='collect']").trigger('click');

    $('#nav_tab').waypoint(function() {
        $("#nav_tab_con").toggleClass('fixed');
    }, {
        offset: '50'
    });

    //加载商品上新
    function getStoreNewGoods(){
        var param = {};
        param.store_id = store_id;
        var load_class_newgoods = new ncScrollLoad();
        load_class_newgoods.loadInit({'url':ApiUrl + '/index.php?act=store&op=store_new_goods','getparam':param,'tmplid':'newgoods_tpl','containerobj':$("#newgoods"),'iIntervalId':true,'resulthandle':'tidyStoreNewGoodsData'});
    }
    //加载店铺促销活动
    function getStoreActivity(){
        $.ajax({
            type: 'post',
            url: ApiUrl + "/index.php?act=store&op=store_promotion",
            data: {store_id: store_id},
            dataType: 'json',
            success: function(result) {
                result.datas.store_id = store_id;
                var html = template.render('storeactivity_tpl', result.datas);
                $("#storeactivity_con").html(html);
            }
        });
    }

    //导航
    $("#nav_tab").find('a').click(function(){
        $('#nav_tab').find('li').removeClass('selected');
        $(this).parent().addClass('selected').siblings().removeClass("selected");
        $("#storeindex_con,#allgoods_con,#newgoods_con,#storeactivity_con").hide();
        window.scrollTo(0,0);

        var data_type = $(this).attr('data-type');
        switch (data_type){
            case 'storeindex':
                $("#storeindex_con").show();
                slidersShow();
                break;
            case 'allgoods':
                if (!$("#allgoods_con").html()) {
                    $("#allgoods_con").load('store_goods_list.html',function(){
                        $(".goods-search-list-nav").addClass('posr');
                        $(".goods-search-list-nav").css("top","0");
                        $("#sort_inner").css("position","static");
                    });
                }
                $("#allgoods_con").show();
                break;
            case 'newgoods':
                if (!$("#newgoods").html()) {
                    getStoreNewGoods();
                }
                $("#newgoods_con").show();
                break;
            case 'storeactivity':
                if (!$("#storeactivity_con").html()) {
                    getStoreActivity();
                }
                $("#storeactivity_con").show();
                break;
        }
    });

    //免费领取代金券
    $("#store_voucher").click(function(){
        if (!$("#store_voucher_con").html()) {
            $.ajax({
                type: 'post',
                url: ApiUrl + '/index.php?act=voucher&op=voucher_tpl_list',
                data: {store_id: store_id, gettype: 'free'},
                dataType: 'json',
                async: false,
                success: function(result) {
                    if (result.code == 200) {
                        var html = template.render('store_voucher_con_tpl', result.datas);
                        $("#store_voucher_con").html(html);
                    }
                }
            });
        }
        //从下到上动态显示隐藏内容
        $.animationUp({'valve':''});
    });
    //领店铺代金券
    $('#store_voucher_con').on('click', '[nc_type="getvoucher"]', function(){
        getFreeVoucher($(this).attr('data-tid'));
    });

    //收藏店铺
    $("#store_notcollect").live('click',function() {
        //添加收藏
        var f_result = favoriteStore(store_id);
        if (f_result) {
            $("#store_notcollect").hide();
            $("#store_collected").show();
            var t;
            var favornum = (t = parseInt($("#store_favornum_hide").val())) > 0?t+1:1;
            $('#store_favornum').html(favornum);
            $('#store_favornum_hide').val(favornum);
        }
    });
    //取消店铺收藏
    $("#store_collected").live('click',function() {
        //取消收藏
        var f_result = dropFavoriteStore(store_id);
        if (f_result) {
            $("#store_collected").hide();
            $("#store_notcollect").show();
            var t;
            var favornum = (t = parseInt($("#store_favornum_hide").val())) > 1?t-1:0;
            $('#store_favornum').html(favornum);
            $('#store_favornum_hide').val(favornum);
        }
    });

});
