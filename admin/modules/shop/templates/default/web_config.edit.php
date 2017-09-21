<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=web_config&op=web_config" title="返回<?php echo '板块区';?>列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['web_config_index'];?> - <?php echo $lang['web_config_web_edit'];?>“<?php echo $output['web_array']['web_name']?>”板块</h3>
        <h5><?php echo $lang['nc_web_index_subhead'];?></h5>
      </div>
     
    </div>
  </div>
  <form id="web_form" method="post" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="web_id" value="<?php echo $output['web_array']['web_id']?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['web_config_web_name'];?></label>
        </dt>
        <dd class="opt">
          <input id="web_name" name="web_name" value="<?php echo $output['web_array']['web_name']?>" class="input-txt" type="text" maxlength="20">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['web_config_web_name_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['web_config_style_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="hidden" value="<?php echo $output['web_array']['style_name']?>" name="style_name" id="style_name">
          <ul class="home-templates-board-style">
            <li class="red"><em></em><i class="fa fa-check-circle"></i><?php echo $lang['web_config_style_red'];?></li>
            <li class="pink"><em></em><i class="fa fa-check-circle"></i><?php echo $lang['web_config_style_pink'];?></li>
            <li class="orange"><em></em><i class="fa fa-check-circle"></i><?php echo $lang['web_config_style_orange'];?></li>
            <li class="green"><em></em><i class="fa fa-check-circle"></i><?php echo $lang['web_config_style_green'];?></li>
            <li class="blue"><em></em><i class="fa fa-check-circle"></i><?php echo $lang['web_config_style_blue'];?></li>
            <li class="purple"><em></em><i class="fa fa-check-circle"></i><?php echo $lang['web_config_style_purple'];?></li>
            <li class="brown"><em></em><i class="fa fa-check-circle"></i><?php echo $lang['web_config_style_brown'];?></li>
            <li class="default"><em></em><i class="fa fa-check-circle"></i><?php echo $lang['web_config_style_default'];?></li>
          </ul>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['web_config_style_name_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['nc_sort'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['web_array']['web_sort']?>" name="web_sort" id="web_sort" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['web_config_sort_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['nc_display'];?></dt>
        <dd class="opt">
          <div class="onoff">
            <label for="show1" class="cb-enable <?php if($output['web_array']['web_show'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_yes'];?>"><?php echo $lang['nc_yes'];?></label>
            <label for="show0" class="cb-disable <?php if($output['web_array']['web_show'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_no'];?>"><?php echo $lang['nc_no'];?></label>
            <input id="show1" name="web_show" <?php if($output['web_array']['web_show'] == '1'){ ?>checked="checked"<?php } ?>  value="1" type="radio">
            <input id="show0" name="web_show" <?php if($output['web_array']['web_show'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){
	$(".home-templates-board-style .<?php echo $output['web_array']['style_name']?>").addClass("selected");
	$("#submitBtn").click(function(){
    if($("#web_form").valid()){
     $("#web_form").submit();
		}
	});
	$(".home-templates-board-style li").click(function(){
    $(".home-templates-board-style li").removeClass("selected");
    $("#style_name").val($(this).attr("class"));
    $(this).addClass("selected");
	});
	$("#web_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            web_name : {
                required : true
            },
            web_sort : {
                required : true,
                digits   : true
            }
        },
        messages : {
            web_name : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['web_config_add_name_null'];?>'
            },
            web_sort  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['web_config_sort_int'];?>',
                digits   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['web_config_sort_int'];?>'
            }
        }
	});
});

</script> 
