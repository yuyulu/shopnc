<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=adv&op=adv_manage" title="返回广告列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['nc_microshop_adv_manage'];?> - 新增/编辑广告条<?php if(isset($output['adv_info']['adv_name'])) echo $output['adv_info']['adv_name'];?></h3>
        <h5><?php echo $lang['nc_microshop_adv_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="add_form" method="post" enctype="multipart/form-data" action="index.php?act=adv&op=adv_save">
    <input name="adv_id" type="hidden" value="<?php echo $output['adv_info']['adv_id'];?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label  for="adv_name"><?php echo $lang['microshop_adv_type'];?> </label>
        </dt>
        <dd class="opt">
          <select name="adv_type">
            <?php if(!empty($output['adv_type_list']) && is_array($output['adv_type_list'])) {?>
            <?php foreach($output['adv_type_list'] as $key=>$value) {?>
            <option value="<?php echo $key;?>" <?php if($key==$output['adv_info']['adv_type']) {echo 'selected';}?>><?php echo $value;?></option>
            <?php } ?>
            <?php } ?>
          </select>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['microshop_adv_type_explain'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="adv_name"><?php echo $lang['microshop_adv_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php if(isset($output['adv_info']['adv_name'])) echo $output['adv_info']['adv_name'];?>" name="adv_name" id="adv_name" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="adv_image"><em>*</em><?php echo $lang['microshop_adv_image'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show">
            <?php if(!empty($output['adv_info']['adv_image'])) { ?>
            <span class="show"> <a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.DS.ATTACH_MICROSHOP.DS.'adv'.DS.$output['adv_info']['adv_image'];?>"><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.DS.ATTACH_MICROSHOP.DS.'adv'.DS.$output['adv_info']['adv_image'];?>>')" onMouseOut="toolTip()"></i></a> </span>
            <?php } ?>
            <span class="type-file-box">
            <input name="old_adv_image" type="hidden" value="<?php echo $output['adv_info']['adv_image'];?>" />
            <input name="adv_image" type="file" class="type-file-file" id="adv_image" size="30" hidefocus="true" nc_type="microshop_goods_adv_image" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            </span> </div>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['microshop_adv_image_explain'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="adv_url"><?php echo $lang['microshop_adv_url'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php if(isset($output['adv_info']['adv_url'])) echo $output['adv_info']['adv_url'];?>" name="adv_url" id="adv_url" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['microshop_adv_url_explain'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="adv_sort"><em>*</em><?php echo $lang['nc_sort'];?></label>
        </dt>
        <dd class="opt">
          <input id="adv_sort" name="adv_sort" type="text" class="input-txt" value="<?php echo !isset($output['adv_info'])?'255':$output['adv_info']['adv_sort'];?>" />
          <span class="err"></span>
          <p class="notic"><?php echo $lang['class_sort_explain'];?></p>
        </dd>
      </dl>
      <div class="bot"><a id="submit" href="javascript:void(0)" class="ncap-btn-big ncap-btn-green"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script type="text/javascript">
$(document).ready(function(){

    //文件上传
    var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />";
    $(textButton).insertBefore("#adv_image");
    $("#adv_image").change(function(){
        $("#textfield1").val($("#adv_image").val());
    });

    $("#submit").click(function(){
        $("#add_form").submit();
    });

    $("input[nc_type='microshop_goods_adv_image']").live("change", function(){
		var src = getFullPath($(this)[0]);
		$(this).parent().prev().find('.low_source').attr('src',src);
		$(this).parent().find('input[class="type-file-text"]').val($(this).val());
	});

    $('#add_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
        <?php if(empty($output['adv_info'])) { ?>
            adv_image: {
                required : true
            },
            <?php } ?>
            adv_sort: {
                required : true,
                digits: true,
                max: 255,
                min: 0
            }
        },
        messages : {
        <?php if(empty($output['adv_info'])) { ?>
            adv_image: {
                required : "<i class='fa fa-exclamation-circle'></i><?php echo $lang['microshop_adv_image_error'];?>"
            },
            <?php } ?>
            adv_sort: {
                required : "<i class='fa fa-exclamation-circle'></i><?php echo $lang['class_sort_required'];?>",
                digits: "<i class='fa fa-exclamation-circle'></i><?php echo $lang['class_sort_digits'];?>",
                max : jQuery.validator.format("<i class='fa fa-exclamation-circle'></i><?php echo $lang['class_sort_max'];?>"),
                min : jQuery.validator.format("<i class='fa fa-exclamation-circle'></i><?php echo $lang['class_sort_min'];?>")
            }
        }
    });
	// 点击查看图片
	$('.nyroModal').nyroModal();
});
</script>