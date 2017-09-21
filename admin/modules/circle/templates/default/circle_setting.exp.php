<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_circle_setting'];?></h3>
        <h5><?php echo $lang['nc_circle_setting_subhead'];?></h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="index.php?act=circle_setting"><?php echo $lang['nc_circle_setting'];?></a></li>
        <li><a href="index.php?act=circle_setting&op=seo"><?php echo $lang['circle_setting_seo'];?></a></li>
        <li><a href="index.php?act=circle_setting&op=sec"><?php echo $lang['circle_setting_sec'];?></a></li>
        <li><a href="JavaScript:void(0);" class="current"><?php echo $lang['circle_setting_exp'];?></a></li>
        <li><a href="index.php?act=circle_setting&op=super_list">超级管理员</a></li>
      </ul>
    </div>
  </div>
  <form id="circle_form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="old_c_logo" value="<?php echo $output['list_setting']['circle_logo'];?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="c_interval"><?php echo $lang['circle_setting_exp_release'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="c_exprelease" id="c_exprelease" class="input-txt" value="<?php echo $output['list_setting']['circle_exprelease'];?>">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="c_contentleast"><?php echo $lang['circle_setting_exp_reply'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="c_expreply" id="c_expreply" class="input-txt" value="<?php echo $output['list_setting']['circle_expreply'];?>" />
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="c_contentleast"><?php echo $lang['circle_setting_exp_release_max'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="c_expreleasemax" id="c_expreleasemax" class="input-txt" value="<?php echo $output['list_setting']['circle_expreleasemax'];?>" />
          <span class="err"></span>
          <p class="notic"><?php echo $lang['circle_setting_exp_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="c_contentleast"><?php echo $lang['circle_setting_exp_replied'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="c_expreplied" id="c_expreplied" class="input-txt" value="<?php echo $output['list_setting']['circle_expreplied'];?>" />
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="c_contentleast"><?php echo $lang['circle_setting_exp_replied_max'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="c_exprepliedmax" id="c_exprepliedmax" class="input-txt" value="<?php echo $output['list_setting']['circle_exprepliedmax'];?>" />
          <span class="err"></span>
          <p class="notic"><?php echo $lang['circle_setting_exp_tips'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.edit.js" charset="utf-8"></script> 
<script>
//按钮先执行验证再提交表单
$(function(){
	$("#submitBtn").click(function(){
		$("#circle_form").submit();
	});
});
</script> 
