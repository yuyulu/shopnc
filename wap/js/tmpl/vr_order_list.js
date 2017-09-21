var page = pagesize;
var curpage = 1;
var hasMore = true;
var footer = false;
var reset = true;
var orderKey = '';

$(function(){
    var key = getCookie('key');
    if(!key){
        window.location.href = WapSiteUrl+'/tmpl/member/login.html';
    }

	if (getQueryString('data-state') != '') {
	    $('#filtrate_ul').find('li').has('a[data-state="' + getQueryString('data-state')  + '"]').addClass('selected').siblings().removeClass("selected");
	}

    var readytopay = false;
    var readytopayWx = false;

    $('#search_btn').click(function(){
        reset = true;
    	initPage();
    });

    $('#fixed_nav').waypoint(function() {
        $('#fixed_nav').toggleClass('fixed');
    }, {
        offset: '50'
    });

	function initPage(){
	    if (reset) {
	        curpage = 1;
	        hasMore = true;
	        $('#footer').html('');
	    }
        $('.loading').remove();
        if (!hasMore) {
            return false;
        }
        hasMore = false;
	    var state_type = $('#filtrate_ul').find('.selected').find('a').attr('data-state');
	    var orderKey = $('#order_key').val();
		$.ajax({
			type:'post',
			url:ApiUrl+"/index.php?act=member_vr_order&op=order_list&page="+page+"&curpage="+curpage,
			data:{key:key, state_type:state_type, order_key : orderKey},
			dataType:'json',
			success:function(result){
				checkLogin(result.login);//检测是否登录了
				curpage++;
                hasMore = result.hasmore;
                if (!hasMore) {
                    get_footer();
                }
                if (result.datas.order_list.length <= 0) {
                    $('#footer').addClass('posa');
                }
				var data = result.datas;
				data.WapSiteUrl = WapSiteUrl;//页面地址
				data.ApiUrl = ApiUrl;
				data.key = getCookie('key');
				template.helper('$getLocalTime', function (nS) {
                    var d = new Date(parseInt(nS) * 1000);
                    var s = '';
                    s += d.getFullYear() + '年';
                    s += (d.getMonth() + 1) + '月';
                    s += d.getDate() + '日 ';
                    s += d.getHours() + ':';
                    s += d.getMinutes();
                    return s;
				});
                template.helper('p2f', function(s) {
                    return (parseFloat(s) || 0).toFixed(2);
                });
                template.helper('parseInt', function(s) {
                    return parseInt(s);
                });
				var html = template.render('order-list-tmpl', data);
				if (reset) {
				    reset = false;
				    $("#order-list").html(html);
				} else {
                    $("#order-list").append(html);
                }
			}
		});

	}

    $.ajax({
        type:'get',
        url:ApiUrl+"/index.php?act=member_payment&op=payment_list",
        data:{key:key},
        dataType:'json',
        success:function(result){
            var validPayments = {};
            $.each((result && result.datas && result.datas.payment_list) || [], function(k, v) {
                validPayments[v] = true;
            });

            var m = navigator.userAgent.match(/MicroMessenger\/(\d+)\./);
            if (parseInt(m && m[1] || 0) >= 5) {
                // in WX
                if (validPayments.wxpay_jsapi) {
                    readytopayWx = true;
                }
            } else {
                if (validPayments.alipay) {
                    readytopay = true;
                }
            }

        }
    });
    //初始化页面
    initPage(page,curpage);

    $(window).scroll(function(){
        if(($(window).scrollTop() + $(window).height() > $(document).height()-1)){
            initPage();
        }
    });

    $('#order-list').on('click','.check-payment',function() {
        var pay_sn = $(this).attr('data-paySn');
        toPay(pay_sn,'member_vr_buy','pay');
        return false;
    });

    // 取消
    $('#order-list').on('click','.cancel-order', cancelOrder);

    //取消订单
    function cancelOrder(){
        var order_id = $(this).attr("order_id");

        $.sDialog({
            content: '确定取消订单？',
            okFn: function() { cancelOrderId(order_id); }
        });
    }

    function cancelOrderId(order_id) {
        $.ajax({
            type:"post",
            url:ApiUrl+"/index.php?act=member_vr_order&op=order_cancel",
            data:{order_id:order_id,key:key},
            dataType:"json",
            success:function(result){
                if(result.datas && result.datas == 1){
                    initPage(page,curpage);
                } else {
                    $.sDialog({
                        skin:"red",
                        content:result.datas.error,
                        okBtn:false,
                        cancelBtn:false
                    });
                }
            }
        });
    }

    // 评价
    $('#order-list').on('click','.evaluation-order', function(){
        var orderId = $(this).attr('order_id');
        location.href = WapSiteUrl + '/tmpl/member/member_vr_evaluation.html?order_id=' + orderId;
    });

    $('#filtrate_ul').find('a').click(function(){
        $('#filtrate_ul').find('li').removeClass('selected');
        $(this).parent().addClass('selected').siblings().removeClass("selected");
        reset = true;
        window.scrollTo(0,0);
        initPage();
    });
});
function get_footer() {
    if (!footer) {
        footer = true;
        $.ajax({
            url: WapSiteUrl+'/js/tmpl/footer.js',
            dataType: "script"
          });
    }
}