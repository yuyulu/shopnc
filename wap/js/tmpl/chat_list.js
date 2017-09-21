$(function(){
    var key = getCookie('key');
    if(!key){
        location.href = 'login.html';
    }
    template.helper('isEmpty', function(o) {
        for (var i in o) {
            return false;
        }
        return true;
    });

    $.ajax({
        type: 'post',
        url: ApiUrl+'/index.php?act=member_chat&op=get_user_list',
        data: {key:key,recent:1},
        dataType:'json',
        success: function(result){
            checkLogin(result.login);
            var data = result.datas;
            //渲染模板
            $("#messageList").html(template.render('messageListScript', data));
            
            
            $('.msg-list-del').click(function(){
                var t_id = $(this).attr('t_id');
                $.ajax({
                    type: 'post',
                    url: ApiUrl+'/index.php?act=member_chat&op=del_msg',
                    data: {key:key,t_id:t_id},
                    dataType:'json',
                    success: function(result){
                        if (result.code == 200) {
                            location.reload();
                        } else {
                            $.sDialog({
                                skin:"red",
                                content:result.datas.error,
                                okBtn:false,
                                cancelBtn:false
                            });
                            return false;
                        }
                    }
                });
            });
        }
    });
});