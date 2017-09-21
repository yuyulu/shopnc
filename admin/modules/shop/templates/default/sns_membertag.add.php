<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=sns_member" title="返回会员标签列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['sns_member_tag'];?> - 新增会员标签</h3>
        <h5><?php echo $lang['sns_member_tag_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="membertag_form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="membertag_name"><em>*</em><?php echo $lang['sns_member_tag_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="membertag_name" id="membertag_name" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['sns_member_tag_name_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['nc_recommend']?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="mtag_recommend1" class="cb-enable"><?php echo $lang['nc_yes'];?></label>
            <label for="mtag_recommend0" class="cb-disable selected"><?php echo $lang['nc_no'];?></label>
            <input id="mtag_recommend1" name="membertag_recommend" value="1" type="radio">
            <input id="mtag_recommend0" name="membertag_recommend" checked="checked" value="0" type="radio">
          </div>
          <p class="notic"><?php echo $lang['sns_member_tag_recommend_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="mtag_sort"><?php echo $lang['nc_sort'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="0" name="membertag_sort" id="mtag_sort" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['sns_member_tag_sort_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['sns_member_tag_desc'];?></label>
        </dt>
        <dd class="opt">
          <textarea class="tarea" rows="6" name="membertag_desc" id="membertag_desc"></textarea>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['sns_member_tag_desc_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['sns_member_tag_img'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="type-file-box">
            <input name="membertag_img" type="file" class="type-file-file" id="membertag_img" size="30" hidefocus="true">
            </span></div>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['sns_member_tag_img_tips'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
	var textButton1="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />"
	$(textButton1).insertBefore("#membertag_img");
	$("#membertag_img").change(function(){$("#textfield1").val($("#membertag_img").val());});

	$("#submitBtn").click(function(){
		if($("#membertag_form").valid()){
			$("#membertag_form").submit();
		}
	});
	
	$('#membertag_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },

        rules : {
        	membertag_name : {
                required : true,
                maxlength : 20
            },
            membertag_sort : {
            	digits   : true
            },
            membertag_desc :{
				maxlength : 50
            }
        },
        messages : {
        	membertag_name : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['sns_member_tag_name_null_error'];?>',
                maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['sns_member_tag_maxlength_error'];?>'
            },
            membertag_sort : {
            	digits   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['sns_member_tag_sort_error'];?>'
            },
            membertag_desc :{
				maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['sns_member_tag_desc_max_error'];?>'
            }
        }
    });
});
</script>