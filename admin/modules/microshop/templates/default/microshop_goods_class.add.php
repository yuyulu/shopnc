<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=goods_class&op=goodsclass_list" title="返回分类列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['nc_microshop_goods_class'];?> - 新增/编辑随心看分类<?php if(isset($output['class_info']['class_name'])) echo $output['class_info']['class_name'];?></h3>
        <h5><?php echo $lang['nc_microshop_goods_class_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="add_form" method="post" enctype="multipart/form-data" action="index.php?act=goods_class&op=goodsclass_save">
    <input name="class_id" type="hidden" value="<?php echo $output['class_info']['class_id'];?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="class_name"><em>*</em><?php echo $lang['microshop_class_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php if(isset($output['class_info']['class_name'])) echo $output['class_info']['class_name'];?>" name="class_name" id="class_name" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['class_name_error'];?></p>
        </dd>
      </dl>
      <?php if(empty($output['class_info'])) { ?>
      <dl class="row">
        <dt class="tit">
          <label for="class_parent_id"><?php echo $lang['microshop_parent_class'];?></label>
        </dt>
        <dd class="opt">
          <select id="class_parent_id" name="class_parent_id">
            <option value="0"><?php echo $lang['nc_please_choose'];?></option>
            <?php if(!empty($output['list']) && is_array($output['list'])) {?>
            <?php foreach($output['list'] as $key=>$val) {?>
            <option value="<?php echo $val['class_id'];?>" <?php if($output['class_parent_id'] == $val['class_id']) echo 'selected';?>><?php echo $val['class_name'];?></option>
            <?php } ?>
            <?php } ?>
          </select>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php } ?>
      <dl class="row">
        <dt class="tit">
          <label for="class_image"><?php echo $lang['microshop_class_image'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"> <span class="show">
            <?php if(empty($output['class_info']['class_image'])) { ?>
            <a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.DS.ATTACH_MICROSHOP.DS.'default_goods_class_image.gif';?>"><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.DS.ATTACH_MICROSHOP.DS.'default_goods_class_image.gif';?>>')" onMouseOut="toolTip()"></i></a>
            <?php } else { ?>
            <a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.DS.ATTACH_MICROSHOP.DS.$output['class_info']['class_image'];?>"><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.DS.ATTACH_MICROSHOP.DS.$output['class_info']['class_image'];?>>')" onMouseOut="toolTip()"></i></a>
            <?php } ?>
            </span> <span class="type-file-box">
            <input name="old_class_image" type="hidden" value="<?php echo $output['class_info']['class_image'];?>" />
            <input name="class_image" type="file" class="type-file-file" id="class_image" size="30" hidefocus="true" nc_type="microshop_goods_class_image" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            </span> </div>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php if(empty($output['class_info']) || intval($output['class_info']['class_parent_id']) > 0) { ?>
      <dl class="row class_keyword">
        <dt class="tit">
          <label for="class_sort"><em>*</em><?php echo $lang['microshop_class_keyword'];?></label>
        </dt>
        <dd class="opt">
          <textarea name="class_keyword" rows="25" cols="30"><?php echo empty($output['class_info']['class_keyword'])?'':$output['class_info']['class_keyword'];?></textarea>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['class_keyword_explain'];?></p>
        </dd>
      </dl>
      <?php } ?>
      <dl class="row">
        <dt class="tit">
          <label for="class_sort"><em>*</em><?php echo $lang['nc_sort'];?></label>
        </dt>
        <dd class="opt">
          <input id="class_sort" name="class_sort" type="text" class="input-txt"
          value="<?php echo !isset($output['class_info'])?'255':$output['class_info']['class_sort'];?>" />
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
    $(textButton).insertBefore("#class_image");
    $("#class_image").change(function(){
        $("#textfield1").val($("#class_image").val());
    });

    $("#submit").click(function(){
        $("#add_form").submit();
    });

    $("input[nc_type='microshop_goods_class_image']").live("change", function(){
		var src = getFullPath($(this)[0]);
		$(this).parent().prev().find('.low_source').attr('src',src);
		$(this).parent().find('input[class="type-file-text"]').val($(this).val());
	});
    <?php if(empty($output['class_info'])) { ?>
    class_keyword_display();    
    $("#class_parent_id").change(function(){
        class_keyword_display();    
    });
    function class_keyword_display() {
        var class_parent_id = $("#class_parent_id").val();
        if(class_parent_id > 0) {
            $(".class_keyword").show();
        } else {
            $(".class_keyword").hide();
        }
    }
    <?php } ?>

    $('#add_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            class_name: {
                required : true,
                maxlength : 10
            },
            class_keyword: {
                maxlength : 150
            },
            class_sort: {
                required : true,
                digits: true,
                max: 255,
                min: 0
            }
        },
        messages : {
            class_name: {
                required : "<i class='fa fa-exclamation-circle'></i><?php echo $lang['class_name_required'];?>",
                maxlength : jQuery.validator.format("<i class='fa fa-exclamation-circle'></i><?php echo $lang['class_name_maxlength'];?>")
            },
            class_keyword: {
                maxlength : jQuery.validator.format("<i class='fa fa-exclamation-circle'></i><?php echo $lang['class_keyword_maxlength'];?>"),
            },
            class_sort: {
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
