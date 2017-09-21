<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_member_pointsmanage']?></h3>
        <h5><?php echo $lang['nc_member_pointsmanage_subhead']?></h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="index.php?act=points&op=pointslog"><?php echo $lang['admin_points_log_title']?></a></li>
        <li><a href="JavaScript:void(0);" class="current">规则设置</a></li>
        <li><a href="index.php?act=points&op=addpoints">积分增减</a></li>
      </ul>
    </div>
  </div>
  <form method="post" name="settingForm" id="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <div class="title">
        <h3>会员日常获取积分设定</h3>
      </div>
      <dl class="row">
        <dt class="tit"><?php echo $lang['points_number_reg']; ?></dt>
        <dd class="opt">
          <input id="points_reg" name="points_reg" value="<?php echo $output['list_setting']['points_reg'];?>" class="input-txt" type="text">
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['points_number_login'];?></dt>
        <dd class="opt">
          <input id="points_login" name="points_login" value="<?php echo $output['list_setting']['points_login'];?>" class="input-txt" type="text">
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['points_number_comments']; ?></dt>
        <dd class="opt">
          <input id="points_comments" name="points_comments" value="<?php echo $output['list_setting']['points_comments'];?>" class="input-txt" type="text">
        </dd>
      </dl>
      <div class="title">
        <h3>会员<?php echo $lang['points_number_order']; ?>时积分获取设定</h3>
      </div>
      <dl class="row">
        <dt class="tit"><?php echo $lang['points_number_orderrate'];?></dt>
        <dd class="opt">
          <input id="points_orderrate" name="points_orderrate" value="<?php echo $output['list_setting']['points_orderrate'];?>" class="input-txt" type="text">
          <p class="notic"><?php echo $lang['points_number_orderrate_tip']; ?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['points_number_ordermax']; ?></dt>
        <dd class="opt">
          <input id="points_ordermax" name="points_ordermax" value="<?php echo $output['list_setting']['points_ordermax'];?>" class="input-txt" type="text">
          <p class="notic"><?php echo $lang['points_number_ordermax_tip'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>

$(function(){
    $("#submitBtn").click(function(){
        if($("#settingForm").valid()){
            $("#settingForm").submit();
        }
    });
});
</script> 
