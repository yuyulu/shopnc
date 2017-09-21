<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['web_set'];?></h3>
        <h5><?php echo $lang['web_set_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <form method="post" id="settingForm" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit"> <?php echo $lang['open_checkcode'];?> </dt>
        <dd class="opt">
          <ul class="nofloat">
            <li>
              <input type="checkbox" value="1" name="captcha_status_login" id="captcha_status1" <?php if($output['list_setting']['captcha_status_login'] == '1'){ ?>checked="checked"<?php } ?> />
              <label for="captcha_status1"><?php echo $lang['front_login'];?></label>
            </li>
            <li>
              <input type="checkbox" value="1" name="captcha_status_register" id="captcha_status2" <?php if($output['list_setting']['captcha_status_register'] == '1'){ ?>checked="checked"<?php } ?> />
              <label for="captcha_status2"><?php echo $lang['front_regist'];?></label>
            </li>
          </ul>
          <p class="notic">选择是否开启登录、注册页面验证码功能。</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.settingForm.submit()"><?php echo $lang['nc_submit'];?></a></a></div>
    </div>
  </form>
</div>
