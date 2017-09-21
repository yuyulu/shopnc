<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=complain&op=complain_subject_list" title="返回投诉主题列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['complain_manage_title'];?> - <?php echo $lang['nc_new'];?></h3>
        <h5><?php echo $lang['complain_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="add_form" method="post" enctype="multipart/form-data" action="index.php?act=complain&op=complain_subject_save">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="complain_subject_content"><em>*</em><?php echo $lang['complain_subject_content'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" id="complain_subject_content" name="complain_subject_content" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="complain_subject_desc"><em>*</em><?php echo $lang['complain_subject_desc'];?></label>
        </dt>
        <dd class="opt">
          <textarea name="complain_subject_desc" rows="6" class="tarea" id="complain_subject_desc"></textarea>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#add_form").valid()){
     $("#add_form").submit();
	}
	});
});
//
$(document).ready(function(){
    //添加按钮的单击事件
    $("#btn_add").click(function(){
        submit_form();
    });
    //页面输入内容验证
	$("#add_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
        	complain_subject_content: {
                required : true,
                maxlength : 50
            },
        	complain_subject_desc: {
                required : true,
                maxlength : 100
            }
        },
        messages : {
      		complain_subject_content: {
       			required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['complain_subject_content_error'];?>',
       			maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['complain_subject_content_error'];?>'
	    	},
      		complain_subject_desc: {
       			required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['complain_subject_desc_error'];?>',
       			maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['complain_subject_desc_error'];?>'
	    	}
        }
	});
});
//submit函数
function submit_form(submit_type){
	$('#add_form').submit();
}
</script> 
