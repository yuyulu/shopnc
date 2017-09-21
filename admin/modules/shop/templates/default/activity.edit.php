<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=activity&op=activity" title="返回<?php echo $lang['nc_manage'];?>列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['activity_index_manage'];?> - <?php echo $lang['nc_edit'];?>活动“<?php echo $output['activity']['activity_title'];?>”</h3>
        <h5><?php echo $lang['activity_index_manage_subhead']; ?></h5>
      </div>
    </div>
  </div>
  <form id="add_form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="activity_id" value="<?php echo $output['activity']['activity_id'];?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="activity_title"><em>*</em><?php echo $lang['activity_index_title'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" id="activity_title" name="activity_title" class="input-txt" value="<?php echo $output['activity']['activity_title'];?>">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row" style="display:none;">
        <dt class="tit">
          <label for="activity_type"><?php echo $lang['activity_index_type'];?></label>
        </dt>
        <dd class="opt">
          <select name="activity_type" id="activity_type">
            <option value="1" <?php if($output['activity']['activity_type']=='1'){?>selected<?php }?>><?php echo $lang['activity_index_goods'];?></option>
            <option value="2" <?php if($output['activity']['activity_type']=='2'){?>selected<?php }?>><?php echo $lang['activity_index_group'];?></option>
          </select>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['activity_new_type_tip'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="activity_start_date"><em>*</em><?php echo $lang['activity_index_start'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" id="activity_start_date" class="input-txt" name="activity_start_date" value="<?php echo date('Y-m-d',$output['activity']['activity_start_date']);?>"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="activity_end_date"><em>*</em><?php echo $lang['activity_index_end'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" id="activity_end_date" class="input-txt" name="activity_end_date" value="<?php if(!empty($output['activity']['activity_end_date']))echo date('Y-m-d',$output['activity']['activity_end_date']);?>"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="activity_banner"><em>*</em><?php echo $lang['activity_index_banner'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"> <a class="nyroModal" rel="gal" href="<?php if(is_file(BASE_UPLOAD_PATH.DS.ATTACH_ACTIVITY.DS.$output['activity']['activity_banner'])){echo UPLOAD_SITE_URL."/".ATTACH_ACTIVITY."/".$output['activity']['activity_banner'];}else{echo ADMIN_SITE_URL."/templates/".TPL_NAME."/images/sale_banner.jpg";}?>"> <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php if(is_file(BASE_UPLOAD_PATH.DS.ATTACH_ACTIVITY.DS.$output['activity']['activity_banner'])){echo UPLOAD_SITE_URL."/".ATTACH_ACTIVITY."/".$output['activity']['activity_banner'];}else{echo ADMIN_SITE_URL."/templates/".TPL_NAME."/images/sale_banner.jpg";}?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input type="file" class="type-file-file" id="activity_banner" name="activity_banner" size="30" hidefocus="true"  nc_type="upload_activity_banner" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            </span></div>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['activity_new_banner_tip'];?></p>
        </dd>
      </dl>
      <dl class="row" style="display:none;">
        <dt class="tit">
          <label for="activity_style"><?php echo $lang['activity_new_style'];?></label>
        </dt>
        <dd class="opt">
          <select id="activity_style" name="activity_style">
            <option value="default_style" <?php if($output['activity']['activity_style']=="default_style"){?>selected<?php }?>><?php echo $lang['activity_index_default'];?></option>
          </select>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['activity_new_style_tip'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="activity_desc"><?php echo $lang['activity_new_desc'];?></label>
        </dt>
        <dd class="opt">
          <textarea name="activity_desc" rows="6" class="tarea" id="activity_desc"><?php echo nl2br($output['activity']['activity_desc']);?></textarea>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="activity_sort"><em>*</em><?php echo $lang['nc_sort'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" id="activity_sort" name="activity_sort" class="input-txt" value="<?php if($output['activity']['activity_sort']==''){?>255<?php }elseif($output['activity']['activity_sort']=='0'){ echo '0';}else{ echo $output['activity']['activity_sort'];}?>">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['activity_new_sort_tip1'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="activity_sort"><?php echo $lang['activity_openstate'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="activity_state1" class="cb-enable <?php echo $output['activity']['activity_state'] == 1?'selected':'';?>" ><?php echo $lang['activity_openstate_open'];?></label>
            <label for="activity_state0" class="cb-disable <?php echo $output['activity']['activity_state'] == 0?'selected':'';?>"><?php echo $lang['activity_openstate_close'];?></label>
            <input id="activity_state1" name="activity_state" <?php if($output['activity']['activity_state'] == 1){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="activity_state0" name="activity_state" <?php if($output['activity']['activity_state'] == 0){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#add_form").valid()){
     $("#add_form").submit();
	}
	});
});
$(document).ready(function(){
	$("#activity_start_date").datepicker();
	$("#activity_end_date").datepicker();
	$("#add_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
	        	activity_title: {
	    		required : true
	    	},
	    	activity_start_date: {
	    		required : true,
				date      : false
	    	},
	    	activity_end_date: {
	    		required : true,
				date      : false
	    	},
	    	activity_sort: {
	    		required : true,
	    		min:0,
	    		max:255
	    	}
        },
        messages : {
        	activity_title: {
	    		required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['activity_new_title_null'];?>'
	    	},
	    	activity_start_date: {
	    		required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['activity_new_startdate_null'];?>'
	    	},
	    	activity_end_date: {
	    		required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['activity_new_enddate_null'];?>'
	    	},
	    	activity_sort: {
	    		required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['activity_new_sort_null'];?>',
	    		min:'<i class="fa fa-exclamation-circle"></i><?php echo $lang['activity_new_sort_minerror'];?>',
	    		max:'<i class="fa fa-exclamation-circle"></i><?php echo $lang['activity_new_sort_maxerror'];?>'
	    	}
        }
	});
});

$(function(){
// 模拟活动页面横幅Banner上传input type='file'样式
	var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />"
    $(textButton).insertBefore("#activity_banner");
    $("#activity_banner").change(function(){
	$("#textfield1").val($("#activity_banner").val());
    });
$('.nyroModal').nyroModal();
});
</script>