<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>第三方账号登录</h3>
        <h5>设置使用第三方账号在手机客户端中登录</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>启用前需在QQ互联开放平台注册开发者帐号，并获得相应的appid与appkey。</li>
    </ul>
  </div>
  <form method="post" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label>QQ互联登录功能</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="app_qq_isuse_1" class="cb-enable <?php if($output['list_setting']['app_qq_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><span><?php echo $lang['open'];?></span></label>
            <label for="app_qq_isuse_0" class="cb-disable <?php if($output['list_setting']['app_qq_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><span><?php echo $lang['close'];?></span></label>
            <input type="radio" id="app_qq_isuse_1" name="app_qq_isuse" value="1" <?php echo $output['list_setting']['app_qq_isuse']==1?'checked=checked':''; ?>>
            <input type="radio" id="app_qq_isuse_0" name="app_qq_isuse" value="0" <?php echo $output['list_setting']['app_qq_isuse']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic">启用后支持使用QQ号来登录</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="app_qq_akey">应用标识</label>
        </dt>
        <dd class="opt">
          <input id="app_qq_akey" name="app_qq_akey" value="<?php echo $output['list_setting']['app_qq_akey'];?>" class="input-txt" type="text">
          <p class="notic"><a class="ncap-btn" target="_blank" href="http://connect.qq.com">立即在线申请</a></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="app_qq_skey">应用密钥</label>
        </dt>
        <dd class="opt">
          <input id="app_qq_skey" name="app_qq_skey" value="<?php echo $output['list_setting']['app_qq_skey'];?>" class="input-txt" type="text">
          <p class="notic">&nbsp;</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.settingForm.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
