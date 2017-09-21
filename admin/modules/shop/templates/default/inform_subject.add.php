<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="javascript:history.back(-1)" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['inform_manage_title'];?></h3>
        <h5><?php echo $lang['inform_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <br/>
  <form id="add_form" method="post" action="index.php?act=inform&op=inform_subject_save">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit"><?php echo $lang['inform_type'];?></dt>
        <dd class="opt">
          <ul class="nofloat">
            <?php foreach($output['list'] as $inform_type) {?>
            <li>
              <p>
                <input type='radio' name="inform_subject_type" id="<?php echo $inform_type['inform_type_id'].','.$inform_type['inform_type_name'];?>" value ="<?php echo $inform_type['inform_type_id'].','.$inform_type['inform_type_name'];?>">
                <label for="<?php echo $inform_type['inform_type_id'].','.$inform_type['inform_type_name'];?>"><?php echo $inform_type['inform_type_name'];?>:</label>
                </input>
                &nbsp;&nbsp;<span style="color:green" ><?php echo $inform_type['inform_type_desc'];?></span></p>
            </li>
            <?php } ?>
          </ul>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="inform_subject_content"><em>*</em><?php echo $lang['inform_subject'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" id="inform_subject_content" name="inform_subject_content" class="input-txt">
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
    //默认选中第一个radio
    $(":radio").first().attr("checked",true);
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
        	inform_subject_content: {
                required : true,
                maxlength : 100
            }
        },
        messages : {
      		inform_subject_content: {
       			required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['inform_subject_add_null'];?>',
       			maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['inform_subject_add_null'];?>'
	    	}
        }
	});
});
//submit函数
function submit_form(submit_type){
	$('#add_form').submit();
}
</script>