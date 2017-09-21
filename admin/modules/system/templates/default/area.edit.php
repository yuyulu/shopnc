<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=area&op=index" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>地区设置 - 新增</h3>
        <h5>地区新增与编辑</h5>
      </div>
    </div>
  </div>

  <form id="form" method="post" action="index.php?act=area&op=save&area_id=<?php echo $_GET['area_id']?>">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="ac_name"><em>*</em>地区名</label>
        </dt>
        <dd class="opt">
          <input type="text" name="area_name" value="<?php echo $output['info']['area_name']?>" maxlength="20" id="area_name" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>

      <dl class="row">
        <dt class="tit">
          <label for="parent_id">上级地区</label>
        </dt>
        <dd class="opt">
            <?php echo $output['info']['area_parent_name']?>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="ac_sort">所属大区域</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['info']['area_region']?>" name="area_region" id="area_region" class="input-txt">
          <span class="err"></span>
          <p class="notic">默认只有省级地区才需要填写大区域，目前全国几大区域有：华北、东北、华东、华南、华中、西南、西北、港澳台、海外。</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#form").valid()){
        $("#form").submit();
    }
	});
});
//
$(document).ready(function(){
	$("#region").nc_region({src:'db',show_deep:3});
	$('#form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            area_name : {
            	required   : true
            }
        },
        messages : {
        	area_name : {
                required : '<i class="fa fa-exclamation-circle"></i>请填写地区'
            }
        }
    });
});
</script>