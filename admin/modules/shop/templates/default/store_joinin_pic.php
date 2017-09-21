<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>商家入驻</h3>
        <h5>开店招商及商家开店申请页面内容管理</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="JavaScript:void(0);" class="current"><?php echo '入驻首页';?></a></li>
        <li><a href="index.php?act=store_joinin&op=help_list"><?php echo '入驻指南';?></a></li>
      </ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>可以传三张图片，在商家入驻首页头部显示，建议使用1920px * 350px</li>
      <li>可选择删除图片，提交保存后生效，则前台页面轮播未删除的图片</li>
      <li>所填写的“贴心提示”会出现在开店首页图片下方</li>
    </ul>
  </div>
  <form method="post" enctype="multipart/form-data" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <?php for ($i = 1;$i <= $output['size'];$i++) { ?>
      <dl class="row">
        <dt class="tit">
          <label>横幅大图<?php echo $i;?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show">
            <?php if(!empty($output['pic'][$i])){ ?>
            <span class="show" id="show<?php echo $i;?>"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.'/'.$output['pic'][$i];?>"><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.'/'.$output['pic'][$i];?>" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.'/'.$output['pic'][$i];?>>')" onMouseOut="toolTip()"/></a></span>
            <?php } ?>
            <span class="type-file-box">
            <input type="text" name="textfield" id="textfield<?php echo $i;?>" class="type-file-text" />
            <input type="button" name="button" id="button<?php echo $i;?>" value="选择上传..." class="type-file-button" />
            <input class="type-file-file" id="pic<?php echo $i;?>" name="pic<?php echo $i;?>" type="file" size="30" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            <input type="hidden" name="show_pic<?php echo $i;?>" id="show_pic<?php echo $i;?>" value="<?php echo $output['pic'][$i];?>" />
            </span></div> <a href="JavaScript:void(0);" class="ncap-btn" onclick="clear_pic(<?php echo $i;?>)"><i class="fa fa-trash"></i>
删除</a>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php } ?>
      <dl class="row">
        <dt class="tit">
          <label for="show_txt">贴心提示:</label>
        </dt>
        <dd class="opt">
          <textarea name="show_txt" rows="6" class="tarea" id="show_txt" ><?php echo $output['show_txt'];?></textarea>
          <p class="notic"><span class="vatop rowform"></span></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.form1.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script type="text/javascript">
$(function(){
    $('input[class="type-file-file"]').change(function(){
    	var pic=$(this).val();
    	var extStart=pic.lastIndexOf(".");
    	var ext=pic.substring(extStart,pic.length).toUpperCase();
    	$(this).parent().find(".type-file-text").val(pic);
		if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
		    alert("<?php echo $lang['default_img_wrong'];?>");
			$(this).attr('value','');
			return false;
		}
	});
    $('.nyroModal').nyroModal();
});
function clear_pic(n){//置空
	$("#show"+n+"").remove();
	$("#textfield"+n+"").val("");
	$("#pic"+n+"").val("");
	$("#show_pic"+n+"").val("");
}
</script> 
