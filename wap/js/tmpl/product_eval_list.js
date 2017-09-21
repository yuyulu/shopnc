var goods_id = getQueryString("goods_id");
$(function(){

    //渲染list
    var load_class = new ncScrollLoad();
    load_class.loadInit({
        'url':ApiUrl + '/index.php?act=goods&op=goods_evaluate',
        'getparam':{goods_id:goods_id},
        'tmplid':'product_ecaluation_script',
        'containerobj':$("#product_evaluation_html"),
        'iIntervalId':true,
        callback:function(){
            callback();
        }
        });

    $('#goodsDetail').click(function(){
        window.location.href = WapSiteUrl+'/tmpl/product_detail.html?goods_id=' + goods_id;
    });
    $('#goodsBody').click(function(){
        window.location.href = WapSiteUrl+'/tmpl/product_info.html?goods_id=' + goods_id;
    });
    $('#goodsEvaluation').click(function(){
        window.location.href = WapSiteUrl+'/tmpl/product_eval_list.html?goods_id=' + goods_id;
    });
    
    $('.nctouch-tag-nav').find('a').click(function(){
        var type = $(this).attr('data-state');
        load_class.loadInit({
            url:ApiUrl + '/index.php?act=goods&op=goods_evaluate',
            getparam:{goods_id:goods_id,type:type},
            tmplid:'product_ecaluation_script',
            containerobj:$("#product_evaluation_html"),
            iIntervalId:true,
            callback:function(){
                callback();
            }
            });
        $(this).parent().addClass('selected').siblings().removeClass('selected');
    });

});

function callback(){
    $('.goods_geval').on('click', 'a', function(){
        var _this = $(this).parents('.goods_geval');
        _this.find('.nctouch-bigimg-layout').removeClass('hide');
        var picBox = _this.find('.pic-box');
        _this.find('.close').click(function(){
            _this.find('.nctouch-bigimg-layout').addClass('hide');
        });
        if (picBox.find('li').length < 2) {
            return;
        }
        Swipe(picBox[0], {
            speed: 400,
            auto: 3000,
            continuous: false,
            disableScroll: false,
            stopPropagation: false,
            callback: function(index, elem) {
                $(elem).parents('.nctouch-bigimg-layout').find('div').last().find('li').eq(index).addClass('cur').siblings().removeClass('cur');
            },
            transitionEnd: function(index, elem) {}
        });
    });
}
