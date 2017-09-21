<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_email_set'];?></h3>
        <h5><?php echo $lang['nc_email_set_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>填写邮件服务器相关参数，并点击“测试”按钮进行效验，保存后生效。</li>
      <li>如使用第三方提供的邮件服务器，请认真阅读服务商提供的相关帮助文档。</li>
    </ul>
  </div>
  <form method="post" id="form_email" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit"><?php echo $lang['smtp_server'];?></dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['list_setting']['email_host'];?>" name="email_host" id="email_host" class="input-txt">
          <p class="notic"><?php echo $lang['set_smtp_server_address'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['smtp_port'];?></dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['list_setting']['email_port'];?>" name="email_port" id="email_port" class="input-txt">
          <p class="notic"><?php echo $lang['set_smtp_port'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['sender_mail_address'];?></dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['list_setting']['email_addr'];?>" name="email_addr" id="email_addr" class="input-txt">
          <p class="notic"><?php echo $lang['if_smtp_authentication'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['smtp_user_name'];?></dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['list_setting']['email_id'];?>" name="email_id" id="email_id" class="input-txt">
          <p class="notic"><?php echo $lang['smtp_user_name_tip'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['smtp_user_pwd'];?></dt>
        <dd class="opt">
          <input type="password" value="<?php echo $output['list_setting']['email_pass'];?>" name="email_pass" id="email_pass" class="input-txt">
          <p class="notic"><?php echo $lang['smtp_user_pwd_tip'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['test_mail_address'];?></dt>
        <dd class="opt">
          <input type="text" value="" name="email_test" id="email_test" class="input-txt">
          <input type="button" value="<?php echo $lang['test'];?>" name="send_test_email" class="input-btn" id="send_test_email">
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.settingForm.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
$(document).ready(function(){
	$('#send_test_email').click(function(){
		$.ajax({
			type:'POST',
			url:'index.php',
			data:'act=message&op=email_testing&email_host='+$('#email_host').val()+'&email_port='+$('#email_port').val()+'&email_addr='+$('#email_addr').val()+'&email_id='+$('#email_id').val()+'&email_pass='+$('#email_pass').val()+'&email_test='+$('#email_test').val(),
			error:function(){
					alert('<?php echo $lang['test_email_send_fail'];?>');
				},
			success:function(html){
				alert(html.msg);
			},
			dataType:'json'
		});
	});
});
</script>