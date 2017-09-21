<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=rec_position&op=rec_list" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['rec_position'];?> - <?php echo $lang['nc_edit'];?>“<?php echo $output['info']['title'];?>”</h3>
        <h5><?php echo $lang['rec_position_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="rec_form" enctype="multipart/form-data" method="post" action="index.php?act=rec_position&op=rec_edit_save">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="rec_id" value="<?php echo $output['info']['rec_id'];?>" />
    <input type="hidden" name="opic_type" value="<?php echo $output['info']['pic_type'];?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['rec_ps_title'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="rtitle" value="<?php echo $output['info']['title'];?>" id="rtitle" class="input-txt">
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
            <option value="2" <?php if ($output['info']['pic_type'] !=0) echo 'selected';?>><?php echo $lang['rec_ps_pic'];?></option>
            <option value="1" <?php if ($output['info']['pic_type'] ==0) echo 'selected';?>><?php echo $lang['rec_ps_txt'];?></option>
          </select>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['rec_ps_type_tips'];?></p>
        </dd>
      </dl>
      <dl class="row" id="tr_pic_type" style="display:none">
        <dt class="tit"><?php echo $lang['rec_ps_select_pic']?></dt>
        <dd class="opt">
          <ul>
            <li>
              <label>
                <input name="pic_type" id="pic_type_1" type="radio" value="1" <?php if ($output['info']['pic_type'] ==1 || $output['info']['pic_type'] ==0) echo 'checked="checked"';?>>
                <?php echo $lang['rec_ps_local'];?></label>
            </li>
            <li>
              <label>
                <input type="radio" name="pic_type" id="pic_type_2" value="2" <?php if ($output['info']['pic_type'] ==2) echo 'checked="checked"';?>>
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
                <input type="text" value="" name="txt[]" size="30" hidefocus="true" placeholder="输入要展示的文字内容" class="input-txt">
              </label>
              <label title="<?php echo $lang['rec_ps_gourl'];?>"><i class="fa fa-link"></i>
                <input type="text" value="http://" name="urltxt[]" placeholder="<?php echo $lang['rec_ps_gourl'];?>" class="input-txt">
              </label>
            </li>
            <a id="addRemoteTxt" class="ncap-btn" href="javascript:void(0);"><i class="fa fa-plus"></i><?php echo $lang['rec_ps_addjx'];?></a>
          </ul>
        </dd>
      </dl>
      <dl class="row" id="local_pic" style="display:none">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['rec_ps_selfile_edit'];?></label>
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
            <a id="addUpFile" href="javascript:void(0);" class="ncap-btn"><i class="fa fa-plus"></i><?php echo $lang['rec_ps_addjx'];?></a>
          </ul>
        </dd>
      </dl>
      <dl id="remote_pic" class="row" style="display:none">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['rec_ps_edit_remote'];?></label>
        </dt>
        <dd class="opt">
          <ul class="ncap-ajax-add" id="RemoteBox">
            <li>
              <label>
                <input type="text" value="http://" name="pic[]" placeholder="<?php echo $lang['rec_ps_remote_url'];?>" class="input-txt" hidefocus="true">
              </label>
              <label title="<?php echo $lang['rec_ps_gourl'];?>"><i class="fa fa-link"></i>
                <input type="text" value="http://" name="urlremote[]" placeholder="<?php echo $lang['rec_ps_gourl'];?>" class="input-txt">
              </label>
            </li>
            <a id="addRemote" class="ncap-btn"  href="javascript:void(0);"><i class="fa fa-plus"></i><?php echo $lang['rec_ps_addjx'];?></a>
          </ul>
        </dd>
      </dl>
      <dl class="row" id="rec_width">
        <dt class="tit">
          <label><?php echo $lang['rec_ps_kcg'];?></label>
        </dt>
        <dd class="opt"><?php echo $lang['rec_ps_image_width'];?>:
          <input type="text" style="width:30px" name="rwidth" value="<?php echo $output['info']['content']['width'];?>">
          px&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $lang['rec_ps_image_height'];?>:
          <input type="text" value="<?php echo $output['info']['content']['height'];?>" style="width:30px" name="rheight">
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
            <input name="rtarget"  type="radio" value="1" <?php if($output['info']['content']['target']==1) echo 'checked="checked"'?>>
            <?php echo $lang['rec_ps_tg1'];?></label>
          <label>
            <input type="radio" name="rtarget" value="2" <?php if($output['info']['content']['target']==2) echo 'checked="checked"'?>>
            <?php echo $lang['rec_ps_tg2'];?></label>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" charset="utf-8"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css"/>
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
			}else{
				if ($('#RemoteBox').find('input[name="pic[]"]').first().val() == ''){
					alert('<?php echo $lang['rec_ps_error_picy'];?>');return false;
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
	$("#remote_pic,#local_pic").on('click','#addUpFile',function(){
		if ($('#UpFileBox').find('input[name="pic[]"]').size() >= 5){
			alert('<?php echo $lang['rec_ps_error_jz'];?>');return;
		}
		$(this).remove();
   		$('#UpFileBox').append("<li><label><div class=\"input-file-show\"><span class=\"type-file-box\"><input type=\"text\" name=\"textfield\" class=\"type-file-text\"><input type=\"button\" name=\"button\" value=\"选择上传...\" class=\"type-file-button\"><input class=\"type-file-file\" type=\"file\" title=\"\" nc_type=\"change_default_goods_image\" hidefocus=\"true\" size=\"30\" name=\"pic[]\"></span></div></label><label title=\"<?php echo $lang['rec_ps_gourl'];?>\"><i class=\"fa fa-link\"></i><input type=\"text\" value=\"http://\" name=\"urlup[]\" placeholder=\"<?php echo $lang['rec_ps_gourl'];?>\" class=\"input-txt\"></label><a id=\"delUpFile\" href=\"javascript:void(0);\" class=\"ncap-btn ncap-btn-red\"><?php echo $lang['nc_del'];?></a></li><a id=\"addUpFile\" class=\"ncap-btn\" href=\"javascript:void(0);\"><i class=\"fa fa-plus\"></i><?php echo $lang['rec_ps_addjx'];?></a>");
	});
	$("#remote_pic,#local_pic").on('click','#addRemote',function(){
		if ($('#RemoteBox').find('input[name="pic[]"]').size() >= 5){
			alert('<?php echo $lang['rec_ps_error_jz'];?>');return;
		}
		$(this).remove();
   		$('#RemoteBox').append("<li><lable><input type=\"text\" value=\"http://\" name=\"pic[]\" placeholder=\"<?php echo $lang['rec_ps_remote_url'];?>\" class=\"input-txt\" hidefocus=\"true\"></label><label title=\"<?php echo $lang['rec_ps_gourl'];?>\"></label><label title=\"<?php echo $lang['rec_ps_gourl'];?>\"><i class=\"fa fa-link\"></i><input type=\"text\" value=\"http://\" name=\"urlremote[]\" placeholder=\"<?php echo $lang['rec_ps_gourl'];?>\" class=\"input-txt\"></label><a id=\"delUpFile\" href=\"javascript:void(0);\" class=\"ncap-btn ncap-btn-red\"><?php echo $lang['nc_del'];?></a></li><a id=\"addRemote\" class=\"ncap-btn\" href=\"javascript:void(0);\"><i class=\"fa fa-plus\"></i><?php echo $lang['rec_ps_addjx'];?></a>");
	});
	$("#local_txt").on('click','#addRemoteTxt',function(){
		if ($('#RemoteBoxTxt').find('input[name="txt[]"]').size() >= 5){
			alert('<?php echo $lang['rec_ps_error_jz'];?>');return;
		}
		$(this).remove();
   		$('#RemoteBoxTxt').append("<li><label><input type=\"text\" value=\"\" name=\"txt[]\" placeholder=\"输入要展示的文字内容\" class=\"input-txt\" hidefocus=\"true\"></label><label title=\"<?php echo $lang['rec_ps_gourl'];?>\"><i class=\"fa fa-link\"></i><input type=\"text\" value=\"http://\" name=\"urltxt[]\" placeholder=\"<?php echo $lang['rec_ps_gourl'];?>\" class=\"input-txt\"></label><a id=\"delUpFile\" href=\"javascript:void(0);\" class=\"ncap-btn ncap-btn-red\"><?php echo $lang['nc_del'];?></a></li><a id=\"addRemoteTxt\" class=\"ncap-btn\" href=\"javascript:void(0);\"><i class=\"fa fa-plus\"></i><?php echo $lang['rec_ps_addjx'];?></a>");
	});
	$('#remote_pic,#local_pic,#local_txt').on('click','#delUpFile',function(){
		$(this).parents('li').remove();
	});
	$('input[name="pic_type"]').on('click',function(){
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
	<?php if ($output['info']['pic_type']==0){?>
		$('#local_pic').hide();$('#tr_pic_type').hide();$('#rec_width').hide();$('#local_txt').show();
		$('#RemoteBoxTxt').find('input[name="txt[]"]').eq(0).val('<?php echo $output['info']['content']['body'][0]['title'];?>');
		$('#RemoteBoxTxt').find('input[name="urltxt[]"]').eq(0).val('<?php echo $output['info']['content']['body'][0]['url'];?>');
		<?php for ($i=1;$i<count($output['info']['content']['body']);$i++){?>
			$('#addRemoteTxt').click();
			$('#RemoteBoxTxt').find('input[name="txt[]"]').eq(<?php echo $i;?>).val('<?php echo $output['info']['content']['body'][$i]['title'];?>');
			$('#RemoteBoxTxt').find('input[name="urltxt[]"]').eq(<?php echo $i;?>).val('<?php echo $output['info']['content']['body'][$i]['url'];?>');		
		<?php }?>
	<?php }elseif ($output['info']['pic_type'] == 1){?>
		$('#UpFileBox').find('li').eq(0).find('label').eq(0).find('div').append('<span class="show"><a class="nyroModal" href="<?php echo $output['info']['content']['body'][0]['title'];?>" rel="gal"> <i class="fa fa-picture-o" onMouseOut="toolTip()" onMouseOver="toolTip(\'<img src=<?php echo $output['info']['content']['body'][0]['title'];?>>\')"></i></a></span><input type="hidden" name="opic[]" value="<?php echo $output['info']['content']['body'][0]['title'];?>">');
		$('#UpFileBox').find('input[name="urlup[]"]').eq(0).val('<?php echo $output['info']['content']['body'][0]['url'];?>');
		<?php for ($i=1;$i<count($output['info']['content']['body']);$i++){?>
			$('#addUpFile').click();
			$('#UpFileBox').find('li').eq(<?php echo $i;?>).find('label').eq(0).find('div').append('<span class="show"><a class="nyroModal" href="<?php echo $output['info']['content']['body'][$i]['title'];?>" rel="gal"> <i class="fa fa-picture-o" onMouseOut="toolTip()" onMouseOver="toolTip(\'<img src=<?php echo $output['info']['content']['body'][$i]['title'];?>>\')"></i></a></span><input type="hidden" name="opic[]" value="<?php echo $output['info']['content']['body'][$i]['title'];?>">');
			$('#UpFileBox').find('input[name="urlup[]"]').eq(<?php echo $i;?>).val('<?php echo $output['info']['content']['body'][$i]['url'];?>');
		<?php }?>		
	<?php }elseif ($output['info']['pic_type'] == 2){?>
		$('#local_pic').hide();$('#remote_pic').show();
		$('#RemoteBox').find('input[name="pic[]"]').eq(0).val('<?php echo $output['info']['content']['body'][0]['title'];?>');
		$('#RemoteBox').find('input[name="urlremote[]"]').eq(0).val('<?php echo $output['info']['content']['body'][0]['url'];?>');
		$('#RemoteBox').find('input[name="pic[]"]').eq(0).after('<span class="show"><a class="nyroModal" href="<?php echo $output['info']['content']['body'][0]['title'];?>" rel="gal"> <i class="fa fa-picture-o" onMouseOut="toolTip()" onMouseOver="toolTip(\'<img src=<?php echo $output['info']['content']['body'][0]['title'];?>>\')"></i></a></span>');
		<?php for ($i=1;$i<count($output['info']['content']['body']);$i++){?>
			$('#addRemote').click();
			$('#RemoteBox').find('input[name="pic[]"]').eq(<?php echo $i;?>).val('<?php echo $output['info']['content']['body'][$i]['title'];?>');
			$('#RemoteBox').find('input[name="urlremote[]"]').eq(<?php echo $i;?>).val('<?php echo $output['info']['content']['body'][$i]['url'];?>');	
			$('#RemoteBox').find('input[name="pic[]"]').eq(<?php echo $i;?>).after('
			<span class="show"><a class="nyroModal" href="<?php echo $output['info']['content']['body'][$i]['title'];?>" rel="gal"> <i class="fa fa-picture-o" onMouseOut="toolTip()" onMouseOver="toolTip(\'<img src=<?php echo $output['info']['content']['body'][$i]['title'];?>>\')"></i></a></span>');
		<?php }?>
	<?php }?>
	// 点击查看图片
	$('.nyroModal').nyroModal();
	// 显示隐藏预览图 start
	$(".show_image").on('hover',function(event){
		if(event.type=='mouseenter'){
			$(this).next().css('display','block');
		}else{
			$(this).next().css('display','none');
		}
	});
});
</script> 
<script type="text/javascript">
$(function(){
	$('input[nc_type="change_default_goods_image"]').on("change", function(){
		$(this).parent().find('input[class="type-file-text"]').val($(this).val());
	});
});
</script> 