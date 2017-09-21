<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['account_syn'];?></h3>
        <h5><?php echo $lang['account_syn_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <form method="post" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['qq_isuse'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="qq_isuse_1" class="cb-enable <?php if($output['list_setting']['qq_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['qq_isuse_open'];?>"><span><?php echo $lang['qq_isuse_open'];?></span></label>
            <label for="qq_isuse_0" class="cb-disable <?php if($output['list_setting']['qq_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['qq_isuse_close'];?>"><span><?php echo $lang['qq_isuse_close'];?></span></label>
            <input type="radio" id="qq_isuse_1" name="qq_isuse" value="1" <?php echo $output['list_setting']['qq_isuse']==1?'checked=checked':''; ?>>
            <input type="radio" id="qq_isuse_0" name="qq_isuse" value="0" <?php echo $output['list_setting']['qq_isuse']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"><?php echo $lang['qqSettings_notice'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="qq_appcode"><?php echo $lang['qq_appcode'];?></label>
        </dt>
        <dd class="opt">
          <textarea name="qq_appcode" rows="6" class="tarea" id="qq_appcode"><?php echo $output['list_setting']['qq_appcode'];?></textarea>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="qq_appid"><em>*</em><?php echo $lang['qq_appid'];?></label>
        </dt>
        <dd class="opt">
          <input id="qq_appid" name="qq_appid" value="<?php echo $output['list_setting']['qq_appid'];?>" class="input-txt" type="text">
          <p class="notic"><a class="ncap-btn" target="_blank" href="http://connect.qq.com"><?php echo $lang['qq_apply_link']; ?></a></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="qq_appkey"><em>*</em><?php echo $lang['qq_appkey'];?></label>
        </dt>
        <dd class="opt">
          <input id="qq_appkey" name="qq_appkey" value="<?php echo $output['list_setting']['qq_appkey'];?>" class="input-txt" type="text">
          <p class="notic">&nbsp;</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.settingForm.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
