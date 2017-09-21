<link type="text/css" rel="stylesheet" href="<?php echo RESOURCE_SITE_URL."/js/jquery-ui/themes/ui-lightness/jquery.ui.css";?>"/>

  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
	<div class="ncsc-form-default">
	  <form id="add_form" method="post" enctype="multipart/form-data" action="index.php?act=store_voucher&op=<?php echo $output['type']=='add'?'templateadd':'templateedit'; ?>">
	  	<input type="hidden" id="act" name="act" value="store_voucher"/>
	  	<?php if ($output['type'] == 'add'){?>
	  	<input type="hidden" id="op" name="op" value="templateadd"/>
	  	<?php }else {?>
	  	<input type="hidden" id="op" name="op" value="templateedit"/>
	  	<input type="hidden" id="tid" name="tid" value="<?php echo $output['t_info']['voucher_t_id'];?>"/>
	  	<?php }?>
	  	<input type="hidden" id="form_submit" name="form_submit" value="ok"/>
	    <dl>
	      <dt><i class="required">*</i><?php echo $lang['voucher_template_title'].$lang['nc_colon']; ?></dt>
	      <dd>
	        <input type="text" class="w300 text" name="txt_template_title" value="<?php echo $output['t_info']['voucher_t_title'];?>" maxlength=50 />
	        <span></span>
	      </dd>
	    </dl>
	    <?php if ($output['isOwnShop']) { ?>
	    <dl>
	      <dt><i class="required">*</i>店铺分类</dt>
	      <dd>
	        <select name="sc_id">
	           <option value="">店铺分类</option>
	           <?php foreach ($output['store_class'] as $k=>$v){?>
	           <option value="<?php echo $v['sc_id'];?>" <?php if ($output['t_info']['voucher_t_sc_id']==$v['sc_id']){ echo 'selected';}?>><?php echo $v['sc_name'];?></option>
	           <?php }?>
	        </select>
	        <span></span>
	      </dd>
	    </dl>
	    <?php } else {?>
	    <input type="hidden" name="sc_id" value="<?php echo $output['store_info']['sc_id'];?>"/>
	    <?php }?>
	  <dl>
		  <dt><i class="required">*</i>领取方式：</dt>
		  <dd>
			  <select name="gettype_sel" id="gettype_sel">
				  <option value="">请选择</option>
				  <?php foreach($output['gettype_arr'] as $k=>$v){ ?>
					  <option value="<?php echo $k; ?>" <?php echo $output['t_info']['voucher_t_gettype'] == $v['sign']?'selected':''; ?>><?php echo $v['name']; ?></option>
				  <?php } ?>
			  </select>
			  <span></span>
			  <p class="hint">“积分兑换”时会员可以在积分中心用积分进行兑换；“卡密兑换”时会员需要在“我的商城——我的代金券”中输入卡密获得代金券；<br>“免费领取”时会员可以点击店铺的代金券推广广告领取代金券。</p>
		  </dd>
	  </dl>
	    <dl>
	      <dt><em class="pngFix"></em><?php echo $lang['voucher_template_enddate'].$lang['nc_colon']; ?></dt>
	      <dd>
	      	<input type="text" class="text w70" id="txt_template_enddate" name="txt_template_enddate" value="" readonly><em class="add-on"><i class="icon-calendar"></i></em>
	        <span></span><p class="hint">
<?php if ($output['isOwnShop']) { ?>
            留空则默认30天之后到期
<?php } else { ?>
            <?php echo $lang['voucher_template_enddate_tip'];?><?php echo @date('Y-m-d',$output['quotainfo']['quota_starttime']);?> ~ <?php echo @date('Y-m-d',$output['quotainfo']['quota_endtime']);?>
<?php } ?>
            </p>
	      </dd>
	    </dl>
	    <dl>
	      <dt><?php echo $lang['voucher_template_price'].$lang['nc_colon']; ?></dt>
	      <dd>
	        <select id="select_template_price" name="select_template_price" class="w80 vt">
	          <?php if(!empty($output['pricelist'])) { ?>
	          	<?php foreach($output['pricelist'] as $voucher_price) {?>
	          	<option value="<?php echo $voucher_price['voucher_price'];?>" <?php echo $output['t_info']['voucher_t_price'] == $voucher_price['voucher_price']?'selected':'';?>><?php echo $voucher_price['voucher_price'];?></option>
	          <?php } } ?>
	        </select><em class="add-on"><i class="icon-renminbi"></i></em>
	        <span></span>
	      </dd>
	    </dl>
	    <dl>
	      <dt><i class="required">*</i><?php echo $lang['voucher_template_total'].$lang['nc_colon']; ?></dt>
	      <dd>
	        <input type="text" class="w70 text" name="txt_template_total" id="txt_template_total" value="<?php echo $output['t_info']['voucher_t_total']; ?>">
	        <span></span>
	        <p class="hint">如果代金券领取方式为卡密兑换，则发放总数应为1~1000之间的整数</p>
	      </dd>
	    </dl>
	    <dl id="eachlimit_dl">
	      <dt><i class="required">*</i><?php echo $lang['voucher_template_eachlimit'].$lang['nc_colon']; ?></dt>
	      <dd>
	      	<select name="eachlimit" class="w80">
	      		<option value="0"><?php echo $lang['voucher_template_eachlimit_item'];?></option>
	      		<?php for($i=1;$i<=intval(C('promotion_voucher_buyertimes_limit'));$i++){?>
	      		<option value="<?php echo $i;?>" <?php echo $output['t_info']['voucher_t_eachlimit'] == $i?'selected':'';?>><?php echo $i;?><?php echo $lang['voucher_template_eachlimit_unit'];?></option>
	      		<?php }?>
	        </select>
	      </dd>
	    </dl>
	    <dl>
	      <dt><i class="required">*</i><?php echo $lang['voucher_template_orderpricelimit'].$lang['nc_colon']; ?></dt>
	      <dd>
	        <input type="text" name="txt_template_limit" class="text w70" value="<?php echo $output['t_info']['voucher_t_limit'];?>"><em class="add-on"><i class="icon-renminbi"></i></em>
	        <span></span>
	        <p class="hint">如果消费金额设置为0，则表示不限制使用代金券的消费金额</p>
	      </dd>
	    </dl>
	    <dl id="mgrade_dl">
	      <dt>会员级别：</dt>
	      <dd>
	        <select name="mgrade_limit" class="w80">
    	        <?php if ($output['member_grade']){?>
                <?php foreach ($output['member_grade'] as $k=>$v){?>
                <option value="<?php echo $v['level'];?>" <?php echo $output['t_info']['voucher_t_mgradelimit'] == $v['level']?'selected':'';?>>V<?php echo $v['level'];?></option>
                <?php }?>
                <?php }?>
	        </select>
	        <p class="hint">当会员兑换代金券时，需要达到该级别或者以上级别后才能兑换领取</p>
	      </dd>
	    </dl>
	    <dl>
	      <dt><i class="required">*</i><?php echo $lang['voucher_template_describe'].$lang['nc_colon']; ?></dt>
	      <dd>
	        <textarea  name="txt_template_describe" class="textarea w400 h600"><?php echo $output['t_info']['voucher_t_desc'];?></textarea>
	        <span></span>
	      </dd>
	    </dl>
	    <dl>
	      <dt><i class="required">*</i><?php echo $lang['voucher_template_image'].$lang['nc_colon']; ?></dt>
	      <dd>
          <div id="customimg_preview" class="ncsc-upload-thumb voucher-pic"><p><?php if ($output['t_info']['voucher_t_customimg']){?>
      			<img src="<?php echo $output['t_info']['voucher_t_customimg'];?>"/>
      			<?php }else {?>
      			<i class="icon-picture"></i>
      			<?php }?></p>
      		</div>
            <div class="ncsc-upload-btn"><a href="javascript:void(0);"><span>
          <input type="file" hidefocus="true" size="1" class="input-file" name="customimg" id="customimg" nc_type="customimg"/>
          </span>
          <p><i class="icon-upload-alt"></i>图片上传</p>
          </a> </div>
          <p class="hint"><?php echo $lang['voucher_template_image_tip'];?></p>
	      </dd>
	      </dl>
	      <?php if ($output['type'] == 'edit'){?>
	      <dl>
	      	<dt><em class="pngFix"></em><?php echo $lang['nc_status'].$lang['nc_colon']; ?></dt>
	      	<dd>
	      		<input type="radio" value="<?php echo $output['templatestate_arr']['usable'][0];?>" name="tstate" <?php echo $output['t_info']['voucher_t_state'] == $output['templatestate_arr']['usable'][0]?'checked':'';?>> <?php echo $output['templatestate_arr']['usable'][1];?>
	      		<input type="radio" value="<?php echo $output['templatestate_arr']['disabled'][0];?>" name="tstate" <?php echo $output['t_info']['voucher_t_state'] == $output['templatestate_arr']['disabled'][0]?'checked':'';?>> <?php echo $output['templatestate_arr']['disabled'][1];?>
	      	</dd>
	    </dl>
	    <?php }?>
	    <div class="bottom">
	      <label class="submit-border">
	      <a id='btn_add' class="submit" href="javascript:void(0);"><?php echo $lang['nc_submit'];?></a>
	      </label>
	      </div>
	  </form>
	</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js"></script>
<script>
//判断是否显示预览模块
<?php if (!empty($output['t_info']['voucher_t_customimg'])){?>
$('#customimg_preview').show();
<?php }?>
var year = <?php echo date('Y',$output['quotainfo']['quota_endtime']);?>;
var month = <?php echo intval(date('m',$output['quotainfo']['quota_endtime']));?>;
var day = <?php echo intval(date('d',$output['quotainfo']['quota_endtime']));?>;
function showcontent(choose_gettype){
	if(choose_gettype == 'pwd'){
		$("#eachlimit_dl").hide();
		$("#mgrade_dl").hide();
	}else{
		$("#eachlimit_dl").show();
		$("#mgrade_dl").show();
	}
}

$(document).ready(function(){
	showcontent('<?php echo $output['t_info']['voucher_t_gettype_key']; ?>');
	
	$("#gettype_sel").change(function(){
		var choose_gettype = $("#gettype_sel").val();
		showcontent(choose_gettype);
	});
    //日期控件
    $('#txt_template_enddate').datepicker();
    
    var currDate = new Date();
    var date = currDate.getDate();
    date = date + 1;
    currDate.setDate(date);
    
    $('#txt_template_enddate').datepicker( "option", "minDate", currDate);
<?php if (!$output['isOwnShop']) { ?>
    $('#txt_template_enddate').datepicker( "option", "maxDate", new Date(year,month-1,day));
<?php } ?>


    $('#txt_template_enddate').val("<?php echo $output['t_info']['voucher_t_end_date']?@date('Y-m-d',$output['t_info']['voucher_t_end_date']):'';?>");
    $('#customimg').change(function(){
		var src = getFullPath($(this)[0]);
		if(navigator.userAgent.indexOf("Firefox")>0){
			$('#customimg_preview').show();
			$('#customimg_preview').children('p').html('<img src="'+src+'">');
		}
	});

    $("#btn_add").click(function(){
        if($("#add_form").valid()){
        	var choose_gettype = $("#gettype_sel").val();
        	if(choose_gettype == 'pwd'){
            	var template_total = parseInt($("#txt_template_total").val());
            	if(template_total > 1000){
            		$("#txt_template_total").addClass('error');
            		$("#txt_template_total").parent('dd').children('span').append('<label for="txt_template_total" class="error"><i class="icon-exclamation-sign"></i>领取方式为卡密兑换的代金券，发放总数不能超过1000张</label>');
            		return false;
                }
            }
        	ajaxpost('add_form', '', '', 'onerror');
    	}
	});
	
    //表单验证
    $('#add_form').validate({
        errorPlacement: function(error, element){
	    	var error_td = element.parent('dd').children('span');
			error_td.append(error);
	    },
        rules : {
            txt_template_title: {
                required : true,
                rangelength:[1,50]
            },
            sc_id: {
            	required : true
            },
            txt_template_total: {
                required : true,
                digits : true,
                min: 1
            },
            txt_template_limit: {
                required : true,
                number : true
            },
            txt_template_describe: {
                required : true,
                rangelength:[1,200]
			},
			gettype_sel: {
				required : true
			}
        },
        messages : {
            txt_template_title: {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['voucher_template_title_error'];?>',
                rangelength : '<i class="icon-exclamation-sign"></i><?php echo $lang['voucher_template_title_error'];?>'
            },
            sc_id: {
            	required : '<i class="icon-exclamation-sign"></i>请选择店铺分类'
            },
            txt_template_total: {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['voucher_template_total_error'];?>',
                digits : '<i class="icon-exclamation-sign"></i><?php echo $lang['voucher_template_total_error'];?>',
                min: '<i class="icon-exclamation-sign"></i><?php echo $lang['voucher_template_total_error'];?>'
            },
            txt_template_limit: {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['voucher_template_limit_error'];?>',
                number : '<i class="icon-exclamation-sign"></i><?php echo $lang['voucher_template_limit_error'];?>'
            },
            txt_template_describe: {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['voucher_template_describe_error'];?>',
                rangelength:'<i class="icon-exclamation-sign"></i><?php echo $lang['voucher_template_describe_error'];?>'
			},
			gettype_sel: {
				required : '<i class="icon-exclamation-sign"></i>请选择领取方式'
			}
        }
    });
});
</script>