<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>经验值管理</h3>
        <h5>商城会员经验值设定及获取日志</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="index.php?act=member_exp&op=index" >经验值明细</a></li>
        <li><a href="JavaScript:void(0);" class="current">规则设置</a></li>
        <li><a href="index.php?act=member_exp&op=member_grade">等级设定</a></li>
      </ul>
    </div>
  </div>
  <form method="post" name="settingForm" id="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <div class="title">
        <h3>会员日常获取经验值设定</h3>
      </div>
      <dl class="row">
        <dt class="tit">会员每天第一次登录</dt>
        <dd class="opt">
          <input id="exp_login" name="exp_login" value="<?php echo $output['list_setting']['exppoints_rule']['exp_login'];?>" class="input-txt" type="text" >
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">订单商品评论</dt>
        <dd class="opt">
          <input id="exp_comments" name="exp_comments" value="<?php echo $output['list_setting']['exppoints_rule']['exp_comments'];?>" class="input-txt" type="text">
        </dd>
      </dl>
      <div class="title">
        <h3>会员购物获取经验值设定</h3>
      </div>
      <dl class="row">
        <dt class="tit">消费额与赠送经验值比例</dt>
        <dd class="opt">
          <input id="exp_orderrate" name="exp_orderrate" value="<?php echo $output['list_setting']['exppoints_rule']['exp_orderrate'];?>" class="input-txt" type="text">
          <p class="notic">该值为大于0的数， 例:设置为10，表明消费10单位货币赠送1经验值</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">每订单最多赠送经验值</dt>
        <dd class="opt">
          <input id="exp_ordermax" name="exp_ordermax" value="<?php echo $output['list_setting']['exppoints_rule']['exp_ordermax'];?>" class="input-txt" type="text">
          <p class="notic"> 该值为大于等于0的数，填写为0表明不限制最多经验值，例:设置为100，表明每订单赠送经验值最多为100经验值</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
$(function(){
	$("#submitBtn").click(function(){
		$("#settingForm").submit();
	});
});
</script> 
