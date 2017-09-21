var page = pagesize;
var curpage = 1;
var hasmore = true;
var footer = false;
var keyword = decodeURIComponent(getQueryString('keyword'));
var gc_id = getQueryString('gc_id');
var b_id = getQueryString('b_id');
var key = getQueryString('key');
var order = getQueryString('order');
var area_id = getQueryString('area_id');
var price_from = getQueryString('price_from');
var price_to = getQueryString('price_to');
var own_shop = getQueryString('own_shop');
var gift = getQueryString('gift');
var groupbuy = getQueryString('groupbuy');
var xianshi = getQueryString('xianshi');
var virtual = getQueryString('virtual');
var ci = getQueryString('ci');
var myDate = new Date();
var searchTimes = myDate.getTime();

$(function(){
    $.animationLeft({
        valve : '#search_adv',
        wrapper : '.nctouch-full-mask',
        scroll : '#list-items-scroll'
    });
    $("#header").on('click', '.header-inp', function(){
        location.href = WapSiteUrl + '/tmpl/search.html?keyword=' + keyword;
    });
    if (keyword != '') {
    	$('#keyword').html(keyword);
    }

    // 商品展示形式
    $('#show_style').click(function(){
        if ($('#product_list').hasClass('grid')) {
            $(this).find('span').removeClass('browse-grid').addClass('browse-list');
            $('#product_list').removeClass('grid').addClass('list');
        } else {
            $(this).find('span').addClass('browse-grid').removeClass('browse-list');
            $('#product_list').addClass('grid').removeClass('list');
        }
    });

    // 排序显示隐藏
    $('#sort_default').click(function(){
        if ($('#sort_inner').hasClass('hide')) {
            $('#sort_inner').removeClass('hide');
        } else {
            $('#sort_inner').addClass('hide');
        }
    });
    $('#nav_ul').find('a').click(function(){
        $(this).addClass('current').parent().siblings().find('a').removeClass('current');
        if (!$('#sort_inner').hasClass('hide') && $(this).parent().index() > 0) {
            $('#sort_inner').addClass('hide');
        }
    });
    $('#sort_inner').find('a').click(function(){
        $('#sort_inner').addClass('hide').find('a').removeClass('cur');
        var text = $(this).addClass('cur').text();
        $('#sort_default').html(text + '<i></i>');
    });

    $('#product_list').on('click', '.goods-store a',function(){
        var $this = $(this);
        var store_id = $(this).attr('data-id');
        var store_name = $(this).text();
        $.getJSON(ApiUrl + '/index.php?act=store&op=store_credit', {store_id:store_id}, function(result){
            var html = '<dl>'
                + '<dt><a href="store.html?store_id=' + store_id + '">' + store_name + '<span class="arrow-r"></span></a></dt>'
                + '<dd class="' + result.datas.store_credit.store_desccredit.percent_class + '">描述相符：<em>' + result.datas.store_credit.store_desccredit.credit + '</em><i></i></dd>'
                + '<dd class="' + result.datas.store_credit.store_servicecredit.percent_class + '">服务态度：<em>' + result.datas.store_credit.store_servicecredit.credit + '</em><i></i></dd>'
                + '<dd class="' + result.datas.store_credit.store_deliverycredit.percent_class + '">发货速度：<em>' + result.datas.store_credit.store_deliverycredit.credit + '</em><i></i></dd>'
                + '</dl>';
            //渲染页面
            
            $this.next().html(html).show();
        });
    }).on('click', '.sotre-creidt-layout', function(){
        $(this).hide();
    });

    get_list();
    $(window).scroll(function(){
        if(($(window).scrollTop() + $(window).height() > $(document).height()-1)){
            get_list();
        }
    });
    search_adv();
});

function get_list() {
    $('.loading').remove();
    if (!hasmore) {
        return false;
    }
    hasmore = false;
    param = {};
    param.page = page;
    param.curpage = curpage;
    if (gc_id != '') {
        param.gc_id = gc_id;
    } else if (keyword != '') {
        param.keyword = keyword;
    } else if (b_id != '') {
        param.b_id = b_id;
    }
    if (key != '') {
        param.key = key;
    }
    if (order != '') {
        param.order = order;
    }

    $.getJSON(ApiUrl + '/index.php?act=goods&op=goods_list' + window.location.search.replace('?','&'), param, function(result){
    	if(!result) {
    		result = [];
    		result.datas = [];
    		result.datas.goods_list = [];
    	}
        $('.loading').remove();
        curpage++;
        var html = template.render('home_body', result);
        $("#product_list .goods-secrch-list").append(html);
        hasmore = result.hasmore;
    });
}

function search_adv() {
    $.getJSON(ApiUrl + '/index.php?act=index&op=search_adv', function(result) {
    	var data = result.datas;
    	$('#list-items-scroll').html(template.render('search_items',data));
    	if (area_id) {
    		$('#area_id').val(area_id);
    	}
    	if (price_from) {
    		$('#price_from').val(price_from);
    	}
    	if (price_to) {
    		$('#price_to').val(price_to);
    	}
    	if (own_shop) {
    		$('#own_shop').addClass('current');
    	}
    	if (gift) {
    		$('#gift').addClass('current');
    	}
    	if (groupbuy) {
    		$('#groupbuy').addClass('current');
    	}
    	if (xianshi) {
    		$('#xianshi').addClass('current');
    	}
    	if (virtual) {
    		$('#virtual').addClass('current');
    	}
    	if (ci) {
    		var ci_arr = ci.split('_');
    		for(var i in ci_arr) {
    			$('a[name="ci"]').each(function(){
    				if ($(this).attr('value') == ci_arr[i]) {
    					$(this).addClass('current');
    				}
    			});
    		}
    	}
    	$('#search_submit').click(function(){
    		var queryString = '?keyword=' + keyword, ci = '';
    		queryString += '&area_id=' + $('#area_id').val();
    		if ($('#price_from').val() != '') {
    			queryString += '&price_from=' + $('#price_from').val();
    		}
    		if ($('#price_to').val() != '') {
    			queryString += '&price_to=' + $('#price_to').val();
    		}
    		if ($('#own_shop')[0].className == 'current') {
    			queryString += '&own_shop=1';
    		}
    		if ($('#gift')[0].className == 'current') {
    			queryString += '&gift=1';
    		}
			if ($('#groupbuy')[0].className == 'current') {
				queryString += '&groupbuy=1';
			}
			if ($('#xianshi')[0].className == 'current') {
				queryString += '&xianshi=1';
			}
			if ($('#virtual')[0].className == 'current') {
				queryString += '&virtual=1';
			}
    		$('a[name="ci"]').each(function(){
    			if ($(this)[0].className == 'current') {
    				ci += $(this).attr('value') + '_';
    			}
    		});
    		if (ci != '') {
    			queryString += '&ci=' + ci;
    		}
    		window.location.href = WapSiteUrl + '/tmpl/product_list.html' + queryString;
    	});
    	$('a[nctype="items"]').click(function(){
    		var myDate = new Date();
    		if(myDate.getTime() - searchTimes > 300) {
    			$(this).toggleClass('current');
    			searchTimes = myDate.getTime();
    		}
    	});
    	$('input[nctype="price"]').on('blur',function(){
    		if ($(this).val() != '' && ! /^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test($(this).val())) {
    			$(this).val('');
    		}
    	});
    	$('#reset').click(function(){
    		$('a[nctype="items"]').removeClass('current');
    		$('input[nctype="price"]').val('');
    		$('#area_id').val('');
    	});
    });
}

function init_get_list(o, k) {
    order = o;
    key = k;
    curpage = 1;
    hasmore = true;
    $("#product_list .goods-secrch-list").html('');
    $('#footer').removeClass('posa');
    get_list();
}