<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=message&op=email_tpl" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['nc_message_set'];?> - 编辑模板</h3>
        <h5><?php echo $lang['nc_email_set_subhead'];?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>插入的变量必需包括花括号“{}”，当应用范围不支持该变量时，该变量将不会在前台显示(变量后边的分隔符也不会显示)，留空为系统默认设置。</li>
      <li>变量函数说明：站点名称 {$site_name} | 时间点 {$send_time} | 验证码 {$verify_code} | 自提码 {$pickup_code}</li>
    </ul>
  </div>
  <form id="form_templates" method="post" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="code" value="<?php echo $output['templates_array']['code'];?>" />
    <div class="ncap-form-default">
      <div class="title">
        <h3><?php echo $output['templates_array']['name'];?></h3>
      </div>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['mailtemplates_edit_title']?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['templates_array']['title'];?>" name="title" class="input-txt">
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['mailtemplates_edit_content']?></label>
        </dt>
        <dd class="opt">
          <?php showEditor('content', $output['templates_array']['content']); ?>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.form1.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
