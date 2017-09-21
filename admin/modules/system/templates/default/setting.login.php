<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['web_set'];?></h3>
        <h5><?php echo $lang['web_set_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>网站登录/注册页面左侧图片，每次刷新可随机显示，最多可设置上传4张。</li>
      <li>选择上传文件并提交表单生效，图片请依据输入框下提示文字内容选择。</li>
    </ul>
  </div>
  <form method="post" enctype="multipart/form-data" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="old_login_pic1" value="<?php echo $output['list'][0];?>" />
    <input type="hidden" name="old_login_pic2" value="<?php echo $output['list'][1];?>" />
    <input type="hidden" name="old_login_pic3" value="<?php echo $output['list'][2];?>" />
    <input type="hidden" name="old_login_pic4" value="<?php echo $output['list'][3];?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="login_pic1">主题轮换图片1</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_PATH.'/login/'.$output['list'][0]);?>"><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_PATH.'/login/'.$output['list'][0]);?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input name="login_pic1" type="file" class="type-file-file" id="login_pic1" size="30" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效"/>
            </span></div>
          <p class="notic">请使用450*350像素jpg/gif/png格式的图片。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="login_pic2">主题轮换图片2</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_PATH.'/login/'.$output['list'][1]);?>"><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_PATH.'/login/'.$output['list'][1]);?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input name="login_pic2" type="file" class="type-file-file" id="login_pic2" size="30" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效"/>
            </span></div>
          <p class="notic">请使用450*350像素jpg/gif/png格式的图片。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="login_pic3">主题轮换图片3</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_PATH.'/login/'.$output['list'][2]);?>"><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_PATH.'/login/'.$output['list'][2]);?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input name="login_pic3" type="file" class="type-file-file" id="login_pic3" size="30" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效"/>
            </span></div>
          <p class="notic">请使用450*350像素jpg/gif/png格式的图片。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="login_pic4">主题轮换图片4</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_PATH.'/login/'.$output['list'][3]);?>"><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_PATH.'/login/'.$output['list'][3]);?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input name="login_pic4" type="file" class="type-file-file" id="login_pic4" size="30" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效"/>
            </span></div>
          <p class="notic">请使用450*350像素jpg/gif/png格式的图片。</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.form1.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script type="text/javascript">
// 模拟网站LOGO上传input type='file'样式
$(function(){
    var textButton1="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />"
    var textButton2="<input type='text' name='textfield' id='textfield2' class='type-file-text' /><input type='button' name='button' id='button2' value='选择上传...' class='type-file-button' />"
    var textButton3="<input type='text' name='textfield' id='textfield3' class='type-file-text' /><input type='button' name='button' id='button3' value='选择上传...' class='type-file-button' />"
    var textButton4="<input type='text' name='textfield' id='textfield4' class='type-file-text' /><input type='button' name='button' id='button4' value='选择上传...' class='type-file-button' />"
	$(textButton1).insertBefore("#login_pic1");
	$(textButton2).insertBefore("#login_pic2");
	$(textButton3).insertBefore("#login_pic3");
	$(textButton4).insertBefore("#login_pic4");
	$("#login_pic1").change(function(){$("#textfield1").val($("#login_pic1").val());});
	$("#login_pic2").change(function(){$("#textfield2").val($("#login_pic2").val());});
	$("#login_pic3").change(function(){$("#textfield3").val($("#login_pic3").val());});
	$("#login_pic4").change(function(){$("#textfield4").val($("#login_pic4").val());});
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
	});

$('#time_zone').attr('value','<?php echo $output['list_setting']['time_zone'];?>');
$('.nyroModal').nyroModal();
});
</script> 
