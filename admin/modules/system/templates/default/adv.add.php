<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=adv&op=adv&ap_id=<?php echo $_GET['ap_id'];?>" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['adv_index_manage'];?> - 新增广告</h3>
        <h5><?php echo $lang['adv_index_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="adv_form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default" id="main_table">
      <dl class="row">
        <dt class="tit">
          <label for="adv_name"><em>*</em><?php echo $lang['adv_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="adv_name" id="adv_name" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="ap_id"><em>*</em><?php echo $lang['adv_ap_select'];?></label>
        </dt>
        <dd class="opt">
          <select name="ap_id" id="ap_id">
            <?php
				 foreach ($output['ap_list'] as $k=>$v){
				 	echo "<option value='".$v['ap_id']."' ap_type='".$v['ap_class']."' ap_width='".$v['ap_width']."' >".$v['ap_name'];
				 	if($v['ap_class'] == '1'){
				 		echo "(".$v['ap_width'].")";
				 		$word_length = $v['ap_width'];
				 	}else{
				 		echo "(".$v['ap_width']."*".$v['ap_height'].")";
				 	}
				 	echo "</option>";
				 }
				?>
          </select>
          <input type="hidden" name="aptype_hidden" id="aptype_hidden"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="adv_start_time"><em>*</em><?php echo $lang['adv_start_time'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="adv_start_time" id="adv_start_time" class="txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="adv_end_time"><em>*</em><?php echo $lang['adv_end_time'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="adv_end_time" id="adv_end_time" class="txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row" id="adv_pic">
        <dt class="tit">
          <label for="file_adv_pic"><?php echo $lang['adv_img_upload'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="type-file-box">
            <input type="file" class="type-file-file" id="file_adv_pic" name="adv_pic" size="30" hidefocus="true"  nc_type="upload_file_adv_pic" title="点击按钮选择文件并提交表单后上传生效">
            </span></div>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['adv_edit_support'];?>gif,jpg,jpeg,png</p>
        </dd>
      </dl>
      <dl class="row" id="adv_pic_url">
        <dt class="tit">
          <label for="type_adv_pic_url"> <?php echo $lang['adv_url'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="adv_pic_url" class="input-txt" id="type_adv_pic_url">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['adv_url_donotadd'];?></p>
        </dd>
      </dl>
      <dl class="row" id="adv_word">
        <dt class="tit">
          <label for="type_adv_word"><?php echo $lang['adv_word_content'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="adv_word" id="type_adv_word" class="input-txt">
          <span class="err"></span>
          <p class="notic" id="adv_word_len"></p>
        </dd>
      </dl>
      <dl class="row" id="adv_word_url">
        <dt class="tit">
          <label for="type_adv_word_url"> <?php echo $lang['adv_url'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="adv_word_url" class="input-txt" id="type_adv_word_url">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['adv_url_donotadd'];?></p>
        </dd>
      </dl>
      <dl class="row" id="adv_flash_swf">
        <dt class="tit">
          <label for="file_flash_swf"><?php echo $lang['adv_flash_upload'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="type-file-box">
            <input type="file" class="type-file-file" id="file_flash_swf" name="flash_swf" size="30" hidefocus="true"  title="点击按钮选择文件并提交表单后上传生效">
            </span></div>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['adv_please_file_swf_file'];?></p>
        </dd>
      </dl>
      <dl class="row" id="adv_flash_url">
        <dt class="tit">
          <label for="type_adv_flash_url"><?php echo $lang['adv_url'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="flash_url" class="input-txt" id="type_adv_flash_url">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['adv_url_donotadd'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('#adv_start_time').datepicker({dateFormat: 'yy-mm-dd'});
    $('#adv_end_time').datepicker({dateFormat: 'yy-mm-dd'});

    $('#adv_pic').hide();
    $('#adv_pic_url').hide();
    $('#adv_word').hide();
    $('#adv_word_url').hide();
    $('#adv_flash_swf').hide();
    $('#adv_flash_url').hide();

    $('#ap_id').change(function(){
    	var select   = document.getElementById("ap_id");
    	var ap_type  = select.item(select.selectedIndex).getAttribute("ap_type");
    	var ap_width = select.item(select.selectedIndex).getAttribute("ap_width");
        if(ap_type == '0'){
    	    $('#adv_pic').show();
            $('#adv_pic_url').show();
            $('#adv_word').hide();
            $('#adv_word_url').hide();
            $('#adv_flash_swf').hide();
            $('#adv_flash_url').hide();
        }
        if(ap_type == '1'){
        	$('#adv_word').show();
            $('#adv_word_url').show();
            $('#adv_word_len').html("<span>最多"+ap_width+"个字</span><input type='hidden' name='adv_word_len' value='"+ap_width+"'>");
            $('#adv_pic').hide();
            $('#adv_pic_url').hide();
            $('#adv_flash_swf').hide();
            $('#adv_flash_url').hide();
        }
        if(ap_type == '3'){
        	$('#adv_flash_swf').show();
            $('#adv_flash_url').show();
            $('#adv_pic').hide();
            $('#adv_pic_url').hide();
            $('#adv_word').hide();
            $('#adv_word_url').hide();
        }
        $("#aptype_hidden").val(ap_type);
    });
});
</script>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#adv_form").valid()){
     $("#adv_form").submit();
	}
	});
});
//
$(document).ready(function(){
	$('#adv_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parents('dl').find('span.err');
            error_td.append(error);
        },
        rules : {
        	adv_name : {
                required : true
            },
            aptype_hidden : {
                required : true
            },
            adv_start_time  : {
                required : true,
                date	 : false
            },
            adv_end_time  : {
            	required : true,
                date	 : false
            },
			adv_pic:{
				required :function(){ if($("#adv_pic").css('display') == 'block'){return true;}else{return false}},
				accept : 'png|jpe?g|gif'
			},
			flash_swf:{
				required :function(){ if($("#adv_flash_swf").css('display') == 'block'){return true;}else{return false}},
				accept : 'swf'
			}
        },
        messages : {
        	adv_name : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['adv_can_not_null'];?>'
            },
            aptype_hidden : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['must_select_ap_id'];?>'
            },
            adv_start_time  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['adv_start_time_can_not_null']; ?>'
            },
            adv_end_time  : {
            	required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['adv_end_time_can_not_null']; ?>'
            },
            adv_pic: {
        		required : '<i class="fa fa-exclamation-circle"></i>请上传图片',
				accept   : '<i class="fa fa-exclamation-circle"></i>图片格式错误'
			},
			flash_swf: {
        		required : '<i class="fa fa-exclamation-circle"></i>请上传Flash',
				accept   : '<i class="fa fa-exclamation-circle"></i>Flash格式错误'
			}
        }
    });
});
</script>
<script type="text/javascript">
$(function(){
	var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />"
    $(textButton).insertBefore("#file_adv_pic");
    $("#file_adv_pic").change(function(){
	$("#textfield1").val($("#file_adv_pic").val());
    });

	var textButton="<input type='text' name='textfield' id='textfield3' class='type-file-text' /><input type='button' name='button' id='button3' value='选择上传...' class='type-file-button' />"
    $(textButton).insertBefore("#file_flash_swf");
    $("#file_flash_swf").change(function(){
	$("#textfield3").val($("#file_flash_swf").val());
    });
    $('#ap_id').val('<?php echo $_GET['ap_id'];?>');
    $('#ap_id').change();
});
</script>