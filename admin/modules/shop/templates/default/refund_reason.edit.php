<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="javascript:history.back(-1)" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['refund_manage'];?></h3>
        <h5><?php echo $lang['refund_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="post_form" method="post" name="form1" action="index.php?act=refund&op=edit_reason&reason_id=<?php echo $output['reason']['reason_id']; ?>">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="reason_info"><em>*</em>原因</label>
        </dt>
        <dd class="opt">
          <input id="reason_info" name="reason_info" value="<?php echo $output['reason']['reason_info']?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="sort"><em>*</em><?php echo $lang['nc_sort'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['reason']['sort']?>" name="sort" id="sort" class="input-txt">
          <span class="err"></span>
          <p class="notic">数字范围为0~255，数字越小越靠前 </p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){
	$("#submitBtn").click(function(){
        if($("#post_form").valid()){
            $("#post_form").submit();
    	}
	});
	$("#post_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            reason_info : {
                required : true
            },
            sort : {
                required : true,
                digits   : true
            }
        },
        messages : {
            reason_info : {
                required : "<i class='fa fa-exclamation-circle'></i>原因不能为空"
            },
            sort  : {
                required : "<i class='fa fa-exclamation-circle'></i>排序仅可以为数字",
                digits   : "<i class='fa fa-exclamation-circle'></i>排序仅可以为数字"
            }
        }
	});
});

</script> 
