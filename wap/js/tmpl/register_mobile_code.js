$(function(){
    //加载验证码
    loadSeccode();
    $("#refreshcode").bind('click',function(){
        loadSeccode();
    });
    // 发送手机验证码
    var mobile = getQueryString("mobile");
    var sec_val = getQueryString("captcha");
    var sec_key = getQueryString("codekey");
    $('#usermobile').html(mobile);
    send_sms(mobile, sec_val, sec_key);
    $('#again').click(function(){
        sec_val = $('#captcha').val();
        sec_key = $('#codekey').val();
        send_sms(mobile, sec_val, sec_key);
    });
    
    $('#register_mobile_password').click(function(){
        if (!$(this).parent().hasClass('ok')) {
            return false;
        }
        var captcha = $('#mobilecode').val();
        if (captcha.length == 0) {
            errorTipsShow('<p>请填写验证码<p>');
        }
        check_sms_captcha(mobile, captcha);
        return false;
        
    });
});
// 发送手机验证码
function send_sms(mobile, sec_val, sec_key) {
    $.getJSON(ApiUrl+'/index.php?act=connect&op=get_sms_captcha', {type:1,phone:mobile,sec_val:sec_val,sec_key:sec_key}, function(result){
        if(!result.datas.error){
            $.sDialog({
                skin:"green",
                content:'发送成功',
                okBtn:false,
                cancelBtn:false
            });
            $('.code-again').hide();
            $('.code-countdown').show().find('em').html(result.datas.sms_time);
            var times_Countdown = setInterval(function(){
                var em = $('.code-countdown').find('em');
                var t = parseInt(em.html() - 1);
                if (t == 0) {
                    $('.code-again').show();
                    $('.code-countdown').hide();
                    clearInterval(times_Countdown);
                } else {
                    em.html(t);
                }
            },1000);
        }else{
            loadSeccode();
            errorTipsShow('<p>' + result.datas.error + '<p>');
        }
    });
}

function check_sms_captcha(mobile, captcha) {
    $.getJSON(ApiUrl + '/index.php?act=connect&op=check_sms_captcha', {type:1,phone:mobile,captcha:captcha }, function(result){
        if (!result.datas.error) {
            window.location.href = 'register_mobile_password.html?mobile=' + mobile + '&captcha=' + captcha;
        } else {
            loadSeccode();
            errorTipsShow('<p>' + result.datas.error + '<p>');
        }
    });
}