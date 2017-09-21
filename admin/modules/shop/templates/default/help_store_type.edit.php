<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=help_store&op=help_type" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>店铺帮助 - <?php echo $lang['nc_edit'];?>类型”<?php echo $output['type']['type_name']?>“</h3>
        <h5>商品店铺帮助类型与文章管理</h5>
      </div>
    </div>
  </div>
  <form id="post_form" method="post" name="form1" action="index.php?act=help_store&op=edit_type&type_id=<?php echo $output['type']['type_id']; ?>">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="type_name"><em>*</em>类型名称</label>
        </dt>
        <dd class="opt">
          <input id="type_name" name="type_name" value="<?php echo $output['type']['type_name']?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="type_sort"><em>*</em><?php echo $lang['nc_sort'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['type']['type_sort']?>" name="type_sort" id="type_sort" class="input-txt">
          <span class="err"></span>
          <p class="notic">数字范围为0~255，数字越小越靠前</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['nc_display'];?></dt>
        <dd class="opt">
          <div class="onoff">
            <label for="show1" class="cb-enable <?php if($output['type']['help_show'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_yes'];?>"><?php echo $lang['nc_yes'];?></label>
            <label for="show0" class="cb-disable <?php if($output['type']['help_show'] != '1'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_no'];?>"><?php echo $lang['nc_no'];?></label>
            <input id="show1" name="help_show" <?php if($output['type']['help_show'] == '1'){ ?>checked="checked"<?php } ?>  value="1" type="radio">
            <input id="show0" name="help_show <?php if($output['type']['help_show'] != '1'){ ?>checked="checked"<?php } ?>" value="0" type="radio">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"> <a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
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
            type_name : {
                required : true
            },
            type_sort : {
                required : true,
                digits   : true
            }
        },
        messages : {
            type_name : {
                required : "<i class='fa fa-exclamation-circle'></i>类型名称不能为空"
            },
            type_sort  : {
                required : "<i class='fa fa-exclamation-circle'></i>排序仅可以为数字",
                digits   : "<i class='fa fa-exclamation-circle'></i>排序仅可以为数字"
            }
        }
	});
});

</script> 
