<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <a class="back" href="index.php?act=activity&op=activity" title="返回活动列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['activity_index_manage'];?> - <?php echo $lang['nc_new'];?></a></h3>
        <h5><?php echo $lang['activity_index_manage_subhead']; ?></h5>
      </div>
    </div>
  </div>
  <form id="add_form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="activity_title"><em>*</em><?php echo $lang['activity_index_title'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" id="activity_title" name="activity_title" class="input-txt" >
          <span class="err"></span>
          <p class="notic">
            <?php //echo $lang['activity_new_title_tip'];?>
          </p>
        </dd>
      </dl>
      <dl class="row" style="display:none;">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['activity_index_type'];?></label>
        </dt>
        <dd class="opt">
          <select name="activity_type">
            <option value="1"><?php echo $lang['activity_index_goods'];?></option>
            <option value="2"><?php echo $lang['activity_index_group'];?></option>
            </optgroup>
          </select>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['activity_new_type_tip'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['activity_index_start'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" id="activity_start_date" name="activity_start_date" class="input-txt" />
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['activity_index_end'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" id="activity_end_date" name="activity_end_date" class="input-txt"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['activity_index_banner'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="type-file-box">
            <input type="file" class="type-file-file" id="activity_banner" name="activity_banner" size="30" hidefocus="true"  nc_type="upload_activity_banner" title="点击按钮选择文件并提交表单后上传生效">
            </span></div>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['activity_new_banner_tip'];?></p>
        </dd>
      </dl>
      <dl class="row" style="display:none;">
        <dt class="tit">
          <label><?php echo $lang['activity_new_style'];?></label>
        </dt>
        <dd class="opt">
          <select id="activity_style" name="activity_style">
            <option value="default_style"><?php echo $lang['activity_index_default'];?></option>
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
          <textarea name="activity_desc" id="activity_desc" rows="6" class="tarea"></textarea>
          <span class="err"></span>
          <p class="notic">&nbsp;</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="activity_sort"><em>*</em><?php echo $lang['nc_sort'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" id="activity_sort" name="activity_sort" class="input-txt" value="0">
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
            <label for="activity_state1" class="cb-enable selected" ><?php echo $lang['activity_openstate_open'];?></label>
            <label for="activity_state0" class="cb-disable"><?php echo $lang['activity_openstate_close'];?></label>
            <input id="activity_state1" name="activity_state" checked="checked" value="1" type="radio">
            <input id="activity_state0" name="activity_state" value="0" type="radio">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#add_form").valid()){
     $("#add_form").submit();
	}
	});
});
$(document).ready(function(){
	$("#activity_start_date").datepicker({dateFormat: 'yy-mm-dd'});
	$("#activity_end_date").datepicker({dateFormat: 'yy-mm-dd'});
	$("#add_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parents('dl').find('span.err');
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
        	activity_banner: {
        		required: true,
				accept : 'png|jpe?g|gif'
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
			activity_banner: {
        		required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['activity_new_banner_null'];?>',
				accept   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['activity_new_ing_wrong'];?>'
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
});
</script>