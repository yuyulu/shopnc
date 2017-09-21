<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['complain_manage_title'];?></h3>
        <h5><?php echo $lang['complain_manage_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <form id="add_form" method="post" enctype="multipart/form-data" action="index.php?act=complain&op=complain_setting_save">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="complain_time_limit"><?php echo $lang['complain_time_limit'];?></label>
        </dt>
        <dd class="opt">
          <input name="complain_time_limit" id="complain_time_limit" value="<?php echo intval($output['list_setting']['complain_time_limit'])/86400;?>" type="text" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['complain_time_limit_desc'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="btn_add"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
//
$(document).ready(function(){
    //添加按钮的单击事件
    $("#btn_add").click(function(){
        $("#add_form").submit();
    });
    //页面输入内容验证
	$("#add_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
        	complain_time_limit: {
                required : true,
                digits : true
            }
        },
        messages : {
      		complain_time_limit: {
       			required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['complain_time_limit_error'];?>',
                digits : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['complain_time_limit_error'];?>'
	    	}
        }
	});
});
</script> 
