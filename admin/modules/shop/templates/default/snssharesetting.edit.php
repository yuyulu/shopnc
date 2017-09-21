<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=sns_sharesetting" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['nc_binding_manage'];?> - 编辑“<?php echo $output['edit_arr']['name'];?>”</h3>
        <h5><?php echo $lang['nc_binding_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form method="post" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['shareset_list_appname'];?></label>
        </dt>
        <dd class="opt"><?php echo $output['edit_arr']['name'];?><span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['shareset_edit_appisuse'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="isuse_1" class="cb-enable <?php if($output['edit_arr']['isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_open'];?>"><span><?php echo $lang['nc_open'];?></span></label>
            <label for="isuse_0" class="cb-disable <?php if($output['edit_arr']['isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_close'];?>"><span><?php echo $lang['nc_close'];?></span></label>
            <input type="radio" id="isuse_1" name="isuse" value="1" <?php echo $output['edit_arr']['isuse']==1?'checked=checked':''; ?>>
            <input type="radio" id="isuse_0" name="isuse" value="0" <?php echo $output['edit_arr']['isuse']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"> </p>
        </dd>
      </dl>
      <?php if(isset($output['edit_arr']['appcode'])){?>
      <dl class="row">
        <dt class="tit">
          <label for="appcode"><?php echo $lang['shareset_edit_appcode'];?></label>
        </dt>
        <dd class="opt">
          <textarea name="appcode" rows="6" class="tarea" id="appcode"><?php echo $output['edit_arr']['appcode'];?></textarea>
          <span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <?php }?>
      <dl class="row">
        <dt class="tit">
          <label for="appid"><em>*</em><?php echo $lang['shareset_edit_appid'];?></label>
        </dt>
        <dd class="opt">
          <input id="appid" name="appid" value="<?php echo $output['edit_arr']['appid'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"><a href="<?php echo $output['edit_arr']['applyurl'];?>" target="_blank" style="color:#ffffff; font-weight:bold;"><?php echo $lang['shareset_edit_applylike'];?></a> </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="appkey"><em>*</em><?php echo $lang['shareset_edit_appkey'];?></label>
        </dt>
        <dd class="opt">
          <input id="appkey" name="appkey" value="<?php echo $output['edit_arr']['appkey'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">&nbsp; </p>
        </dd>
      </dl>
      <?php if(isset($output['edit_arr']['secretkey'])){?>
      <dl class="row">
        <dt class="tit">
          <label for="appid"><em>*</em><?php echo 'Secret Key';?></label>
        </dt>
        <dd class="opt">
          <input id="secretkey" name="secretkey" value="<?php echo $output['edit_arr']['secretkey'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <?php }?>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.settingForm.submit()"><?php echo $lang['nc_submit'];?></a> </div>
    </div>
  </form>
</div>
