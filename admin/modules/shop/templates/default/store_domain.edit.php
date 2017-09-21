<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=domain&op=store_domain_list" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['nc_domain_manage'];?> - <?php echo $lang['nc_edit'];?></h3>
        <h5><?php echo $lang['nc_domain_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="store_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="store_id" value="<?php echo $output['store_array']['store_id'];?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['store_user_name'];?></label>
        </dt>
        <dd class="opt"><?php echo $output['store_array']['member_name'];?><span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label> <?php echo $lang['store_name'];?></label>
        </dt>
        <dd class="opt"><?php echo $output['store_array']['store_name'];?><span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['store_domain'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['store_array']['store_domain'];?>" id="store_domain" name="store_domain" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['store_domain_times'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['store_array']['store_domain_times'];?>" id="store_domain_times" name="store_domain_times" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a> </div>
    </div>
  </form>
</div>
<script type="text/javascript">
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#store_form").valid()){
     $("#store_form").submit();
	}
	});
	jQuery.validator.addMethod("domain", function(value, element) {
			return this.optional(element) || /^[\w\-]+$/i.test(value);
		}, "");
	$('#store_form').validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },

		rules : {
			store_domain: {
				domain: true,
        rangelength:[<?php echo $output['subdomain_length'][0];?>, <?php echo $output['subdomain_length'][1];?>]
			},
			store_domain_times: {
				digits : true,
        max:<?php echo $output['setting_config']['subdomain_times'];?>
			}
		},
		messages : {
			store_domain: {
				domain: '<?php echo $lang['store_domain_valid'];?>',
        rangelength:'<?php echo $lang['store_domain_rangelength'];?>'
			},
			store_domain_times: {
				digits: '<?php echo $lang['store_domain_times_digits'];?>',
        max:'<?php echo $lang['store_domain_times_max'];?>'
			}
		}
	});
});
</script> 
