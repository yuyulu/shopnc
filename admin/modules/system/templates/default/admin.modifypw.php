<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <form id="admin_form" method="post" action='index.php?act=index&op=modifypw' name="adminForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="old_pw"><em>*</em><?php echo $lang['index_modifypw_oldpw']; ?><!-- 原密码 --></label>
        </dt>
        <dd class="opt">
          <input id="old_pw" name="old_pw" class="infoTableInput" type="password">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="new_pw"><em>*</em><?php echo $lang['index_modifypw_newpw']; ?><!-- 新密码 --></label>
        </dt>
        <dd class="opt">
          <input id="new_pw" name="new_pw" class="infoTableInput" type="password">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="new_pw2"><em>*</em><?php echo $lang['index_modifypw_newpw2']; ?><!-- 确认密码--></label>
        </dt>
        <dd class="opt">
          <input id="new_pw2" name="new_pw2" class="infoTableInput" type="password">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#admin_form").valid()){
     $("#admin_form").submit();
	}
	});
});
//
$(document).ready(function(){
	$("#admin_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
        	old_pw : {
        		required : true
            },
        	new_pw : {
                required : true,
				minlength: 6,
				maxlength: 20
            },
            new_pw2 : {
                required : true,
				minlength: 6,
				maxlength: 20,
				equalTo: '#new_pw'
            }
        },
        messages : {
        	old_pw : {
        		required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_password_null'];?>'
            },
        	new_pw : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_password_null'];?>',
				minlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_password_max'];?>',
				maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_password_max'];?>'
            },
            new_pw2 : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_password_null'];?>',
				minlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_password_max'];?>',
				maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_password_max'];?>',
				equalTo:   '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_edit_repeat_error'];?>'
            }
        }
	});
});
</script> 