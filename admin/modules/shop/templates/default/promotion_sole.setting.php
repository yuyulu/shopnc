<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <!-- 页面导航 -->
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>手机专享</h3>
        <h5>商城手机专享优惠活动设置与管理</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="<?php echo urlAdminShop('promotion_sole', 'goods_list');?>">商品列表</a></li>
        <li><a href="<?php echo urlAdminShop('promotion_sole', 'sole_quota_list');?>">套餐列表</a></li>
        <li><a href="JavaScript:void(0);" class="current">设置</a></li>
      </ul>
    </div>
  </div>
  <form id="add_form" method="post" action="<?php echo urlAdminShop('promotion_sole', 'sole_setting');?>">
    <input type="hidden" id="form_submit" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="promotion_sole_price"><em>*</em>购买单价（元/月）</label>
        </dt>
        <dd class="opt">
          <input type="text" id="promotion_sole_price" name="promotion_sole_price" value="<?php echo $output['setting']['promotion_sole_price'];?>" class="input-txt">
          <span class="err"></span>
          <p class="notic">购买手机专享活动所需费用，购买后商家可以在所购买周期内设定商品在移动端支付的优惠金额。</p>
          <p class="notic">相关费用会在店铺的账期结算中扣除</p>
          <p class="notic">若设置为0，则商家可以免费发布此种促销活动</p>
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
		    promotion_sole_price: {
				required : true,
				digits : true
			},
			promotion_sole_goods_sum: {
				required : true,
				digits : true,
				min : 1
			}
		},
		messages : {
		    promotion_sole_price: {
				required : '<i class="fa fa-exclamation-circle"></i>请填写套餐价格',
				digits : '<i class="fa fa-exclamation-circle"></i>请填写套餐价格'
			},
			promotion_sole_goods_sum: {
				required : '<i class="fa fa-exclamation-circle"></i>不能为空，且不小于1的整数',
				digits : '<i class="fa fa-exclamation-circle"></i>不能为空，且不小于1的整数',
				min : '<i class="fa fa-exclamation-circle"></i>不能为空，且不小于1的整数'
			}
		}
	});
});
</script>
