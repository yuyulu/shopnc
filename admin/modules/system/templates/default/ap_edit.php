<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=adv&op=ap_manage" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['adv_index_manage'];?> - <?php echo $lang['ap_change'];?> “<?php echo $output['ap_list'][0]['ap_name'];?>”</h3>
        <h5><?php echo $lang['adv_index_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="link_form" enctype="multipart/form-data" method="post" name="form1">
    <input type="hidden" name="ref_url" value="<?php echo $output['ref_url'];?>" />
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <?php foreach($output['ap_list'] as $k => $v){ ?>
      <input type="hidden" name="ap_class" value="<?php echo $v['ap_class']; ?>" />
      <dl class="row">
        <dt class="tit">
          <label for="ap_name"><em>*</em><?php echo $lang['ap_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="ap_name" id="ap_name" class="input-txt" value="<?php echo $v['ap_name'];?>">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php
				 	switch ($v['ap_class']){
				 	case '0':
				        if($v['ap_display'] == '1'){
				 			$display_state1 = "checked";
				 			$display_state2 = "";
				 		}else{
				 			$display_state1 = "";
				 			$display_state2 = "checked";
				 		}
				 		echo "<dl class='row'>
								<dt class='tit'>
									<label>".$lang['ap_class']."</label>
								</dt>
								<dd class='opt'>
									".$lang['adv_pic']."
									<p class='notic'></p>
								</dd>
							</dl>";
				 		echo "<dl class='row'>
								<dt class='tit'>
									<label for='ap_width_input'><em>*</em>".$lang['ap_width_l']."</label>
								</dt>
								<dd class='opt'>
									<input type='text' value='".$v['ap_width']."' name='ap_width' class='input-txt' id='ap_width_input'>
									<span class='err'></span>
									<p class='notic'>".$lang['adv_pix']."</p>
								</dd>
							</dl>";
				 		echo "<dl class='row'>
								<dt class='tit'>
									<label for='ap_height_input'><em>*</em>".$lang['ap_height_l']."</label>
								</dt>
								<dd class='opt'>
									<input type='text' value='".$v['ap_height']."' name='ap_height' id='ap_height_input' class='input-txt'>
									<span class='err'></span>
									<p class='notic'>".$lang['adv_pix']."</p>
								</dd>
							</dl>";
				 		echo "<dl class='row'>
								<dt class='tit'>
									<label>".$lang['ap_show_style']."</label>
								</dt>
								<dd class='opt'>
									<ul class='nofloat'>
										<li><input type='radio' name='ap_display' id='ap_display_1' value='1' ".$display_state1."><label for='ap_display_1'>".$lang['ap_allow_mul_adv']."</label></li>
										<li><input type='radio' name='ap_display' id='ap_display_2' value='2' ".$display_state2."><label for='ap_display_2'>".$lang['ap_allow_one_adv']."</label></li>
									</ul>
									<p class='notic'></p>
								</dd>
						</dl>";
				 		echo "<dl class='row' id='adv_pic'>
								<dt class='tit'>
									<label>".$lang['ap_default_pic_upload']."</label>
								</dt>
								<dd class='opt'>
								<div class='input-file-show'><span class='show'> <a class='nyroModal' rel='gal' href='".UPLOAD_SITE_URL."/".ATTACH_ADV."/".$v['default_content']."'> <i class='fa fa-picture-o' onMouseOver=\"toolTip('<img src=".UPLOAD_SITE_URL."/".ATTACH_ADV."/".$v['default_content'].">')\" onMouseOut='toolTip()'></i></a></span><span class='type-file-box'>
            <input type='file' class='type-file-file' id='change_default_pic' name='default_pic' size='30' hidefocus='true'  nc_type='change_default_pic' title='点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效'>
            </span></div>
          <span class='err'></span>
          <p class='notic'>".$lang['ap_show_defaultpic_when_nothing'].",".$lang['adv_edit_support']."gif,jpg,jpeg,png</p>
								</dd>
							</dl>";
				 		break;
				 		case '1':
				        if($v['ap_display'] == '1'){
				 			$display_state1 = "checked";
				 			$display_state2 = "";
				 		}else{
				 			$display_state1 = "";
				 			$display_state2 = "checked";
				 		}
				 		echo "<dl class='row'>
								<dt class='tit'>
									<label>".$lang['ap_class']."</label>
								</dt>
								<dd class='opt'>
									".$lang['adv_word']."
									<p class='notic'></p>
								</dd>
							</dl> ";
				 		echo "<dl class='row'>
								<dt class='tit'>
									<label for='ap_width_input'><em>*</em>".$lang['ap_word_num']."</label>
								</dt>
								<dd class='opt'>
									<input type='text' value='".$v['ap_width']."' name='ap_width' id='ap_width_input' class='input-txt'>
									<span class='err'></span>
									<p class='notic'>".$lang['adv_byte']."</p>
								</dd>
							</dl> ";
				 		echo "<dl class='row'>
								<dt class='tit'>
									<label>".$lang['ap_show_style']."</label>
								</dt>
								<dd class='opt'>
									<ul class='nofloat'>
										<li><input type='radio' name='ap_display' value='1' id='ap_display_1' ".$display_state1."><label for='ap_display_1'>".$lang['ap_allow_mul_adv']."</label></li>
										<li><input type='radio' name='ap_display' id='ap_display_2' value='2' ".$display_state2."><label for='ap_display_2'>".$lang['ap_allow_one_adv']."</label></li>
									</ul>
								</dd>
							</dl> ";
				 		echo "<dl class='row'>
								<dt class='tit'>
									<label for='default_word'><em>*</em>".$lang['ap_default_word']."</label>
								</dt>
								<dd class='opt'>
									<input type='text' value='".$v['default_content']."' name='default_word' class='input-txt' id='default_word'>
									<span class='err'></span>
									<p class='notic'></p>
								</dd>
							</dl>";
				 		break;
				 		case '3':
				 		if($v['ap_display'] == '1'){
				 			$display_state1 = "checked";
				 			$display_state2 = "";
				 		}else{
				 			$display_state1 = "";
				 			$display_state2 = "checked";
				 		}
				 		echo "<dl class='row'>
								<dt class='tit'>
									<label>".$lang['ap_class']."</label>
								</dt>
								<dd class='opt'>
									Flash
									<p class='notic'></p>
								</dd>
							</dl> ";
				 		echo "<dl class='row'>
								<dt class='tit'>
									<label for='ap_width'><em>*</em>".$lang['ap_width_l']."</label>
								</dt>
								<dd class='opt'>
									<input type='text' value='".$v['ap_width']."' name='ap_width' class='input-txt' id='ap_width'>
									<span class='err'></span>
									<p class='notic'>".$lang['adv_pix']."</p>
								</dd>
							</dl> ";
				 		echo "<dl class='row'>
								<dt class='tit'>
									<label for='ap_height_input'><em>*</em>".$lang['ap_height_l']."</label>
								</dt>
								<dd class='opt'>
									<input type='text' value='".$v['ap_height']."' name='ap_height' id='ap_height_input' class='input-txt'>
									<span class='err'></span>
									<p class='notic'>".$lang['adv_pix']."</p>
								</dd>
							</dl>";
				 		echo "<dl class='row'>
								<dt class='tit'>
									<label>".$lang['ap_show_style']."</label>
								</dt>
								<dd class='opt'>
									<ul class='nofloat'>
										<li><input type='radio' id='ap_display_1' name='ap_display' value='1' ".$display_state1."><label for='ap_display_1'>".$lang['ap_allow_mul_adv']."</label></li>
										<li><input type='radio' id='ap_display_2' name='ap_display' value='2' ".$display_state2."><label for='ap_display_2'>".$lang['ap_allow_one_adv']."</label></li></ul>						
									<p class='notic'></p>
								</dd>
							</dl> ";
				 		echo "<dl id='adv_pic' >
								<dt class='tit'>
									<label>".$lang['ap_default_pic_upload']."</label>
								</dt>
								<dd class='opt'>
									<div class='input-file-show'><span class='show'> <a class='nyroModal' rel='gal' href='".UPLOAD_SITE_URL."/".ATTACH_ADV."/".$v['default_content']."'> <i class='fa fa-picture-o' onMouseOver=\"toolTip('<img src=".UPLOAD_SITE_URL."/".ATTACH_ADV."/".$v['default_content'].">')\" onMouseOut='toolTip()'></i></a></span><span class='type-file-box'>
            <input type='file' class='type-file-file' id='change_default_pic' name='default_pic' size='30' hidefocus='true'  nc_type='change_default_pic' title='点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效'>
            </span></div>
          <span class='err'></span>
          <p class='notic'>".$lang['ap_show_defaultpic_when_nothing'].",".$lang['adv_edit_support']."gif,jpg,jpeg,png</p>
								</dd>
							</dl>";
				 		break;
				 }
				?>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['ap_is_use'];?></label>
        </dt>
        <dd class="opt">
          <ul>
            <li>
              <input type="radio" id="is_use_1" name="is_use" value="1" <?php if($v['is_use'] == '1'){echo "checked";}?>>
              <label for="is_use_1"><?php echo $lang['ap_use_s'];?></label>
            </li>
            <li>
              <input type="radio" id="is_use_0" name="is_use" value="0" <?php if($v['is_use'] == '0'){echo "checked";}?>>
              <label for="is_use_0"><?php echo $lang['ap_not_use_s'];?></label>
            </li>
          </ul>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php }?>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn" onclick="document.form1.submit()"><?php echo $lang['adv_change'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />"
	$(textButton).insertBefore("#change_default_pic");
	$("#change_default_pic").change(function(){
	$("#textfield1").val($("#change_default_pic").val());
	});
});
</script>

