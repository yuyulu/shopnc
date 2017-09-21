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
          <label><?php echo $lang['ucenter_integration'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="ucenter_status_1" class="cb-enable <?php if($output['list_setting']['ucenter_status'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['qq_isuse_open'];?>"><span><?php echo $lang['qq_isuse_open'];?></span></label>
            <label for="ucenter_status_0" class="cb-disable <?php if($output['list_setting']['ucenter_status'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['qq_isuse_close'];?>"><span><?php echo $lang['qq_isuse_close'];?></span></label>
            <input type="radio" id="ucenter_status_1" name="ucenter_status" value="1" <?php echo $output['list_setting']['ucenter_status']==1?'checked=checked':''; ?>>
            <input type="radio" id="ucenter_status_0" name="ucenter_status" value="0" <?php echo $output['list_setting']['ucenter_status']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"><a href="JavaScript:void(0);" onclick="javascript:if(confirm('<?php echo $lang['user_info_clear'];?>'))window.location ='index.php?act=account&op=member_clear';" class="btns tooltip" title="<?php echo $lang['click_clear'];?>"><span><?php echo $lang['click_clear'];?></span></a> &nbsp;<?php echo $lang['first_integration'];?>&nbsp; <a href="JavaScript:void(0);" onclick="javascript:window.location ='index.php?act=db&op=db';" class="btns tooltip" title="<?php echo $lang['click_bak'];?>"><span><?php echo $lang['click_bak'];?></span></a><br/><a href="http://www.33hao.com/thread-677-1-1.html" target="_blank"><?php echo $lang['ucenter_help_url']; ?></a></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="ucenter_app_id"><?php echo $lang['ucenter_type'];?></label>
        </dt>
        <dd class="opt">
                      <li>
                <input id="ucenter_type_dx" name="ucenter_type" <?php if($output['list_setting']['ucenter_type'] != 'phpwind'){ ?>checked="checked"<?php } ?> value="discuz" type="radio">
                <label for="ucenter_type_dx"><?php echo $lang['ucenter_uc_discuz'];?></label>
              </li>
              <li>
                <input id="ucenter_type_pw" name="ucenter_type" <?php if($output['list_setting']['ucenter_type'] == 'phpwind'){ ?>checked="checked"<?php } ?> value="phpwind" type="radio">
                <label for="ucenter_type_pw">phpwind</label>
              </li>
        </dd>
      </dl>
            <dl class="row">
        <dt class="tit">
          <label for="ucenter_app_id"><em>*</em><?php echo $lang['qq_appid'];?></label>
        </dt>
        <dd class="opt">
          <input id="ucenter_app_id" name="ucenter_app_id" value="<?php echo $output['list_setting']['ucenter_app_id'];?>" class="input-txt" type="text">
          <p class="notic"><?php echo $lang['ucenter_application_id_tips']?></p>
        </dd>
      </dl>
       <dl class="row">
        <dt class="tit">
          <label for="ucenter_url"><em>*</em><?php echo $lang['ucenter_address'];?></label>
        </dt>
        <dd class="opt">
          <input id="ucenter_url" name="ucenter_url" value="<?php echo $output['list_setting']['ucenter_url'];?>" class="input-txt" type="text">
          <p class="notic"><?php echo $lang['ucenter_address_tips']?></p>
        </dd>
      </dl>
             <dl class="row">
        <dt class="tit">
          <label for="ucenter_app_key"><em>*</em><?php echo $lang['ucenter_key'];?></label>
        </dt>
        <dd class="opt">
          <input id="ucenter_app_key" name="ucenter_app_key" value="<?php echo $output['list_setting']['ucenter_app_key'];?>" class="input-txt" type="text">
          <p class="notic"></p>
        </dd>
      </dl>
             <dl class="row">
        <dt class="tit">
          <label for="ucenter_ip"><em>*</em><?php echo $lang['ucenter_ip'];?></label>
        </dt>
        <dd class="opt">
          <input id="ucenter_ip" name="ucenter_ip" value="<?php echo $output['list_setting']['ucenter_ip'];?>" class="input-txt" type="text">
          <p class="notic"><?php echo $lang['ucenter_ip_tips']?></p>
        </dd>
      </dl>
                   <dl class="row">
        <dt class="tit">
          <label for="ucenter_mysql_server"><em>*</em><?php echo $lang['ucenter_mysql_server'];?></label>
        </dt>
        <dd class="opt">
          <input id="ucenter_mysql_server" name="ucenter_mysql_server" value="<?php echo $output['list_setting']['ucenter_mysql_server'];?>" class="input-txt" type="text">
          <p class="notic"><?php echo $lang['ucenter_mysql_server_tips']?></p>
        </dd>
      </dl>
                         <dl class="row">
        <dt class="tit">
          <label for="ucenter_mysql_username"><em>*</em><?php echo $lang['ucenter_mysql_username'];?></label>
        </dt>
        <dd class="opt">
          <input id="ucenter_mysql_username" name="ucenter_mysql_username" value="<?php echo $output['list_setting']['ucenter_mysql_username'];?>" class="input-txt" type="text">
          <p class="notic"><?php echo $lang['ucenter_mysql_username_tips']?></p>
        </dd>
      </dl>
                               <dl class="row">
        <dt class="tit">
          <label for="ucenter_mysql_passwd"><em>*</em><?php echo $lang['ucenter_mysql_passwd'];?></label>
        </dt>
        <dd class="opt">
          <input id="ucenter_mysql_passwd" name="ucenter_mysql_passwd" value="<?php echo $output['list_setting']['ucenter_mysql_passwd'];?>" class="input-txt" type="text">
          <p class="notic"><?php echo $lang['ucenter_mysql_passwd_tips']?></p>
        </dd>
      </dl>
                                       <dl class="row">
        <dt class="tit">
          <label for="ucenter_mysql_name"><em>*</em><?php echo $lang['ucenter_mysql_name'];?></label>
        </dt>
        <dd class="opt">
          <input id="ucenter_mysql_name" name="ucenter_mysql_name" value="<?php echo $output['list_setting']['ucenter_mysql_name'];?>" class="input-txt" type="text">
          <p class="notic"><?php echo $lang['ucenter_mysql_name_tips']?></p>
        </dd>
      </dl>
                                          <dl class="row">
        <dt class="tit">
          <label for="ucenter_mysql_pre"><em>*</em><?php echo $lang['ucenter_mysql_pre'];?></label>
        </dt>
        <dd class="opt">
          <input id="ucenter_mysql_pre" name="ucenter_mysql_pre" value="<?php echo $output['list_setting']['ucenter_mysql_pre'];?>" class="input-txt" type="text">
          <p class="notic"><?php echo $lang['ucenter_mysql_pre_tips']?></p>
        </dd>
      </dl>
     <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.settingForm.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script language="javascript">
function change_type(type) {
	if(type == 1) {
		$(".db_type").css("display","none");
	} else {
		$(".db_type").css("display","");
	}
}
<?php
if($output['list_setting']['ucenter_connect_type'] == '1') {
?>
change_type(1);
<?php
}
?>
</script>