<?php defined('In33hao') or exit('Access Invalid!');?>
<script language="JavaScript" type="text/javascript">
window.onload = function() {
    tips = new Array(2);
    tips[0] = document.getElementById("loginBG01");
    tips[1] = document.getElementById("loginBG02");
    index = Math.floor(Math.random() * tips.length);
    tips[index].style.display = "block";
};
$(document).ready(function() {
    //更换验证码
    function change_seccode() {
        $('#codeimage').attr('src', 'index.php?act=seccode&op=makecode&nchash=<?php echo $output['nchash'];?>&t=' + Math.random());
        $('#captcha').select();
    }

    $('[nctype="btn_change_seccode"]').on('click', function() {
        change_seccode();
    });

    //登陆表单验证
    $("#chain_login").validate({
        errorPlacement:function(error, element) {
            element.prev(".repuired").append(error);
        },
        submitHandler:function(form){
            ajaxpost('chain_login', '', '', 'onerror');
        },
        onkeyup: false,
        rules:{
            user:{
                required:true
            },
            pwd:{
                required:true
            },
            captcha:{
                required:true,
                remote:{
                    url:"index.php?act=seccode&op=check&nchash=<?php echo $output['nchash'];?>",
                    type:"get",
                    data:{
                        captcha:function() {
                            return $("#captcha").val();
                        }
                    },
                    complete: function(data) {
                        if(data.responseText == 'false') {
                            change_seccode();
                        }
                    }
                }
            }
        },
        messages:{
            user:{
                required:"<i class='icon-exclamation-sign'></i>用户名不能为空"
            },
            pwd:{
                required:"<i class='icon-exclamation-sign'></i>密码不能为空"
            },
            captcha:{
                required:"<i class='icon-exclamation-sign'></i>验证码不能为空",
                remote:"<i class='icon-frown'></i>验证码错误"
            }
        }
    });
	//Hide Show verification code
    $("#hide").click(function(){
        $(".code").fadeOut("slow");
    });
    $("#captcha").focus(function(){
        $(".code").fadeIn("fast");
    });

});
</script>

<div id="loginBG01" class="ncsc-login-bg">
  <p class="pngFix"></p>
</div>
<div id="loginBG02" class="ncsc-login-bg">
  <p class="pngFix"></p>
</div>
<div class="ncsc-login-container">
  <div class="ncsc-login-title">
    <h2>门店管理中心</h2>
    <h4><a href="<?php echo urlShop('seller_login', 'show_login');?>">商家管理登录</a></h4>
    <span>门店登录用户名及密码是由商家管理中心进行添加及设定<br/>
    该账号仅与店铺对应的线下门店关联，并非商城通用账号</span></div>
  <form id="chain_login" method="post" action="<?php echo CHAIN_SITE_URL;?>/index.php?act=login">
    <?php Security::getToken();?>
    <input type="hidden" name="form_submit" value="ok" />
    <div class="input">
      <label>用户名</label>
      <span class="repuired"></span>
      <input class="text" type="text" name="user" id="user" autofocus autocomplete="off"  />
      <span class="ico"><i class="icon-user"></i></span> </div>
    <div class="input">
      <label>密码</label>
      <span class="repuired"></span>
      <input class="text" type="password" name="pwd" id="pwd" />
      <span class="ico"><i class="icon-key"></i></span> </div>
    <div class="input">
    <label>验证码</label>
    <span class="repuired"></span>
    <input type="text" name="captcha" id="captcha" autocomplete="off" class="text" style="width: 80px;" maxlength="4" size="10" />
    <div class="code">
      <div class="arrow"></div>
      <div class="code-img"><a href="javascript:void(0)" nctype="btn_change_seccode"><img src="index.php?act=seccode&op=makecode&nchash=<?php echo $output['nchash'];?>" name="codeimage" border="0" id="codeimage"></a></div>
      <a href="JavaScript:void(0);" id="hide" class="close" title="<?php echo $lang['login_index_close_checkcode'];?>"><i></i></a> <a href="JavaScript:void(0);" class="change" nctype="btn_change_seccode" title="<?php echo $lang['login_index_change_checkcode'];?>"><i></i></a> </div>
    <span class="ico"><i class="icon-qrcode"></i></span>
    <input type="submit" class="login-submit" value="确认登录">
  </form>
</div>
