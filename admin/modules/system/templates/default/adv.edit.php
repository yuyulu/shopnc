<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="<?php echo getReferer();?>" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['adv_index_manage'];?> - 编辑广告“<?php echo $output['adv_list'][0]['adv_title']; ?>” </h3>
        <h5><?php echo $lang['adv_index_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="adv_form" enctype="multipart/form-data" method="post" name="advForm">
    <input type="hidden" name="ref_url" value="<?php echo $output['ref_url'];?>" />
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <?php foreach($output['adv_list'] as $k => $v){ ?>
      <dl class="row">
        <dt class="tit">
          <label for="adv_name"><em>*</em><?php echo $lang['adv_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="adv_name" id="adv_name" class="input-txt" value="<?php echo $v['adv_title']; ?>">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php foreach ($output['ap_info'] as $ap_k => $ap_v){ if($v['ap_id'] == $ap_v['ap_id']){ ?>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['adv_ap_id'];?></label>
        </dt>
        <dd class="opt"><?php echo $ap_v['ap_name'];?><span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['adv_class'];?></label>
        </dt>
        <dd class="opt">
          <?php switch ($ap_v['ap_class']){ case '0': echo $lang['adv_pic']; break; case '1': echo $lang['adv_word']; break; case '2': echo $lang['adv_slide']; break; case '3': echo "Flash"; break;} ?>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="adv_start_date"><?php echo $lang['adv_start_time'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="adv_start_date" id="adv_start_date" class="input-txt" value="<?php echo date('Y-m-d',$v['adv_start_date']); ?>">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="adv_end_date"><?php echo $lang['adv_end_time'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="adv_end_date" id="adv_end_date" class="input-txt" value="<?php echo date('Y-m-d',$v['adv_end_date']); ?>">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php switch ($ap_v['ap_class']){ case '0': $pic_content = unserialize($v['adv_content']); $pic = $pic_content['adv_pic']; $url = $pic_content['adv_pic_url']; ?>
      <dl class="row" id="adv_pic">
        <dt class="tit">
          <input type="hidden" name="mark" value="0">
          <label for="file_adv_pic"><?php echo $lang['adv_img_upload'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"> <span class="show"> <a class="nyroModal" href="<?php echo UPLOAD_SITE_URL."/".ATTACH_ADV."/".$pic;?>" rel="gal"> <i class="fa fa-picture-o" onmouseout="toolTip()" onmouseover="toolTip('<img src=<?php echo UPLOAD_SITE_URL."/".ATTACH_ADV."/".$pic;?>>')"></i> </a> </span> <span class="type-file-box">
            <input type="file" class="type-file-file" id="file_adv_pic" name="adv_pic" size="30" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效"/>
            </span>
            <input type="hidden" name="pic_ori" value="<?php echo $pic;?>">
          </div>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['adv_edit_support'];?>gif,jpg,jpeg,png </p>
        </dd>
      </dl>
      <dl class="row" id="adv_pic_url">
        <dt class="tit">
          <label for="adv_pic_url"><?php echo $lang['adv_url'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" id="adv_pic_url" name="adv_pic_url" value="<?php echo $url; ?>" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['adv_url_donotadd'];?></p>
        </dd>
      </dl>
      <?php break; case '1': $word_content = unserialize($v['adv_content']); $word = $word_content['adv_word']; $url = $word_content['adv_word_url']; ?>
      <dl class="row" id="adv_word" >
        <input type="hidden" name="mark" value="1">
        <dt class="tit">
          <label for="adv_word"><?php echo $lang['adv_word_content'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="adv_word" id="adv_word" class="input-txt" value="<?php echo $word; ?>">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['adv_max'];?><?php echo $ap_v['ap_width'];?><?php echo $lang['adv_byte'];?>
            <input type="hidden" name="adv_word_len" value="<?php echo $ap_v['ap_width'];?>" >
          </p>
        </dd>
      </dl>
      <dl class="row" id="adv_word_url">
        <dt class="tit">
          <label for="adv_word_url"><?php echo $lang['adv_url'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="adv_word_url" class="input-txt" id="adv_word_url" value="<?php echo $url; ?>">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['adv_url_donotadd'];?> </p>
        </dd>
      </dl>
      <?php break; case '3': $flash_content = unserialize($v['adv_content']); $flash = $flash_content['flash_swf']; $url = $flash_content['flash_url']; ?>
      <dl class="row" id="adv_flash_swf">
        <input type="hidden" name="mark" value="3">
        <dt class="tit">
          <label class="file_flash_swf"><?php echo $lang['adv_flash_upload'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="type-file-box">
            <input type="file" name="flash_swf" class="type-file-file" id="file_flash_swf" size="30"/>
            </span></div>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['adv_please_file_swf_file']; ?></p>
          <a href="http://<?php echo $url; ?>" target='_blank'>
          <button style="width:<?php echo $ap_v['ap_width']; ?>px; height:<?php echo $ap_v['ap_height']; ?>px; border:none; padding:0; background:none;" disabled >
          <object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="<?php echo $ap_v['ap_width']; ?>" height="<?php echo $ap_v['ap_height']; ?>">
            <param name="movie" value="<?php echo UPLOAD_SITE_URL."/".ATTACH_ADV."/".$flash;?>" />
            <param name="quality" value="high" />
            <param name="wmode" value="opaque" />
            <param name="swfversion" value="9.0.45.0" />
            <!-- 此 param 标签提示使用 Flash Player 6.0 r65 和更高版本的用户下载最新版本的 Flash Player。如果您不想让用户看到该提示，请将其删除。 -->
            <param name="expressinstall" value="<?php echo RESOURCE_SITE_URL;?>/js/expressInstall.swf" />
            <!-- 下一个对象标签用于非 IE 浏览器。所以使用 IECC 将其从 IE 隐藏。 -->
            <object type="application/x-shockwave-flash" data="<?php echo UPLOAD_SITE_URL."/".ATTACH_ADV."/".$flash;?>" width="<?php echo $ap_v['ap_width']; ?>" height="<?php echo $ap_v['ap_height']; ?>">
              <param name="quality" value="high" />
              <param name="wmode" value="opaque" />
              <param name="swfversion" value="9.0.45.0" />
              <param name="expressinstall" value="<?php echo RESOURCE_SITE_URL;?>/js/expressInstall.swf" />
              <!-- 浏览器将以下替代内容显示给使用 Flash Player 6.0 和更低版本的用户。 -->
              <h4>此页面上的内容需要较新版本的 Adobe Flash Player。</h4>
              <p><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="获取 Adobe Flash Player" width="112" height="33" href="http://www.adobe.com/go/getflashplayer" /></p>
            </object>
          </object>
          </button>
          <input type="hidden" name="flash_ori" value="<?php echo $flash;?>">
          </a> </dd>
      </dl>
      <dl class="row" id="adv_flash_url">
        <dt class="tit">
          <label for="flash_url"><?php echo $lang['adv_url'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="flash_url" id="flash_url" class="input-txt" value="<?php echo $url; ?>">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['adv_url_donotadd'];?></p>
        </dd>
      </dl>
      <?php }?>
      <?php }?>
      <?php }?>
      <?php }?>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.advForm.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script type="text/javascript">
$(function(){
	// 点击查看图片
	$('.nyroModal').nyroModal();
	
    $('#adv_start_date').datepicker({dateFormat: 'yy-mm-dd'});
    $('#adv_end_date').datepicker({dateFormat: 'yy-mm-dd'});

	var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />"
    $(textButton).insertBefore("#file_adv_pic");
    $("#file_adv_pic").change(function(){
	$("#textfield1").val($("#file_adv_pic").val());
    });

	var textButton="<input type='text' name='textfield' id='textfield3' class='type-file-text' /><input type='button' name='button' id='button3' value='选择上传...' class='type-file-button' />"
    $(textButton).insertBefore("#file_flash_swf");
    $("#file_flash_swf").change(function(){
	$("#textfield3").val($("#file_flash_swf").val());
    });
});
</script>