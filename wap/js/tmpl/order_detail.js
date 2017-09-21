$(function(){
	var key = getCookie('key');
	if(!key){
		window.location.href = WapSiteUrl+'/tmpl/member/login.html';
	}
    $.getJSON(ApiUrl + '/index.php?act=member_order&op=order_info',{key:key,order_id:getQueryString('order_id')}, function(result) {
    	result.datas.order_info.WapSiteUrl = WapSiteUrl;
    	$('#order-info-container').html(template.render('order-info-tmpl',result.datas.order_info));

        // 取消
        $(".cancel-order").click(cancelOrder);
        // 收货
        $(".sure-order").click(sureOrder);
        // 评价
        $(".evaluation-order").click(evaluationOrder);
        // 追评
        $('.evaluation-again-order').click(evaluationAgainOrder);
        // 全部退款
        $('.all_refund_order').click(allRefundOrder);
        // 部分退款
        $('.goods-refund').click(goodsRefund);
        // 退货
        $('.goods-return').click(goodsReturn);

        $('.viewdelivery-order').click(viewOrderDelivery);
        $.ajax({
            type: 'post',
            url: ApiUrl + "/index.php?act=member_order&op=get_current_deliver",
            data:{key:key,order_id:getQueryString("order_id")},
            dataType:'json',
            success:function(result) {
                //检测是否登录了
                checkLogin(result.login);

                var data = result && result.datas;
                if (data.deliver_info) {
                    $("#delivery_content").html(data.deliver_info.context);
                    $("#delivery_time").html(data.deliver_info.time);               	
                }
            }
        });
    });
    
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
            url:ApiUrl+"/index.php?act=member_order&op=order_cancel",
            data:{order_id:order_id,key:key},
            dataType:"json",
            success:function(result){
                if(result.datas && result.datas == 1){
                	window.location.reload();
                }
            }
        });
    }

    //确认订单
    function sureOrder(){
        var order_id = $(this).attr("order_id");

        $.sDialog({
            content: '确定收到了货物吗？',
            okFn: function() { sureOrderId(order_id); }
        });
    }

    function sureOrderId(order_id) {
        $.ajax({
            type:"post",
            url:ApiUrl+"/index.php?act=member_order&op=order_receive",
            data:{order_id:order_id,key:key},
            dataType:"json",
            success:function(result){
                if(result.datas && result.datas == 1){
                    window.location.reload();
                }
            }
        });
    }
    // 评价
    function evaluationOrder() {
        var orderId = $(this).attr('order_id');
        location.href = WapSiteUrl + '/tmpl/member/member_evaluation.html?order_id=' + orderId;
        
    }
    // 追加评价
    function evaluationAgainOrder() {
        var orderId = $(this).attr('order_id');
        location.href = WapSiteUrl + '/tmpl/member/member_evaluation_again.html?order_id=' + orderId;
    }
    // 全部退款
    function allRefundOrder() {
        var orderId = $(this).attr('order_id');
        location.href = WapSiteUrl + '/tmpl/member/refund_all.html?order_id=' + orderId;
    }
    // 查看物流
    function viewOrderDelivery() {
        var orderId = $(this).attr('order_id');
        location.href = WapSiteUrl + '/tmpl/member/order_delivery.html?order_id=' + orderId;
    }
    // 退款
    function goodsRefund() {
        var orderId = $(this).attr('order_id');
        var orderGoodsId = $(this).attr('order_goods_id');
        location.href = WapSiteUrl + '/tmpl/member/refund.html?order_id=' + orderId +'&order_goods_id=' + orderGoodsId;
    }
    // 退货
    function goodsReturn() {
        var orderId = $(this).attr('order_id');
        var orderGoodsId = $(this).attr('order_goods_id');
        location.href = WapSiteUrl + '/tmpl/member/return.html?order_id=' + orderId +'&order_goods_id=' + orderGoodsId;
    }
});