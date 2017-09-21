<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['upload_set'];?></h3>
        <h5><?php echo $lang['upload_set_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>依据服务器环境支持最大上传组件大小设置选项，如需要上传超大附件需调整服务器环境配置。</li>
    </ul>
  </div>
  <form id="form" method="post" enctype="multipart/form-data" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="image_max_filesize"><?php echo $lang['upload_image_filesize'];?></label>
        </dt>
        <dd class="opt"><?php echo $lang['upload_image_file_size'];?>
          <input id="image_max_filesize" name="image_max_filesize" type="text" class="input-txt" style="width:30px !important;" value="<?php echo $output['list_setting']['image_max_filesize'] ? $output['list_setting']['image_max_filesize'] : '1024' ;?>"/>
          KB&nbsp;(1024 KB = 1MB)
          <p class="notic"><?php echo $lang['image_max_size_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="image_allow_ext"><?php echo $lang['image_allow_ext'];?></label>
        </dt>
        <dd class="opt">
          <input id="image_allow_ext" name="image_allow_ext" value="<?php echo $output['list_setting']['image_allow_ext'] ? $output['list_setting']['image_allow_ext'] : 'gif,jpeg,jpg,bmp,png,swf,tbi';?>" class="input-txt" type="text" />
          <p class="notic"><?php echo $lang['image_allow_ext_notice'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.settingForm.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
//<!CDATA[
$(function(){
	$('#form').validate({
		rules : {
		    image_max_filesize : {
				number : true,
				maxlength : 4
			},
			image_allow_ext : {
				required : true
			}
		},
		messages : {
		    image_max_filesize : {
				number   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['image_max_size_only_num'];?>',
				maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['image_max_size_c_num'];?>'
			},
			image_allow_ext : {
				required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['image_allow_ext_not_null'];?>'
			}
		}
	});
});
//]]>
</script>