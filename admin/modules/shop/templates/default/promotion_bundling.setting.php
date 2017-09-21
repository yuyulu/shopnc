<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <!-- 页面导航 -->
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_promotion_bundling'];?></h3>
        <h5><?php echo $lang['nc_promotion_bundling_subhead'];?></h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="index.php?act=promotion_bundling&op=bundling_list"><?php echo $lang['bundling_list'];?></a></li>
        <li><a href="index.php?act=promotion_bundling&op=bundling_quota"><?php echo $lang['bundling_quota'];?></a></li>
        <li><a href="JavaScript:void(0);" class="current"><?php echo $lang['bundling_setting'];?></a></li>
      </ul>
    </div>
  </div>
  <form id="add_form" method="post" action="index.php?act=promotion_bundling&op=bundling_setting">
    <input type="hidden" id="form_submit" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="promotion_bundling_price"><em>*</em>购买单价（元/月）</label>
        </dt>
        <dd class="opt">
          <input type="text" id="promotion_bundling_price" name="promotion_bundling_price" value="<?php echo $output['setting']['promotion_bundling_price'];?>" class="input-txt">
          <span class="err"></span>
          <p class="notic">购买优惠套装活动所需费用，购买后商家可以在所购买周期内发布优惠套装促销活动</p>
          <p class="notic">相关费用会在店铺的账期结算中扣除</p>
          <p class="notic">若设置为0，则商家可以免费发布此种促销活动</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="promotion_bundling_sum"><em>*</em><?php echo $lang['bundling_sum'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" id="promotion_bundling_sum" name="promotion_bundling_sum" value="<?php echo $output['setting']['promotion_bundling_sum'];?>" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['bundling_sum_prompt'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="promotion_bundling_goods_sum"><em>*</em><?php echo $lang['bundling_goods_sum'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" id="promotion_bundling_goods_sum" name="promotion_bundling_goods_sum" value="<?php echo $output['setting']['promotion_bundling_goods_sum'];?>" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['bundling_goods_sum_prompt'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
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
			promotion_bundling_price: {
				required : true,
				digits : true,
				min : 0
			},
			promotion_bundling_sum: {
				required : true,
				digits : true
			},
			promotion_bundling_goods_sum: {
				required : true,
				digits : true,
				min : 1,
				max : 5
			}
		},
		messages : {
			promotion_bundling_price: {
				required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['bundling_price_error'];?>',
				digits : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['bundling_price_error'];?>',
				min : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['bundling_price_error'];?>'
			},
			promotion_bundling_sum: {
				required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['bundling_sum_error'];?>',
				digits : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['bundling_sum_error'];?>'
			},
			promotion_bundling_goods_sum: {
				required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['bundling_goods_sum_error'];?>',
				digits : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['bundling_goods_sum_error'];?>',
				min : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['bundling_goods_sum_error'];?>',
				max : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['bundling_goods_sum_error'];?>'
			}
		}
	});
});
</script>