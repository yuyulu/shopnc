<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['top_set'];?></h3>
        <h5><?php echo $lang['top_set_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>在这里可以设置顶部广告是否开启及广告基本的设置。</li>
    </ul>
  </div>
  <form method="post" enctype="multipart/form-data" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="top_name"><?php echo $lang['top_name'];?></label>
        </dt>
        <dd class="opt">
          <input id="top_banner_name" name="top_banner_name" value="<?php echo $output['list_setting']['hao_top_banner_name'];?>" class="input-txt" type="text" />
          <p class="notic"><?php echo $lang['top_name_notice'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="top_banner_url"><?php echo $lang['top_banner_url'];?></label>
        </dt>
        <dd class="opt">
          <input id="top_banner_url" name="top_banner_url" value="<?php echo $output['list_setting']['hao_top_banner_url'];?>" class="input-txt" type="text" />
          <p class="notic"><?php echo $lang['top_banner_url_notice'];?></p>
        </dd>
      </dl>
            <dl class="row">
        <dt class="tit">
          <label for="top_banner_color"><?php echo $lang['top_banner_color'];?></label>
        </dt>
        <dd class="opt">
          <input id="top_banner_color" name="top_banner_color" value="<?php echo $output['list_setting']['hao_top_banner_color'];?>" class="input-txt" type="text" />
          <p class="notic"><?php echo $lang['top_banner_color_notice'];?></p>
        </dd>
      </dl>
          <dl class="row">
        <dt class="tit">
          <label for="hao_top_banner_pic"><?php echo $lang['top_banner_pic'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_COMMON.DS.$output['list_setting']['hao_top_banner_pic']);?>"> <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_COMMON.DS.$output['list_setting']['hao_top_banner_pic']);?>>')" onMouseOut="toolTip()"/></i> </a></span><span class="type-file-box">
            <input type="text" name="textfield" id="textfield" class="type-file-text" />
            <input type="button" name="button" id="button" value="选择上传..." class="type-file-button" />
            <input class="type-file-file" id="hao_top_banner_pic" name="hao_top_banner_pic" type="file" size="30" hidefocus="true" nc_type="change_hao_top_banner_pic" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            </span></div>
          <span class="err"></span>
          <p class="notic">通用头部显示，最佳显示尺寸为1200*50像素</p>
        </dd>
      </dl>

      <dl class="row">
        <dt class="tit"><?php echo $lang['top_banner_state'];?></dt>
        <dd class="opt">
          <div class="onoff">
            <label for="top_banner_status1" class="cb-enable <?php if($output['list_setting']['hao_top_banner_status'] == '1'){ ?>selected<?php } ?>" ><?php echo $lang['open'];?></label>
            <label for="top_banner_status0" class="cb-disable <?php if($output['list_setting']['hao_top_banner_status'] == '0'){ ?>selected<?php } ?>" ><?php echo $lang['close'];?></label>
            <input id="top_banner_status1" name="top_banner_status" <?php if($output['list_setting']['hao_top_banner_status'] == '1'){ ?>checked="checked"<?php } ?>  value="1" type="radio">
            <input id="top_banner_status0" name="top_banner_status" <?php if($output['list_setting']['hao_top_banner_status'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic"><?php echo $lang['top_banner_state_notice'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.form1.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>

<script type="text/javascript">
// 模拟网站LOGO上传input type='file'样式
$(function(){
	$("#top_banner_pic").change(function(){
		$("#textfield").val($(this).val());
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
	});
// 点击查看图片
	$('.nyroModal').nyroModal();
$('#time_zone').attr('value','<?php echo $output['list_setting']['time_zone'];?>');	
});
</script> 