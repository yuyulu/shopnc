<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=adv&op=ap_manage" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['adv_index_manage'];?> - <?php echo $lang['ap_add'];?></h3>
        <h5><?php echo $lang['adv_index_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="link_form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="ap_name"><em>*</em><?php echo $lang['ap_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="ap_name" id="ap_name" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['ap_class'];?></label>
        </dt>
        <dd class="opt">
          <select name="ap_class" id="ap_class">
            <option value="0"><?php echo $lang['adv_pic'];?></option>
            <option value="1"><?php echo $lang['adv_word'];?></option>
            <option value="3">Flash</option>
          </select>
          <p class="notic"><?php echo $lang['ap_select_showstyle'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['ap_is_use'];?></dt>
        <dd class="opt">
          <ul>
            <li>
              <input name="is_use" type="radio" value="1" checked="checked">
              <label><?php echo $lang['ap_use_s'];?></label>
            </li>
            <li>
              <input type="radio" name="is_use" value="0">
              <label><?php echo $lang['ap_not_use_s'];?></label>
            </li>
          </ul>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row" id="ap_display">
        <dt class="tit"><?php echo $lang['ap_show_style'];?></dt>
        <dd class="opt">
          <ul class="nofloat">
            <li>
              <input type="radio" name="ap_display" value="1">
              <label><?php echo $lang['ap_allow_mul_adv'];?></label>
            </li>
            <li>
              <input type="radio" name="ap_display" value="2" checked="checked">
              <label><?php echo $lang['ap_allow_one_adv'];?></label>
            </li>
          </ul>
          <p class="vatop tips"></p>
        </dd>
      </dl>
      <dl class="row" id="ap_width_media">
        <dt class="tit">
          <label for="ap_width_media_input"><em>*</em><?php echo $lang['ap_width_l'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="ap_width_media"  class="input-txt" id="ap_width_media_input">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['adv_pix'];?></p>
        </dd>
      </dl>
      <dl class="row" id="ap_width_word">
        <dt class="tit">
          <label for="ap_width_word_input"><em>*</em><?php echo $lang['ap_word_num'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="ap_width_word"  class="input-txt" id="ap_width_word_input">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['adv_byte'];?></p>
        </dd>
      </dl>
      <dl class="row" id="ap_height">
        <dt class="tit">
          <label for="ap_height_input"><em>*</em><?php echo $lang['ap_height_l'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="ap_height" class="input-txt" id="ap_height_input">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['adv_pix'];?></p>
        </dd>
      </dl>
      <dl class="row" id="default_pic">
        <dt class="tit">
          <label for="change_default_pic"><em>*</em><?php echo $lang['ap_default_pic']; ?></label>
        </dt>
        <dd class="opt ">
        <div class="type-file-box">
          <div class="input-file-show"><span class="type-file-box">
            <input type="file" class="type-file-file" id="change_default_pic" name="default_pic" size="30" hidefocus="true"  nc_type="change_default_pic" title="点击按钮选择文件并提交表单后上传生效">
            </span></div></div>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['ap_show_defaultpic_when_nothing']; ?>,<?php echo $lang['adv_edit_support'];?>gif,jpg,jpeg,png</p>
        </dd>
      </dl>
      <dl class="row" id="default_word">
        <dt class="tit">
          <label for="default_word"><em>*</em><?php echo $lang['ap_default_word']; ?></label>
        </dt>
        <dd class="opt">
          <input type="text" id="default_word" value="" name="default_word" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['ap_show_defaultword_when_nothing']; ?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
	$("#ap_width_word").hide();
	$("#default_word").hide();
	$("#ap_class").change(function(){
	if($("#ap_class").val() == '1'){
		$("#ap_height").hide();
		$("#ap_width_media").hide();
		$("#default_pic").hide();
		$("#default_word").show();
		$("#ap_width_word").show();
		$("#ap_display").show();
	}else if($("#ap_class").val() == '0'||$("#ap_class").val() == '3'){
		$("#ap_height").show();
		$("#ap_width_media").show();
		$("#default_pic").show();
		$("#default_word").hide();
		$("#ap_width_word").hide();
		$("#ap_display").show();
	}
  });
});
</script> 
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#link_form").valid()){
     $("#link_form").submit();
	}
	});
var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />"
    $(textButton).insertBefore("#change_default_pic");
    $("#change_default_pic").change(function(){
	$("#textfield1").val($("#change_default_pic").val());
    });
});
//
$(document).ready(function(){

	$('#link_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parents('dl').find('span.err');
            error_td.append(error);
        },
        rules : {
        	ap_name : {
                required : true
            },
			ap_width_media:{
				required :function(){return $("#ap_class").val()!=1;},
				digits	 :true,
				min:1
			},
			ap_height:{
				required :function(){return $("#ap_class").val()!=1;},
				digits	 :true,
				min:1
			},
			ap_width_word :{
				required :function(){return $("#ap_class").val()==1;},
				digits	 :true,
				min:1
			},
			default_word  :{
				required :function(){return $("#ap_class").val()==1;}
			},
			default_pic:{
				required :function(){ if($("#ap_class").val() == '0'||$("#ap_class").val() == '3'){return true;}else{return false}},
				accept : 'png|jpe?g|gif'
			}
        },
        messages : {
        	ap_name : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['ap_can_not_null']; ?>'
            },
            ap_width_media	:{
            	required   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['ap_input_digits_pixel']; ?>',
            	digits	:'<i class="fa fa-exclamation-circle"></i><?php echo $lang['ap_input_digits_pixel'];?>',
            	min	:'<i class="fa fa-exclamation-circle"></i><?php echo $lang['ap_input_digits_pixel'];?>'
            },
            ap_height	:{
            	required   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['ap_input_digits_pixel']; ?>',
            	digits	:'<i class="fa fa-exclamation-circle"></i><?php echo $lang['ap_input_digits_pixel'];?>',
            	min	:'<i class="fa fa-exclamation-circle"></i><?php echo $lang['ap_input_digits_pixel'];?>'
            },
            ap_width_word	:{
            	required   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['ap_input_digits_pixel']; ?>',
            	digits	:'<i class="fa fa-exclamation-circle"></i><?php echo $lang['ap_input_digits_pixel'];?>',
            	min	:'<i class="fa fa-exclamation-circle"></i><?php echo $lang['ap_input_digits_pixel'];?>'
            },
            default_word	:{
            	required   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['ap_default_word_can_not_null']; ?>'
            },
            default_pic: {
        		required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['default_pic_can_not_null'];?>',
				accept   : '<i class="fa fa-exclamation-circle"></i>图片格式错误'
			}
        }
    });
});
</script>