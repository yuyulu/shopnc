<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_mall_set'];?></h3>
        <h5><?php echo $lang['nc_mall_set_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <form method="post" id="settingForm" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['allowed_visitors_consult'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="guest_comment_enable" class="cb-enable <?php if($output['list_setting']['guest_comment'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_yes'];?>"><?php echo $lang['nc_yes'];?></label>
            <label for="guest_comment_disabled" class="cb-disable <?php if($output['list_setting']['guest_comment'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_no'];?>"><?php echo $lang['nc_no'];?></label>
            <input id="guest_comment_enable" name="guest_comment" <?php if($output['list_setting']['guest_comment'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="guest_comment_disabled" name="guest_comment" <?php if($output['list_setting']['guest_comment'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic"><?php echo $lang['allowed_visitors_consult_notice'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['open_checkcode'];?></dt>
        <dd class="opt">
          <input type="checkbox" value="1" name="captcha_status_goodsqa" id="captcha_status3" <?php if($output['list_setting']['captcha_status_goodsqa'] == '1'){ ?>checked="checked"<?php } ?> />
          <label for="captcha_status3"><?php echo $lang['front_goodsqa'];?></label>
        </dd>
        <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.settingForm.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
