<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <?php if (C('weixin_isuse') == 1){?>
  <div class="ncm-bind">
    <?php if (!empty($output['member_info']['weixin_unionid'])){?>
    <div class="alert">
      <h4>提示信息：</h4>
      <ul>
        <li>您已将本站账号<em>“<?php echo $_SESSION['member_name'];?>”</em>微信账号<em>“<?php echo $output['member_info']['weixin_infoarr']['nickname'];?>”</em>绑定</li>
        <li>如果您忘记本站账号<em>“<?php echo $_SESSION['member_name']; ?>”</em>的密码，请重新设置本站登录密码，再确认解除</li>
      </ul>
    </div>
    <input type="hidden" name="form_submit" value="ok"  />
    <div class="relieve">
      <form method="post" id="editbind_form" name="editbind_form" action="index.php?act=member_bind&op=weixinunbind">
        <input type='hidden' id="is_editpw" name="is_editpw" value='no'/>
        <div class="ico-wx"></div>
        <p>解除已绑定账号？</p>
        <div class="bottom">
          <label class="submit-border">
            <input class="submit" type="submit" value="确认解除" />
          </label>
        </div>
      </form>
    </div>
    <div class="revise ncm-default-form ">
      <form method="post" id="editpw_form" name="editpw_form" action="index.php?act=member_bind&op=weixinunbind">
        <input type='hidden' id="is_editpw" name="is_editpw" value='yes'/>
        <dl>
          <dt>新密码<?php echo $lang['nc_colon']; ?></dt>
          <dd>
            <input type="password"  name="new_password" id="new_password"/>
            <label for="new_password" generated="true" class="error"></label>
          </dd>
        </dl>
        <dl>
          <dt>确认密码<?php echo $lang['nc_colon']; ?></dt>
          <dd>
            <input type="password"  name="confirm_password" id="confirm_password" />
            <label for="confirm_password" generated="true" class="error"></label>
          </dd>
        </dl>
        <dl class="bottom">
          <dt></dt>
          <dd>
            <label class="submit-border">
              <input class="submit" type="submit" value="修改密码并解除" />
            </label>
          </dd>
        </dl>
      </form>
    </div>
    <?php } else {?>
    <div class="relieve pt50">
      <p class="ico"><a href="javascript:void(0);" onclick="ajax_form('weixin_form', '绑定微信账号', '<?php echo urlMember('connect_wx', 'index');?>', 360);"><img src="<?php echo MEMBER_TEMPLATES_URL;?>/images/wx_bind_small.png"></a>
      <p class="hint">点击按钮，立刻绑定微信账号</p>
    </div>
    <div class="revise pt50">
      <p class="qq">使用微信账号绑定本站，您可以...</p>
      <p>用微信账号轻松登录</p>
      <p class="hint">无需记住本站的账号和密码，随时使用微信轻松登录</p>
    </div>
    <?php }?>
  </div>
  <?php } else {?>
  <div class="warning-option"><i>&nbsp;</i><span>系统未开启微信登录功能</span></div>
  <?php }?>
</div>
<script type="text/javascript">
$(function(){
	$("#unbind").hide();

    $('#editpw_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('td').next('td');
            error_td.find('.field_notice').hide();
            error_td.append(error);
        },
        rules : {
            new_password : {
                required   : true,
                minlength  : 6,
                maxlength  : 20
            },
            confirm_password : {
                required   : true,
                equalTo    : '#new_password'
            }
        },
        messages : {
            new_password  : {
                required   : '<i class="icon-exclamation-sign"></i><?php echo $lang['member_qqconnect_new_password_null'];?>',
                minlength  : '<i class="icon-exclamation-sign"></i><?php echo $lang['member_qqconnect_password_range'];?>'
            },
            confirm_password : {
                required   : '<i class="icon-exclamation-sign"></i><?php echo $lang['member_qqconnect_ensure_password_null'];?>',
                equalTo    : '<i class="icon-exclamation-sign"></i><?php echo $lang['member_qqconnect_input_two_password_again'];?>'
            }
        }
    });
});
function showunbind(){
	$("#unbind").show();
}
function showpw(){
	$("#is_editpw").val('yes');
	$("#editbinddiv").hide();
	$("#editpwul").show();
}
</script>
