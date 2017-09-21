<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=admin&op=admin" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['nc_limit_manage'];?> - <?php echo $lang['nc_edit'];?>管理员“<?php echo $output['admininfo']['admin_name'];?>”</h3>
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
      <li>编辑管理员修改所属权限组，登录密码不变时不必重新填写。</li>
    </ul>
  </div>
  <form id="admin_form" method="post" action='index.php?act=admin&op=admin_edit&admin_id=<?php echo $output['admininfo']['admin_id'];?>'>
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_index_username'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="input-txt" value="<?php echo $output['admininfo']['admin_name'];?>" readonly />
          <span class="err"></span>
          <p class="notic">管理员登录名不可修改。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="new_pw"><?php echo $lang['admin_edit_admin_pw']; ?></label>
        </dt>
        <dd class="opt">
          <input id="new_pw" name="new_pw" class="input-txt" type="password">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['admin_edit_pwd_tip1'];?>。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="new_pw2"><?php echo $lang['admin_edit_admin_pw2']; ?></label>
        </dt>
        <dd class="opt">
          <input id="new_pw2" name="new_pw2" class="input-txt" type="password">
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
            <option <?php if ($v['gid'] == $output['admininfo']['admin_gid']) echo 'selected';?> value="<?php echo $v['gid'];?>"><?php echo $v['gname'];?></option>
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
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#admin_form").valid()){
     $("#admin_form").submit();
	}
	});
});
$(document).ready(function(){
	$("#admin_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
        	new_pw : {
				minlength: 6,
				maxlength: 20
            },
            new_pw2 : {
				minlength: 6,
				maxlength: 20,
				equalTo: '#new_pw'
            },
            gid : {
                required : true
            }
        },
        messages : {
        	new_pw : {
				minlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_password_max'];?>',
				maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_password_max'];?>'
            },
            new_pw2 : {
				minlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_password_max'];?>',
				maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_password_max'];?>',
				equalTo:   '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_edit_repeat_error'];?>'
            },
            gid : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_gid_null'];?>'
            }
        }
	});
});
</script>