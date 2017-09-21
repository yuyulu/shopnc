var order_id;
$(function(){
	var key = getCookie('key');
	if(!key){
		window.location.href = WapSiteUrl+'/tmpl/member/login.html';
	}
    $.getJSON(ApiUrl + '/index.php?act=member_refund&op=refund_all_form',{key:key,order_id:getQueryString('order_id')}, function(result) {
    	result.datas.WapSiteUrl = WapSiteUrl;
    	$('#order-info-container').html(template.render('order-info-tmpl',result.datas));
    	order_id = result.datas.order.order_id;
    	$('#allow_refund_amount').val('￥'+result.datas.order.allow_refund_amount);

        // 图片上传
        $('input[name="refund_pic"]').ajaxUploadImage({
            url : ApiUrl + "/index.php?act=member_refund&op=upload_pic",
            data:{key:key},
            start :  function(element){
                element.parent().after('<div class="upload-loading"><i></i></div>');
                element.parent().siblings('.pic-thumb').remove();
            },
            success : function(element, result){
                checkLogin(result.login);
                if (result.datas.error) {
                    element.parent().siblings('.upload-loading').remove();
                    $.sDialog({
                        skin:"red",
                        content:'图片尺寸过大！',
                        okBtn:false,
                        cancelBtn:false
                    });
                    return false;
                }
                element.parent().after('<div class="pic-thumb"><img src="'+result.datas.pic+'"/></div>');
                element.parent().siblings('.upload-loading').remove();
                element.parents('a').next().val(result.datas.file_name);
            }
        });

        $('.btn-l').click(function(){
            var _form_param = $('form').serializeArray();
            var param = {};
            param.key = key;
            param.order_id = order_id;
            for (var i=0; i<_form_param.length; i++) {
                param[_form_param[i].name] = _form_param[i].value;
            }
            if (param.buyer_message.length == 0) {
                $.sDialog({
                    skin:"red",
                    content:'请填写退款说明',
                    okBtn:false,
                    cancelBtn:false
                });
                return false;
            }
            $.ajax({//获取区域列表
                type:'post',
                url:ApiUrl+'/index.php?act=member_refund&op=refund_all_post',
                data:param,
                dataType:'json',
                async:false,
                success:function(result){
                    checkLogin(result.login);
                    if (result.datas.error) {
                        $.sDialog({
                            skin:"red",
                            content:result.datas.error,
                            okBtn:false,
                            cancelBtn:false
                        });
                        return false;
                    }
                    window.location.href = WapSiteUrl + '/tmpl/member/member_refund.html';
                }
            });
        });
    });
});