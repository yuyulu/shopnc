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
  <?php if ($output['is_exist']){?>
  <form method="post" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit"><?php echo $lang['sina_isuse'];?></dt>
        <dd class="opt">
          <div class="onoff">
            <label for="sina_isuse_1" class="cb-enable <?php if($output['list_setting']['sina_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['sina_isuse_open'];?>"><span><?php echo $lang['sina_isuse_open'];?></span></label>
            <label for="sina_isuse_0" class="cb-disable <?php if($output['list_setting']['sina_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['sina_isuse_close'];?>"><span><?php echo $lang['sina_isuse_close'];?></span></label>
            <input type="radio" id="sina_isuse_1" name="sina_isuse" value="1" <?php echo $output['list_setting']['sina_isuse']==1?'checked=checked':''; ?>>
            <input type="radio" id="sina_isuse_0" name="sina_isuse" value="0" <?php echo $output['list_setting']['sina_isuse']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic">开启后可使用新浪微博账号登录系统前台。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="sina_appcode"><?php echo $lang['sina_appcode'];?></label>
        </dt>
        <dd class="opt">
          <textarea name="sina_appcode" rows="6" class="tarea" id="sina_appcode"><?php echo $output['list_setting']['sina_appcode'];?></textarea>
        </dd>
        <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="sina_wb_akey"><em>*</em><?php echo $lang['sina_wb_akey'];?></label>
        </dt>
        <dd class="opt">
          <input id="sina_wb_akey" name="sina_wb_akey" value="<?php echo $output['list_setting']['sina_wb_akey'];?>" class="input-txt" type="text">
          <p class="notic"><a class="ncap-btn" target="_blank" href="http://open.weibo.com/developers"><?php echo $lang['sina_apply_link']; ?></a></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="sina_wb_skey"><em>*</em><?php echo $lang['sina_wb_skey'];?></label>
        </dt>
        <dd class="opt">
          <input id="sina_wb_skey" name="sina_wb_skey" value="<?php echo $output['list_setting']['sina_wb_skey'];?>" class="input-txt" type="text">
          <p class="notic">&nbsp;</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.settingForm.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
  <?php }else{ ?>
  <?php echo $lang['sina_function_fail_tip']; ?>
  <?php }?>
</div>
