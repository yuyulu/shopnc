<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"> <a class="back" href="<?php echo urlAdminShop('pointprod', 'index'); ?>" title="返回列表"> <i class="fa fa-arrow-circle-o-left"></i> </a>
      <div class="subject">
        <h3>积分兑换 - 编辑礼品“<?php echo $output['prod_info']['pgoods_name']; ?>”</h3>
        <h5>编辑商城会员可使用积分兑换的礼品</h5>
      </div>
    </div>
  </div>
  <form id="pointprod_form" method="post" enctype="multipart/form-data" >
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <div class="title">
        <h3><?php echo $lang['admin_pointprod_baseinfo']; ?></h3>
      </div>
      <dl class="row">
        <dt class="tit">
          <label for="goodsname"><em>*</em><?php echo $lang['admin_pointprod_goods_name']; ?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="goodsname" id="goodsname" class="input-txt" value="<?php echo $output['prod_info']['pgoods_name']; ?>"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="goodsserial"><em>*</em><?php echo $lang['admin_pointprod_goods_serial']; ?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="goodsserial" id="goodsserial" class="input-txt" value="<?php echo $output['prod_info']['pgoods_serial']; ?>"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="goodsprice"><em>*</em><?php echo $lang['admin_pointprod_goods_price']; ?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="goodsprice" id="goodsprice" class="input-txt" value="<?php echo $output['prod_info']['pgoods_price']; ?>"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="goodspoints"><em>*</em><?php echo $lang['admin_pointprod_goods_points']; ?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="goodspoints" id="goodspoints" class="txt" value="<?php echo $output['prod_info']['pgoods_points']; ?>"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for=""><?php echo $lang['admin_pointprod_goods_image'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo $output['prod_info']['pgoods_image'];?>"><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo $output['prod_info']['pgoods_image'];?>>')" onMouseOut="toolTip()" onerror="this.src='<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('default_goods_image');?>'"nc_type="goods_image" /></i></a></span><span class="type-file-box">
            <input name="goods_images" type="file" class="type-file-file" id="goods_images" size="30" hidefocus="true" nc_type="change_goods_image">
            </span></div>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="goodstag"><?php echo $lang['admin_pointprod_goods_tag']; ?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="goodstag" id="goodstag" class="input-txt" value="<?php echo $output['prod_info']['pgoods_tag']; ?>"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="goodsstorage"><em>*</em><?php echo $lang['admin_pointprod_goods_storage']; ?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="goodsstorage" id="goodsstorage" class="input-txt" value="<?php echo $output['prod_info']['pgoods_storage']; ?>"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="title">
        <h3><?php echo $lang['admin_pointprod_requireinfo']; ?></h3>
      </div>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_pointprod_limittip']; ?></label>
        </dt>
        <dd class="opt">
          <input type="radio" name="islimit" id="islimit_1" value="1" <?php if ($output['prod_info']['pgoods_islimit'] == 1){echo 'checked=checked';} ?>  onclick="showlimit();"/>
          <label for=""><?php echo $lang['admin_pointprod_limit_yes']; ?></label>
          <input type="radio" name="islimit" id="islimit_0" value="0" <?php if ($output['prod_info']['pgoods_islimit'] == 0){echo 'checked=checked';} ?> onclick="showlimit();"/>
          <label><?php echo $lang['admin_pointprod_limit_no']; ?></label>
        </dd>
      </dl>
      <dl id="limitnum_div" class="row">
        <dt class="tit">
          <label for="limitnum"><?php echo $lang['admin_pointprod_limitnum']; ?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="limitnum" id="limitnum" class="input-txt" value="<?php echo $output['prod_info']['pgoods_limitnum']; ?>"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_pointprod_limittimetip']; ?></label>
        </dt>
        <dd class="opt">
          <input type="radio" name="islimittime" id="islimittime_1" value="1" <?php if ($output['prod_info']['pgoods_islimittime'] == 1){echo 'checked=checked';} ?> onclick="showlimittime();"/>
          <label for="islimittime_1"><?php echo $lang['admin_pointprod_limittime_yes']; ?></label>
          <input type="radio" name="islimittime" id="islimittime_0" value="0" <?php if ($output['prod_info']['pgoods_islimittime'] == 0){echo 'checked=checked';} ?> onclick="showlimittime();"/>
          <label for="islimittime_0"><?php echo $lang['admin_pointprod_limittime_no']; ?></label>
        </dd>
      </dl>
      <dl class="row" name="limittime_div">
        <dt class="tit"><?php echo $lang['admin_pointprod_starttime']; ?>
          </label>
        </dt>
        <dd class="opt">
          <input type="text" name="starttime" id="starttime" class="input-txt" style="width:100px;" value="<?php echo $output['prod_info']['pgoods_starttime']? @date('Y-m-d',$output['prod_info']['pgoods_starttime']):@date('Y-m-d',time());?>"/>
          <?php echo $lang['admin_pointprod_time_day']; ?>
          <select id="starthour" name="starthour" style="width:50px; margin-left: 8px; _margin-left: 4px;">
            <?php foreach ($output['hourarr'] as $item){ ?>
            <option value="<?php echo $item; ?>" <?php if ($item == @date('H',$output['prod_info']['pgoods_starttime'])){ echo 'selected'; } ?>><?php echo $item; ?></option>
            <?php }?>
          </select>
          <?php echo $lang['admin_pointprod_time_hour']; ?><span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row" name="limittime_div">
        <dt class="tit">
          <label><?php echo $lang['admin_pointprod_endtime'] ?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="endtime" id="endtime" class="input-txt" style="width:100px;" value="<?php echo $output['prod_info']['pgoods_endtime']? @date('Y-m-d',$output['prod_info']['pgoods_endtime']):@date('Y-m-d',time()); ?>"/>
          <?php echo $lang['admin_pointprod_time_day']; ?>
          <select id="endhour" name="endhour" style="width:50px; margin-left: 8px; _margin-left: 4px;">
            <?php foreach ($output['hourarr'] as $item){ ?>
            <option value="<?php echo $item; ?>" <?php if ($item == @date('H',$output['prod_info']['pgoods_endtime'])){ echo 'selected'; } ?>><?php echo $item; ?></option>
            <?php }?>
          </select>
          <?php echo $lang['admin_pointprod_time_hour']; ?><span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>限制参与兑换的会员级别</label>
        </dt>
        <dd class="opt">
          <select name="limitgrade">
            <?php if ($output['member_grade']){?>
            <?php foreach ($output['member_grade'] as $k=>$v){?>
            <option <?php if(intval($output['prod_info']['pgoods_limitmgrade']) == intval($v['level'])){ ?>selected='selected'<?php } ?> value="<?php echo $v['level'];?>">V<?php echo $v['level'];?></option>
            <?php }?>
            <?php }?>
          </select>
          <p class="notic">当会员兑换积分商品时，需要达到该级别或者以上级别后才能参与兑换</p>
        </dd>
      </dl>
      <div class="title">
        <h3><?php echo $lang['admin_pointprod_stateinfo']; ?></h3>
      </div>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_pointprod_isshow']; ?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="showstate_1" class="cb-enable <?php if ($output['prod_info']['pgoods_show'] == '1'){ ?>selected<?php } ?>"><span><?php echo $lang['admin_pointprod_yes']; ?></span></label>
            <label for="showstate_0" class="cb-disable <?php if ($output['prod_info']['pgoods_show'] == '0'){ ?>selected<?php } ?>"><span><?php echo $lang['admin_pointprod_no']; ?></span></label>
            <input id="showstate_1" name="showstate" <?php if ($output['prod_info']['pgoods_show'] == '1'){echo 'checked=checked';}?> value="1" type="radio">
            <input id="showstate_0" name="showstate" <?php if ($output['prod_info']['pgoods_show'] == '0'){echo 'checked=checked';}?> value="0" type="radio">
          </div>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_pointprod_iscommend']; ?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="commendstate_1" class="cb-enable <?php if ($output['prod_info']['pgoods_commend'] == '1'){ ?>selected<?php } ?>"><span><?php echo $lang['admin_pointprod_yes']; ?></span></label>
            <label for="commendstate_0" class="cb-disable <?php if ($output['prod_info']['pgoods_commend'] == '0'){ ?>selected<?php } ?>"><span><?php echo $lang['admin_pointprod_no']; ?></span></label>
            <input id="commendstate_1" name="commendstate" <?php if ($output['prod_info']['pgoods_commend'] == '1'){echo 'checked=checked';}?> value="1" type="radio">
            <input id="commendstate_0" name="commendstate" <?php if ($output['prod_info']['pgoods_commend'] == '0'){echo 'checked=checked';}?> value="0" type="radio">
          </div>
        </dd>
      </dl>
      <dl class="row" id="forbidreason_div">
        <dt class="tit">
          <label for="forbidreason"><?php echo $lang['admin_pointprod_forbidreason']; ?></label>
        </dt>
        <dd class="opt">
          <textarea name="forbidreason" id="forbidreason" rows="6"><?php echo $output['prod_info']['pgoods_close_reason']; ?></textarea>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="title">
        <h3><?php echo $lang['admin_pointprod_seoinfo']; ?></h3>
      </div>
      <dl class="row">
        <dt class="tit">
          <label for="keywords"><?php echo $lang['admin_pointprod_seokey']; ?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="keywords" id="keywords" class="input-txt" value="<?php echo $output['prod_info']['pgoods_keywords']; ?>"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_pointprod_seodescription']; ?></label>
        </dt>
        <dd class="opt">
          <textarea name="description" rows="6" class="tarea"><?php echo $output['prod_info']['pgoods_description']; ?></textarea>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="title">
        <h3><?php echo $lang['admin_pointprod_otherinfo']; ?></h3>
      </div>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_pointprod_sort']; ?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="sort" id="sort" class="input-txt" value="<?php echo $output['prod_info']['pgoods_sort']; ?>"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_pointprod_edit_addtime']; ?></label>
        </dt>
        <dd class="opt"><?php echo @date('Y-m-d',$output['prod_info']['pgoods_add_time']); ?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_pointprod_edit_viewnum']; ?></label>
        </dt>
        <dd class="opt"><?php echo $output['prod_info']['pgoods_view']; ?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_pointprod_edit_salenum']; ?></label>
        </dt>
        <dd class="opt"><?php echo $output['prod_info']['pgoods_salenum']; ?></dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['admin_pointprod_descriptioninfo']; ?></dt>
        <dd class="opt">
          <?php showEditor('pgoods_body',$output['prod_info']['pgoods_body'],'600px','400px','visibility:hidden;',"false",$output['editor_multimedia']);?>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['admin_pointprod_uploadimg']; ?></dt>
        <dd class="opt" id="divComUploadContainer">
          <div class="input-file-show"><span class="type-file-box">
            <input class="type-file-file" id="fileupload" name="fileupload" type="file" size="30" multiple hidefocus="true" title="点击按钮选择文件上传">
            <input type="text" name="text" id="text" class="type-file-text" />
            <input type="button" name="button" id="button" value="选择上传..." class="type-file-button" />
            </span></div>
          <div id="thumbnails" class="ncap-thumb-list">
            <h5><i class="fa fa-exclamation-circle"></i>上传后的图片可以插入到富文本编辑器中使用，无用附件请手动删除，如不处理系统会始终保存该附件图片。</h5>
            <ul>
              <?php if(is_array($output['file_upload'])){?>
              <?php foreach($output['file_upload'] as $k => $v){ ?>
              <li id="<?php echo $v['upload_id'];?>" class="picture" >
                <input type="hidden" name="file_id[]" value="<?php echo $v['upload_id'];?>" />
                <div class="thumb-list-pics"><a href="javascript:void(0);"><img src="<?php echo $v['upload_path'];?>" alt="<?php echo $v['file_name'];?>"/></a></div>
                <a href="javascript:del_file_upload('<?php echo $v['upload_id'];?>');" class="del" title="<?php echo $lang['nc_del'];?>">X</a><a href="javascript:insert_editor('<?php echo $v['upload_path'];?>');" class="inset"><i class="fa fa-trash"></i>插入图片</a> </li>
              <?php } ?>
              <?php } ?>
            </ul>
          </div>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script>

function showlimit(){
	var islimit = $(":radio[name=islimit]:checked").val();
	if(islimit == '1'){
		$("#limitnum_div").show();
	}else{
		$("#limitnum_div").hide();
		$("#limitnum").val('1');//为了减少提交表单的验证，所以添加一个虚假值
	}
}
function showforbidreason(){
	var forbidstate = $(":radio[name=forbidstate]:checked").val();
	if(forbidstate == '1'){
		$("#forbidreason_div").show();
	}else{
		$("#forbidreason_div").hide();
	}
}
function showlimittime(){
	var islimit = $(":radio[name=islimittime]:checked").val();
	if(islimit == '1'){
		$("[name=limittime_div]").show();
	}else{
		$("[name=limittime_div]").hide();
		$("#starttime").val('<?php echo @date('Y-m-d',time()); ?>');
		$("#endtime").val('<?php echo @date('Y-m-d',time()); ?>');
	}
}
$(function(){
// 点击查看图片
	$('.nyroModal').nyroModal();
	// 模拟上传input type='file'样式
	$(function(){
	    var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />"
		$(textButton).insertBefore("#goods_images");
		$("#goods_images").change(function(){
		$("#textfield1").val($("#goods_images").val());
		});
	});

	$('input[nc_type="change_goods_image"]').change(function(){
		var src = getFullPath($(this)[0]);
		$('img[nc_type="goods_image"]').attr('src', src);
		$('input[nc_type="change_goods_image"]').removeAttr('name');
		$(this).attr('name', 'goods_image');
	});

	showlimit();
	showforbidreason();
	showlimittime();

	$('#starttime').datepicker({dateFormat: 'yy-mm-dd'});
	$('#endtime').datepicker({dateFormat: 'yy-mm-dd'});
	//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#pointprod_form").valid()){
     $("#pointprod_form").submit();
	}
	});
});
//
    $('#pointprod_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
        	goodsname : {
                required   : true
            },
            goodsprice    : {
				required  : true,
                number    : true,
                min       : 0
            },
            goodspoints : {
				required   : true,
				digits     : true,
				min		   :0
            },
            goodsserial : {
                required   : true
            },
            goodsstorage  : {
				required  : true,
                digits    : true
            },
            limitnum  : {
				required   : true,
				digits     : true,
				min        : 0
            },
            starttime  : {
				required  : true,
				date      : false
            },
            endtime  : {
				required  : true,
				date      : false
            },
            sort : {
				required  : true,
				digits    : true,
				min		  :0
            }
        },
        messages : {
        	goodsname  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_pointprod_add_goodsname_error']; ?>'
            },
            goodsprice : {
				required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_pointprod_add_goodsprice_null_error']; ?>',
                number   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_pointprod_add_goodsprice_number_error']; ?>',
                min     : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_pointprod_add_goodsprice_number_error']; ?>'
            },
            goodspoints : {
				required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_pointprod_add_goodspoint_null_error']; ?>',
				digits     : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_pointprod_add_goodspoint_number_error']; ?>',
				min		   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_pointprod_add_goodspoint_number_error']; ?>'
            },
            goodsserial:{
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_pointprod_add_goodsserial_null_error']; ?>'
            },
            goodsstorage : {
				required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_pointprod_add_storage_null_error']; ?>',
				digits  : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_pointprod_add_storage_number_error']; ?>'
            },
            limitnum : {
				required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_pointprod_add_limitnum_error']; ?>',
				digits  : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_pointprod_add_limitnum_digits_error']; ?>',
				min		: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_pointprod_add_limitnum_digits_error']; ?>'
            },
            starttime  : {
            	required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_pointprod_add_limittime_null_error']; ?>'
            },
            endtime  : {
            	required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_pointprod_add_limittime_null_error']; ?>'
            },
            sort : {
				required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_pointprod_add_sort_null_error']; ?>',
				digits  : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_pointprod_add_sort_number_error']; ?>',
				min		: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_pointprod_add_sort_number_error']; ?>'
            }
        }
    });

    // 替换图片
    $('#fileupload').each(function(){
        $(this).fileupload({
            dataType: 'json',
            url: 'index.php?act=pointprod&op=pointprod_pic_upload&item_id=<?php echo $output['prod_info']['pgoods_id']; ?>',
            done: function (e,data) {
                if(data != 'error'){
                	add_uploadedfile(data.result);
                }
            }
        });
    });
});
function add_uploadedfile(file_data)
{
	var newImg = '<li id="' + file_data.file_id + '" class="picture"><input type="hidden" name="file_id[]" value="' + file_data.file_id + '" /><div class="thumb-list-pics"><a href="javascript:void(0);"><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_POINTPROD.'/';?>' + file_data.file_name + '" alt="' + file_data.file_name + '" /></a></div></span><span><a href="javascript:del_file_upload(' + file_data.file_id + ');" class="del" title="<?php echo $lang['nc_del'];?>">X</a><a href="javascript:insert_editor(\'<?php echo UPLOAD_SITE_URL.'/'.ATTACH_POINTPROD.'/';?>' + file_data.file_name + '\');" class="inset"><i class="fa fa-clipboard"></i>插入图片</a</a></li>';
    $('#thumbnails > ul').prepend(newImg);
}
function insert_editor(file_path){
	KE.appendHtml('pgoods_body', '<img src="'+ file_path + '" alt="'+ file_path + '">');
}
function del_file_upload(file_id)
{
    if(!window.confirm('<?php echo $lang['nc_ensure_del'];?>')){
        return;
    }
    $.getJSON('index.php?act=pointprod&op=ajaxdelupload&file_id=' + file_id, function(result){
        if(result){
            $('#' + file_id).remove();
        }else{
            alert('<?php echo $lang['admin_pointprod_delfail'];?>');
        }
    });
}
</script> 
