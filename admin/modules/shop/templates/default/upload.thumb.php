<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_picture_set'];?></h3>
        <h5><?php echo $lang['nc_picture_set_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <form method="post" enctype="multipart/form-data" onsubmit="MySubmit();return false;" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="default_goods_image"><?php echo $lang['default_product_pic'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_COMMON.'/'.$output['list_setting']['default_goods_image']);?>"><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_COMMON.'/'.$output['list_setting']['default_goods_image']);?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input class="type-file-file" id="default_goods_image" name="default_goods_image" type="file" size="30" hidefocus="true" nc_type="change_default_goods_image" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            </span></div>
          <p class="notic">300px * 300px</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="default_store_logo"><?php echo $lang['default_store_logo'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_COMMON.'/'.$output['list_setting']['default_store_logo']);?>"> <i class="fa fa-picture-o"  onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_COMMON.'/'.$output['list_setting']['default_store_logo']);?>>')" onMouseOut="toolTip()"></i> </a></span><span class="type-file-box">
            <input class="type-file-file" id="default_store_logo" name="default_store_logo" type="file" size="30" hidefocus="true" nc_type="change_default_store_logo" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            </span></div>
          <p class="notic">200px * 60px</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="default_store_logo">默认店铺头像</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_COMMON.'/'.$output['list_setting']['default_store_avatar']);?>"> <i class="fa fa-picture-o"  onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_COMMON.'/'.$output['list_setting']['default_store_avatar']);?>>')" onMouseOut="toolTip()"></i> </a></span><span class="type-file-box">
            <input class="type-file-file" id="default_store_avatar" name="default_store_avatar" type="file" size="30" hidefocus="true" nc_type="change_default_store_avatar" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            </span></div>
          <p class="notic">100px * 100px</p>
        </dd>
      </dl>
      </tbody>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.form1.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script type="text/javascript">
$(function(){
// 模拟默认商品图片上传input type='file'样式
	var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />"
    $(textButton).insertBefore("#default_goods_image");
    $("#default_goods_image").change(function(){
	$("#textfield1").val($("#default_goods_image").val());
    });
// 模拟默认店铺图片上传input type='file'样式
	var textButton="<input type='text' name='textfield' id='textfield2' class='type-file-text' /><input type='button' name='button' id='button2' value='选择上传...' class='type-file-button' />"
    $(textButton).insertBefore("#default_store_logo");
    $("#default_store_logo").change(function(){
	$("#textfield2").val($("#default_store_logo").val());
    });
    // 模拟默认店铺图片上传input type='file'样式
	var textButton="<input type='text' name='textfield' id='textfield3' class='type-file-text' /><input type='button' name='button' id='button3' value='选择上传...' class='type-file-button' />"
    $(textButton).insertBefore("#default_store_avatar");
    $("#default_store_avatar").change(function(){
	$("#textfield3").val($("#default_store_avatar").val());
    });

// 上传图片类型
	$('input[class="type-file-file"]').change(function(){
		var filepath=$(this).val();
		var extStart=filepath.lastIndexOf(".");
		var ext=filepath.substring(extStart,filepath.length).toUpperCase();
		if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
			alert("<?php echo $lang['default_img_wrong'];?>");
				$(this).attr('value','');
			return false;
		}
	})
// 点击查看图片
	$('.nyroModal').nyroModal();
});
</script> 
