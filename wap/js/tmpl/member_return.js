$(function(){
    var key = getCookie('key');
    //渲染list
    var load_class = new ncScrollLoad();
    load_class.loadInit({
        url:ApiUrl + '/index.php?act=member_return&op=get_return_list',
        getparam:{key :key },
        tmplid:'return-list-tmpl',
        containerobj:$("#return-list"),
        iIntervalId:true,
        data:{WapSiteUrl:WapSiteUrl},
        callback:function(){
            $('.delay-btn').click(function(){
                return_id = $(this).attr('return_id');
                $.getJSON(ApiUrl+'/index.php?act=member_return&op=delay_form', {key:key,return_id:return_id}, function(result){
                    checkLogin(result.login);
                    $.sDialog({
                        skin:"red",
                        content:'发货 <span id="delayDay">'+result.datas.return_delay+'</span> 天后，当商家选择未收到则要进行延迟时间操作；<br> 如果超过 <span id="confirmDay">'+result.datas.return_confirm+'</span> 天不处理按弃货处理，直接由管理员确认退款。',
                        okFn:function(){
                            $.ajax({
                                type:'post',
                                url:ApiUrl+'/index.php?act=member_return&op=delay_post',
                                data:{key:key,return_id:return_id},
                                dataType:'json',
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
                        },
                        cancelFn:function(){
                            window.location.href = WapSiteUrl + '/tmpl/member/member_return.html';
                        }
                    });
                    return false;
                });
            });
        }
    });
});