$(function(){
    //加载验证码
    loadSeccode();
    $("#refreshcode").bind('click',function(){
        loadSeccode();
    });
    $.sValid.init({//注册验证
        rules:{
            usermobile:{
                required:true,
                mobile:true
            }
        },
        messages:{
            usermobile:{
                required:"请填写手机号！",
                mobile:"手机号码不正确"
            }
        },
        callback:function (eId,eMsg,eRules){
            if(eId.length >0){
                var errorHtml = "";
                $.map(eMsg,function (idx,item){
                    errorHtml += "<p>"+idx+"</p>";
                });
                errorTipsShow(errorHtml);
            }else{
                errorTipsHide();
            }
        }  
    });
    
	$('#refister_mobile_btn').click(function(){
        if (!$(this).parent().hasClass('ok')) {
            return false;
        }
	    if ($.sValid()) {
	        $(this).attr('href', 'register_mobile_code.html?mobile=' + $('#usermobile').val() + '&captcha=' + $('#captcha').val() + '&codekey=' + $('#codekey').val());
	    } else {
	        return false;
	    }
	});
});