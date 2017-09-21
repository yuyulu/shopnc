$(function(){
    var key = getCookie('key');
    var return_id = getQueryString("refund_id");
    $.getJSON(ApiUrl+'/index.php?act=member_return&op=ship_form', {key:key,return_id:return_id}, function(result){
        checkLogin(result.login);
        $('#delayDay').html(result.datas.return_delay);
        $('#confirmDay').html(result.datas.return_confirm);
        for (var i=0; i<result.datas.express_list.length; i++) {
            $('#express').append('<option value="'+result.datas.express_list[i].express_id+'">'+result.datas.express_list[i].express_name+'</option>');
        }
        

        $('.btn-l').click(function(){
            var _form_param = $('form').serializeArray();
            var param = {};
            param.key = key;
            param.return_id = return_id;
            for (var i=0; i<_form_param.length; i++) {
                param[_form_param[i].name] = _form_param[i].value;
            }
            if (param.invoice_no == '') {
                $.sDialog({
                    skin:"red",
                    content:'请填写快递单号',
                    okBtn:false,
                    cancelBtn:false
                });
                return false;
                
            }
            // 发货表单提交
            $.ajax({
                type:'post',
                url:ApiUrl+'/index.php?act=member_return&op=ship_post',
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
                    window.location.href = WapSiteUrl + '/tmpl/member/member_return.html';
                }
            });
        });
    });
});