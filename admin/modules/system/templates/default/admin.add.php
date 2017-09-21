<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=admin&op=admin" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['nc_limit_manage'];?> - <?php echo $lang['nc_add'];?>管理员</h3>
        <h5><?php echo $lang['nc_limit_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>可添加一名后台管理员，设置其后台登录用户名及密码，但不可登录网站前台。</li>
      <li>管理员必须下属某个权限组，如无权限组选择请先完成“添加权限组”步骤。</li>
    </ul>
  </div>
  <form id="add_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="admin_name"><em>*</em><?php echo $lang['admin_index_username'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" id="admin_name" name="admin_name" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['admin_add_username_tip'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="admin_password"><em>*</em><?php echo $lang['admin_index_password'];?></label>
        </dt>
        <dd class="opt">
          <input type="password" id="admin_password" name="admin_password" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['admin_add_password_tip'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="admin_password"><em>*</em><?php echo $lang['admin_rpassword'];?></label>
        </dt>
        <dd class="opt">
          <input type="password" id="admin_rpassword" name="admin_rpassword" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['admin_add_rpassword_tip'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="gadmin_name"><em>*</em><?php echo $lang['gadmin_name'];?></label>
        </dt>
        <dd class="opt">
          <select name="gid">
            <?php foreach((array)$output['gadmin'] as $v){?>
            <option value="<?php echo $v['gid'];?>"><?php echo $v['gname'];?></option>
            <?php }?>
          </select>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['admin_add_gid_tip'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表
$(document).ready(function(){
	//按钮先执行验证再提交表单
	$("#submitBtn").click(function(){
	    if($("#add_form").valid()){
	     $("#add_form").submit();
		}
	});
	
	$("#add_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
		
        rules : {
            admin_name : {
                required : true,
				minlength: 3,
				maxlength: 20,
				remote	: {
                    url :'index.php?act=admin&op=ajax&branch=check_admin_name',
                    type:'get',
                    data:{
                    	admin_name : function(){
                            return $('#admin_name').val();
                        }
                    }
                }
            },
            admin_password : {
                required : true,
				minlength: 6,
				maxlength: 20
            },
            admin_rpassword : {
                required : true,
                equalTo  : '#admin_password'
            },
            gid : {
                required : true
            }        
        },
        messages : {
            admin_name : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_username_null'];?>',
				minlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_username_max'];?>',
				maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_username_max'];?>',
				remote   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_admin_not_exists'];?>'
            },
            admin_password : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_password_null'];?>',
				minlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_password_max'];?>',
				maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_password_max'];?>'
            },
            admin_rpassword : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_password_null'];?>',
                equalTo  : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_edit_repeat_error'];?>'
            },
            gid : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_gid_null'];?>'
            }
        }
	});
});
</script> 
