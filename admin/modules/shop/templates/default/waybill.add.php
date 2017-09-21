<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page"> 
  <!-- 页面导航 -->
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=waybill&op=waybill_list" title="返回运单模板列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>运单模板 - <?php echo $output['waybill_info']?'编辑 “' . $output['waybill_info']['waybill_name'] .'”':'添加';?>运单模板</h3>
        <h5>预设供商家选择的运单快递模板</h5>
      </div>
    </div>
  </div>
  <form id="add_form" method="post" action="<?php echo urlAdminShop('waybill', 'waybill_save');?>" enctype="multipart/form-data">
    <?php if($output['waybill_info']) { ?>
    <input type="hidden" name="waybill_id" value="<?php echo $output['waybill_info']['waybill_id'];?>">
    <input type="hidden" name="old_waybill_image" value="<?php echo $output['waybill_info']['waybill_image'];?>">
    <?php } ?>
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="waybill_name"><em>*</em>模板名称</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['waybill_info']?$output['waybill_info']['waybill_name']:'';?>" name="waybill_name" id="waybill_name" class="input-txt">
          <span class="err"></span>
          <p class="notic">运单模板名称，最多10个字</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="waybill_name"><em>*</em>物流公司</label>
        </dt>
        <dd class="opt">
          <select name="waybill_express">
            <?php if(!empty($output['express_list']) && is_array($output['express_list'])) {?>
            <?php foreach($output['express_list'] as $value) {?>
            <option value="<?php echo $value['id'];?>|<?php echo $value['e_name'];?>" <?php if($value['selected']) { echo 'selected'; }?> ><?php echo $value['e_name'];?></option>
            <?php } ?>
            <?php } ?>
          </select>
          <span class="err"></span>
          <p class="notic">模板对应的物流公司</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="waybill_width"><em>*</em>宽度</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['waybill_info']?$output['waybill_info']['waybill_width']:'';?>" name="waybill_width" id="waybill_width" class="input-txt">
          <span class="err"></span>
          <p class="notic">运单宽度，单位为毫米(mm)</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="waybill_height"><em>*</em>高度</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['waybill_info']?$output['waybill_info']['waybill_height']:'';?>" name="waybill_height" id="waybill_height" class="input-txt">
          <span class="err"></span>
          <p class="notic">运单高度，单位为毫米(mm)</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="waybill_top"><em>*</em>上偏移量</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['waybill_info']?$output['waybill_info']['waybill_top']:'0';?>" name="waybill_top" id="waybill_top" class="input-txt">
          <span class="err"></span>
          <p class="notic">运单模板上偏移量，单位为毫米(mm)</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="waybill_left"><em>*</em>左偏移量</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['waybill_info']?$output['waybill_info']['waybill_left']:'0';?>" name="waybill_left" id="waybill_left" class="input-txt">
          <span class="err"></span>
          <p class="notic">运单模板左偏移量，单位为毫米(mm)</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="waybill_image"><em>*</em>模板图片</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show">
            <?php if($output['waybill_info']) { ?>
            <span class="show"><a class="nyroModal" rel="gal" href="<?php echo $output['waybill_info']['waybill_image_url'];?>"/><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo $output['waybill_info']['waybill_image_url'];?>>')" onMouseOut="toolTip()"></i></a></span>
            <?php } ?>
            <span class="type-file-box">
            <input type='text' name='textfield' id='textfield' class='type-file-text' />
            <input type='button' name='button' id='button' value='选择上传...' class='type-file-button' />
            <input name="waybill_image" type="file" class="type-file-file" id="waybill_image" size="30" hidefocus="true"  title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效"/>
            </span></div>
          <span class="err"></span>
          <p class="notic">请上传扫描好的运单图片，图片尺寸必须与快递单实际尺寸相符</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="waybill_image"><em>*</em>启用</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <?php if(!empty($output['waybill_info']) && $output['waybill_info']['waybill_usable'] == '1') { $usable = 1; } else { $usable = 0; } ?>
            <input id="waybill_usable_1" type="radio" name="waybill_usable" value="1" <?php echo $usable ? 'checked' : '';?>>
            <label for="waybill_usable_1"  class="cb-enable <?php echo $usable ? 'selected' : '';?>" >是</label>
            <input id="waybill_usable_0" type="radio" name="waybill_usable" value="0" <?php echo $usable ? '' : 'checked';?>>
            <label for="waybill_usable_0"  class="cb-disable <?php echo $usable ? '' : 'selected';?>" >否</label>
          </div>
          <p class="notic">请首先设计并测试模板然后再启用，启用后商家可以使用 </p>
        </dd>
      </dl>
      <div class="bot"><a id="submit" href="javascript:void(0)" class="ncap-btn-big ncap-btn-green"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$('.nyroModal').nyroModal();// 点击查看图片
	
	$("#waybill_image").change(function(){
		$("#textfield").val($(this).val());
	});

    $("#submit").click(function(){
        $("#add_form").submit();
    });
    $('#add_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            waybill_name: {
                required : true,
                maxlength : 10
            },
            waybill_width: {
                required : true,
                digits: true 
            },
            waybill_height: {
                required : true,
                digits: true 
            },
            waybill_top: {
                required : true,
                number: true 
            },
            waybill_left: {
                required : true,
                number: true 
            },
            waybill_image: {
                <?php if(!$output['waybill_info']) { ?>
                required : true,
                <?php } ?>
                accept: "jpg|jpeg|png"
            }
        },
        messages : {
            waybill_name: {
                required : "<i class='fa fa-exclamation-circle'></i>模板名称不能为空",
                maxlength : "<i class='fa fa-exclamation-circle'></i>模板名称最多10个字" 
            },
            waybill_width: {
                required : "<i class='fa fa-exclamation-circle'></i>宽度不能为空",
                digits: "<i class='fa fa-exclamation-circle'></i>宽度必须为数字"
            },
            waybill_height: {
                required : "<i class='fa fa-exclamation-circle'></i>高度不能为空",
                digits: "<i class='fa fa-exclamation-circle'></i>高度必须为数字"
            },
            waybill_top: {
                required : "<i class='fa fa-exclamation-circle'></i>上偏移量不能为空",
                number: "<i class='fa fa-exclamation-circle'></i>上偏移量必须为数字"
            },
            waybill_left: {
                required : "<i class='fa fa-exclamation-circle'></i>左偏移量不能为空",
                number: "<i class='fa fa-exclamation-circle'></i>左偏移量必须为数字"
            },
            waybill_image: {
                <?php if(!$output['waybill_info']) { ?>
                required : '<i class="fa fa-exclamation-circle"></i>图片不能为空',
                <?php } ?>
                accept: '<i class="fa fa-exclamation-circle"></i>图片类型不正确' 
            }
        }
    });
});
</script> 
