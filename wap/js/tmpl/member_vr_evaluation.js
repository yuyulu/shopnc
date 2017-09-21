$(function(){
    var key = getCookie('key');
    if (!key) {
        window.location.href = WapSiteUrl + '/tmpl/member/login.html';
        return;
    }
    var order_id = getQueryString('order_id');

    $.getJSON(ApiUrl + '/index.php?act=member_evaluate&op=vr', {key:key, order_id:order_id}, function(result){
        if (result.datas.error) {
            $.sDialog({
                skin:"red",
                content:result.datas.error,
                okBtn:false,
                cancelBtn:false
            });
            return false;
        }
        var html = template.render('member-evaluation-script', result.datas);
        $("#member-evaluation-div").html(html);

        // 星星选择
        $('.star-level').find('i').click(function(){
            var _index = $(this).index();
            for (var i=0; i<5; i++) {
                var _i = $(this).parent().find('i').eq(i);
                if (i<=_index) {
                    _i.removeClass('star-level-hollow').addClass('star-level-solid');
                } else {
                    _i.removeClass('star-level-solid').addClass('star-level-hollow');
                }
            }
            $(this).parent().next().val(_index + 1);
        });
        
        $('.btn-l').click(function(){
            var _form_param = $('form').serializeArray();
            var param = {};
            param.key = key;
            param.order_id = order_id;
            for (var i=0; i<_form_param.length; i++) {
                param[_form_param[i].name] = _form_param[i].value;
            }
            $.ajax({//获取区域列表
                type:'post',
                url:ApiUrl+'/index.php?act=member_evaluate&op=save_vr',
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
                    window.location.href = WapSiteUrl + '/tmpl/member/vr_order_list.html';
                }
            });
        });
    });
    
});

