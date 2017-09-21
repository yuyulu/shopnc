<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=circle_class&op=class_list" title="返回分类列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['nc_circle_classmanage'];?> - <?php echo $lang['nc_edit'];?>圈子分类“<?php echo $output['class_info']['class_name'];?>”</h3>
        <h5><?php echo $lang['nc_circle_classmanage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="class_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="class_id" value="<?php echo $output['class_info']['class_id'];?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="class_name"><em>*</em><?php echo $lang['circle_class_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="class_name" id="class_name" class="input-txt" value="<?php echo $output['class_info']['class_name'];?>" />
          <span class="err"></span>
          <p class="notic"><?php echo $lang['circle_class_name_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="recommend"><?php echo $lang['circle_class_is_recommend'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="recommend1" class="cb-enable <?php if($output['class_info']['is_recommend'] == '1'){?>selected<?php }?>" ><?php echo $lang['nc_yes'];?></label>
            <label for="recommend0" class="cb-disable <?php if($output['class_info']['is_recommend'] == '0'){?>selected<?php }?>" ><?php echo $lang['nc_no'];?></label>
            <input id="recommend1" name="recommend" <?php if($output['class_info']['is_recommend'] == '1'){?>checked="checked"<?php }?> value="1" type="radio">
            <input id="recommend0" name="recommend" <?php if($output['class_info']['is_recommend'] == '0'){?>checked="checked"<?php }?> value="0" type="radio">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="status"><?php echo $lang['circle_class_status'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="site_status1" class="cb-enable <?php if($output['class_info']['class_status'] == '1'){?>selected<?php }?>" ><?php echo $lang['open'];?></label>
            <label for="site_status0" class="cb-disable <?php if($output['class_info']['class_status'] == '0'){?>selected<?php }?>" ><?php echo $lang['close'];?></label>
            <input id="site_status1" name="status" <?php if($output['class_info']['class_status'] == '1'){?>checked="checked"<?php }?> value="1" type="radio">
            <input id="site_status0" name="status" <?php if($output['class_info']['class_status'] == '0'){?>checked="checked"<?php }?> value="0" type="radio">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="class_sort"><?php echo $lang['nc_sort'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="class_sort" id="class_sort" class="input-txt" value="<?php echo $output['class_info']['class_sort'];?>">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['circle_class_sort_tips'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.edit.js" charset="utf-8"></script> 
<script>
//按钮先执行验证再提交表单
$(function(){
	$("#submitBtn").click(function(){
		if($("#class_form").valid()){
			$("#class_form").submit();
		}
	});
	$('#class_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
        	class_name : {
        		required : true,
        		maxlength : 8
        	},
        	class_sort : {
            	digits : true,
            	max : 255
            }
        },
        messages : {
        	class_name : {
        		required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['circle_class_name_not_null'];?>',
        		maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['circle_class_name_maxlength'];?>'
        	},
        	class_sort : {
            	digits : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['circle_class_sort_is_number'];?>',
            	max : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['circle_class_sort_max'];?>'
            }
        }
    });
});
</script> 
