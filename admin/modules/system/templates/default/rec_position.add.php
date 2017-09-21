<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=rec_position&op=rec_list" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['rec_position'];?> - <?php echo $lang['nc_new'];?></h3>
        <h5><?php echo $lang['rec_position_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="rec_form" enctype="multipart/form-data" method="post" action="index.php?act=rec_position&op=rec_save">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['rec_ps_title'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="rtitle" id="rtitle" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['rec_ps_title_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['rec_ps_type'];?></label>
        </dt>
        <dd class="opt">
          <select name="rec_type" id="rec_type">
            <option value="2"><?php echo $lang['rec_ps_pic'];?></option>
            <option value="1"><?php echo $lang['rec_ps_txt'];?></option>
          </select>
          <p class="notic"><?php echo $lang['rec_ps_type_tips'];?></p>
        </dd>
      </dl>
      <dl class="row" id="tr_pic_type" style="display:none;">
        <dt class="tit"><?php echo $lang['rec_ps_select_pic']?></dt>
        <dd class="opt">
          <ul>
            <li>
              <label>
                <input name="pic_type" id="pic_type_1" type="radio" value="1" checked="checked">
                <?php echo $lang['rec_ps_local'];?></label>
            </li>
            <li>
              <label>
                <input type="radio" name="pic_type" id="pic_type_2" value="2">
                <?php echo $lang['rec_ps_remote'];?></label>
            </li>
          </ul>
          <p class="notic"><?php echo $lang['rec_ps_type_tips2'];?></p>
        </dd>
      </dl>
      <dl class="row" id="local_txt" style="display:none">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['rec_ps_ztxt'];?></label>
        </dt>
        <dd class="opt">
          <ul class="ncap-ajax-add" id="RemoteBoxTxt">
            <li>
              <label>
                <input type="text" value="" name="txt[]" size="30" placeholder="输入要展示的文字内容" class="input-txt" hidefocus="true">
              </label>
              <label title="<?php echo $lang['rec_ps_gourl'];?>"><i class="fa fa-link"></i>
                <input type="text" value="http://" name="urltxt[]"  placeholder="<?php echo $lang['rec_ps_gourl'];?>" class="input-txt">
              </label>
            </li>
            <a id="addRemoteTxt" class="ncap-btn" href="javascript:void(0);"><i class="fa fa-plus"></i><?php echo $lang['rec_ps_addjx'];?></a>
          </ul>
        </dd>
      </dl>
      <dl class="row" id="local_pic" style="display:none">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['rec_ps_selfile'];?></label>
        </dt>
        <dd class="opt">
          <ul class="ncap-ajax-add" id="UpFileBox">
            <li>
              <label>
              <div class="input-file-show"><span class="type-file-box">
                <input class="type-file-file" id="pic[]" name="pic[]" type="file" size="30" nc_type="change_default_goods_image"  hidefocus="true" title="点击按钮选择文件上传">
                <input type="text" name="textfield" id="textfield" class="type-file-text" />
                <input type="button" name="button" id="button" value="选择上传..." class="type-file-button" />
                </span></div>
              </label>
              <label title="<?php echo $lang['rec_ps_gourl'];?>"><i class="fa fa-link"></i>
                <input type="text" value="http://" name="urlup[]" placeholder="<?php echo $lang['rec_ps_gourl'];?>" class="input-txt">
              </label>
            </li>
            <a id="addUpFile" class="ncap-btn" href="javascript:void(0);"><i class="fa fa-plus"></i><?php echo $lang['rec_ps_addjx'];?></a>
          </ul>
        </dd>
      </dl>
      <dl id="remote_pic" class="row" style="display:none">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['rec_ps_add_remote'];?></label>
        </dt>
        <dd class="opt">
          <ul class="ncap-ajax-add" id="RemoteBox">
            <li>
              <label>
                <input type="text" value="" name="pic[]" placeholder="<?php echo $lang['rec_ps_remote_url'];?>" class="input-txt" hidefocus="true">
              </label>
              <label title="<?php echo $lang['rec_ps_gourl'];?>"><i class="fa fa-link"></i>
                <input type="text" value="http://" name="urlremote[]" placeholder="<?php echo $lang['rec_ps_gourl'];?>" class="input-txt">
              </label>
            </li>
            <a id="addRemote" class="ncap-btn" href="javascript:void(0);"><i class="fa fa-plus"></i><?php echo $lang['rec_ps_addjx'];?></a>
          </ul>
        </dd>
      </dl>
      <dl class="row" id="rec_width">
        <dt class="tit">
          <label><?php echo $lang['rec_ps_kcg'];?></label>
        </dt>
        <dd class="opt"><?php echo $lang['rec_ps_image_width'];?>:
          <input type="text" class="txt" value="" style="width:30px" name="rwidth">
          px&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $lang['rec_ps_image_height'];?>:
          <input type="text" class="txt" value="" style="width:30px" name="rheight">
          px
          <p class="notic"><?php echo $lang['rec_ps_kcg_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['rec_ps_target'];?></label>
        </dt>
        <dd class="opt">
          <label>
            <input name="rtarget"  type="radio" value="1" checked="checked">
            <?php echo $lang['rec_ps_tg1'];?></label>
          <label>
            <input type="radio" name="rtarget" value="2">
            <?php echo $lang['rec_ps_tg2'];?></label>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
//按钮先执行验证再提交表单
$(function(){
	function _check(){
		if ($('#rec_type').val() == 1){
			flag = false;
			$('input[name="txt[]"]').each(function(){
				if ($(this).val() != '') flag = true;
			});
			if (flag == false){
				alert('<?php echo $lang['rec_ps_error_ztxt'];?>');return false;
			}else{
				flag = false;
			}
		}else{
			if ($('#pic_type_1').attr('checked')){
				flag = false;
				$('#UpFileBox').find('input[name="pic[]"]').each(function(){
					if ($(this).val() != '') flag = true;
				});
				if (flag == false){
					alert('<?php echo $lang['rec_ps_error_pics'];?>');return false;
				}else{
					flag = false;
				}
			}else{
				flag = false;
				$('#RemoteBox').find('input[name="pic[]"]').each(function(){
					if ($(this).val() != '' && $(this).val() != 'http://') flag = true;
				});
				if (flag == false){
					alert('<?php echo $lang['rec_ps_error_picy'];?>');return false;
				}else{
					flag = false;
				}
			}
		}
		return true;
	}

	$("#submitBtn").click(function(){
		if(_check()){
			$("#rec_form").submit();
		}
	});
	$("#addUpFile").live('click',function(){
		if ($('#UpFileBox').find('input[name="pic[]"]').size() >= 5){
			alert('<?php echo $lang['rec_ps_error_jz'];?>');return;
		}
		$(this).remove();
   		$('#UpFileBox').append("<li><label><div class=\"input-file-show\"><span class=\"type-file-box\"><input class=\"type-file-file\" id=\"fileupload\" name=\"pic[]\" type=\"file\" size=\"30\" nc_type=\"change_default_goods_image\" hidefocus=\"true\" title=\"点击按钮选择文件上传\"><input type=\"text\" name=\"textfield\" id=\"textfield\" class=\"type-file-text\"/><input type=\"button\" name=\"button\" id=\"button\" value=\"选择上传...\" class=\"type-file-button\"></span></div></label><label title=\"<?php echo $lang['rec_ps_gourl'];?>\"><i class=\"fa fa-link\"></i><input type=\"text\" value=\"http://\" name=\"urlup[]\" placeholder=\"<?php echo $lang['rec_ps_gourl'];?>\" class=\"input-txt\"></label><a id=\"delUpFile\" href=\"javascript:void(0);\" class=\"ncap-btn ncap-btn-red\"><?php echo $lang['nc_del'];?></a></li><a id=\"addUpFile\" class=\"ncap-btn\" href=\"javascript:void(0);\"><i class=\"fa fa-plus\"></i><?php echo $lang['rec_ps_addjx'];?></a>");
	});
	$("#addRemote").live('click',function(){
		if ($('#RemoteBox').find('input[name="pic[]"]').size() >= 5){
			alert('<?php echo $lang['rec_ps_error_jz'];?>');return;
		}
		$(this).remove();
   		$('#RemoteBox').append("<li><label><input type=\"text\" value=\"\" name=\"pic[]\" placeholder=\"<?php echo $lang['rec_ps_remote_url'];?>\" class=\"input-txt\" hidefocus=\"true\"></label><label title=\"<?php echo $lang['rec_ps_gourl'];?>\"><i class=\"fa fa-link\"></i><input type=\"text\" value=\"http://\" name=\"urlremote[]\" placeholder=\"<?php echo $lang['rec_ps_gourl'];?>\" class=\"input-txt\"></label><a id=\"delUpFile\" href=\"javascript:void(0);\" class=\"ncap-btn ncap-btn-red\"><?php echo $lang['nc_del'];?></a></li><a id=\"addRemote\" class=\"ncap-btn\" href=\"javascript:void(0);\"><i class=\"fa fa-plus\"></i><?php echo $lang['rec_ps_addjx'];?></a>");
	});
	$("#addRemoteTxt").live('click',function(){
		if ($('#RemoteBoxTxt').find('input[name="txt[]"]').size() >= 5){
			alert('<?php echo $lang['rec_ps_error_jz'];?>');return;
		}
		$(this).remove();
   		$('#RemoteBoxTxt').append("<li><label><input type=\"text\" value=\"\" name=\"txt[]\" placeholder=\"输入要展示的文字内容\" class=\"input-txt\" hidefocus=\"true\"></label><label title=\"<?php echo $lang['rec_ps_gourl'];?>\"><i class=\"fa fa-link\"></i><input type=\"text\" value=\"http://\" name=\"urltxt[]\" placeholder=\"<?php echo $lang['rec_ps_gourl'];?>\" class=\"input-txt\"></label><a id=\"delUpFile\" href=\"javascript:void(0);\" class=\"ncap-btn ncap-btn-red\"><?php echo $lang['nc_del'];?></a></li><a id=\"addRemoteTxt\" class=\"ncap-btn\" href=\"javascript:void(0);\"><i class=\"fa fa-plus\"></i><?php echo $lang['rec_ps_addjx'];?></a>");
	});	
	$('#delUpFile').live('click',function(){
		$(this).parent().remove();$(this).remove();
	});
	$('input[name="pic_type"]').live('click',function(){
		if($(this).val() == 1) {
			$('#local_pic').show();$('#remote_pic').hide();
		}else{
			$('#local_pic').hide();$('#remote_pic').show();
		}
	});
	$('#rec_type').change(function(){
		if ($(this).val() == 1){
			$('#local_txt').show();$('#tr_pic_type').hide();$('#local_pic').hide();$('#remote_pic').hide();$('#rec_width').hide();
		}else{
			$('#local_txt').hide();$('#tr_pic_type').show();$('#local_pic').show();$('#pic_type_1').attr('checked',true);$('#rec_width').show();
		}
	});
	$('#local_pic').show();
	$('#tr_pic_type').show();
});
</script> 
<script type="text/javascript">
$(function(){
	$('input[nc_type="change_default_goods_image"]').live("change", function(){
		$(this).parent().find('input[class="type-file-text"]').val($(this).val());
	});
});
</script> 