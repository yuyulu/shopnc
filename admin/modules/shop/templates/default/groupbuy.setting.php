<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <!-- 页面导航 -->
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['groupbuy_index_manage'];?></h3>
        <h5><?php echo $lang['groupbuy_index_manage_subhead'];?></h5>
      </div>
      <ul class="tab-base nc-row">
        <?php   foreach($output['menu'] as $menu) {  if($menu['menu_type'] == 'text') { ?>
        <li><a href="JavaScript:void(0);" class="current"><?php echo $menu['menu_name'];?></a></li>
        <?php }  else { ?>
        <li><a href="<?php echo $menu['menu_url'];?>" ><?php echo $menu['menu_name'];?></a></li>
        <?php  } }  ?>
      </ul>
    </div>
  </div>
  <form id="add_form" method="post" enctype="multipart/form-data" action="<?php echo urlAdminShop('groupbuy', 'groupbuy_setting_save');?>">
    <input type="hidden" id="submit_type" name="submit_type" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>购买单价（元/月）</label>
        </dt>
        <dd class="opt">
          <input type="text" id="groupbuy_price" name="groupbuy_price" value="<?php echo $output['setting']['groupbuy_price'];?>" class="input-txt">
          <span class="err"></span>
          <p class="notic">购买抢购活动所需费用，购买后商家可以在所购买周期内发布抢购促销活动</p>
          <p class="notic">相关费用会在店铺的账期结算中扣除</p>
          <p class="notic">若设置为0，则商家可以免费发布此种促销活动</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>抢购审核期</label>
        </dt>
        <dd class="opt">
          <input type="text" id="groupbuy_review_day" name="groupbuy_review_day" value="<?php echo $output['setting']['groupbuy_review_day'];?>" class="input-txt">
          <span class="err"></span>
          <p class="notic">抢购审核期(天)，平台预留的审核天数，商家只能发布审核期天数以后的抢购活动</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
$(document).ready(function(){
    $("#submitBtn").click(function(){
        $("#add_form").submit();
    });
    //页面输入内容验证
	$("#add_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },

        rules : {
        	groupbuy_price: {
                required : true,
                digits : true,
                min : 0
            }
        },
        messages : {
      		groupbuy_price: {
       			required : '<i class="fa fa-exclamation-circle"></i>必填',
       			digits : '<i class="fa fa-exclamation-circle"></i>数字',
                min : '<i class="fa fa-exclamation-circle"></i>最小'
            }
        }
	});
});
//submit函数
function submit_form(submit_type){
	$('#submit_type').val(submit_type);
	$('#add_form').submit();
}
</script>
