<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_domain_manage'];?></h3>
        <h5><?php echo $lang['nc_domain_manage_subhead'];?></h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="JavaScript:void(0);" class="current"><?php echo $lang['nc_config'];?></a></li>
        <li><a href="index.php?act=domain&op=store_domain_list"><?php echo $lang['nc_domain_shop'];?></a></li>
      </ul>
    </div>
  </div>
  <form method="post" id="settingForm" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['if_open_domain'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
          <label for="enabled_subdomain1" class="cb-enable <?php if($output['list_setting']['enabled_subdomain'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_yes'];?>"><?php echo $lang['nc_yes'];?></label>
          <label for="enabled_subdomain0" class="cb-disable <?php if($output['list_setting']['enabled_subdomain'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_no'];?>"><?php echo $lang['nc_no'];?></label>
          <input type="radio" id="enabled_subdomain1" <?php if($output['list_setting']['enabled_subdomain'] == '1'){ ?>checked="checked"<?php } ?> value="1" name="enabled_subdomain">
          <input type="radio" id="enabled_subdomain0" <?php if($output['list_setting']['enabled_subdomain'] == '0'){ ?>checked="checked"<?php } ?> value="0" name="enabled_subdomain">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['open_domain_document'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['domain_edit'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="subdomain_edit1" class="cb-enable <?php if($output['list_setting']['subdomain_edit'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_yes'];?>"><?php echo $lang['nc_yes'];?></label>
            <label for="subdomain_edit0" class="cb-disable <?php if($output['list_setting']['subdomain_edit'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_no'];?>"><?php echo $lang['nc_no'];?></label>
            <input type="radio" id="subdomain_edit1" <?php if($output['list_setting']['subdomain_edit'] == '1'){ ?>checked="checked"<?php } ?> value="1" name="subdomain_edit">
            <input type="radio" id="subdomain_edit0" <?php if($output['list_setting']['subdomain_edit'] == '0'){ ?>checked="checked"<?php } ?> value="0" name="subdomain_edit">
          </div>
          <p class="notic"><?php echo  $lang['domain_edit_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="subdomain_times"><em>*</em><?php echo $lang['domain_times'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['list_setting']['subdomain_times'];?>" name="subdomain_times" id="subdomain_times" class="input-txt" style=" width:50px;">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['domain_times_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="subdomain_reserved"><?php echo $lang['reservations_domain'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['list_setting']['subdomain_reserved'];?>" name="subdomain_reserved" id="subdomain_reserved" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['please_input_domain'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="subdomain_length"><em>*</em><?php echo $lang['length_limit'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['list_setting']['subdomain_length'];?>" name="subdomain_length" id="subdomain_length" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['domain_length'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#settingForm").valid()){
     $("#settingForm").submit();
	}
	});
});
//
$(document).ready(function(){
	jQuery.validator.addMethod("domain_length", function(value, element) {
			var success = this.optional(element) || /^(\d+)[\/-](\d+)$/i.test(value);
			return success && (parseInt(RegExp.$1)<parseInt(RegExp.$2)) && (parseInt(RegExp.$1)>0);
		}, ""); 
	$("#settingForm").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },

        rules : {
            subdomain_times : {
                required : true,
                digits   : true,
                min    :1
            },
            subdomain_length : {
                required : true,
                domain_length   : true
            }
        },
        messages : {
            subdomain_times  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['domain_times_null'];?>',
                digits   : '<?php echo $lang['domain_times_digits'];?>',
                min    :'<?php echo $lang['domain_times_min'];?>'
            },
            subdomain_length  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['domain_length_tips'];?>',
                domain_length   : '<?php echo $lang['domain_length_tips'];?>'
            }
        }
	});
});
</script> 
