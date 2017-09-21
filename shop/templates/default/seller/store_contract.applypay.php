<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="tabmenu">
    <?php include template('layout/submenu');?>
</div>

<div class="ncsc-form-default">
<form id="pay_form" method="post" enctype="multipart/form-data" action="index.php?act=store_contract&op=applypay">
	<input type="hidden" id="act" name="act" value="store_contract"/>
	<input type="hidden" id="op" name="op" value="applypay"/>
	<input type="hidden" id="itemid" name="itemid" value="<?php echo $output['item_info']['cti_id']; ?>"/>
	<input type="hidden" id="form_submit" name="form_submit" value="ok"/>
	<dl>
		<dt><em class="pngFix"></em>项目名称：</dt>
		<dd><?php echo $output['item_info']['cti_name'];?></dd>
	</dl>
	<dl>
		<dt><em class="pngFix"></em>所需保证金：</dt>
		<dd><?php echo $output['item_info']['cti_cost'];?>&nbsp;&nbsp;<?php echo $lang['currency_zh'];?></dd>
	</dl>
	<dl>
		<dt><em class="pngFix"></em>申请时间：</dt>
		<dd><?php echo @date('Y-m-d H:i:s',$output['apply_info']['cta_addtime']);?></dd>
	</dl>
	<?php if ($output['apply_info']['cta_costimg']){?>
	<dl>
		<dt><em class="pngFix"></em>已上传付款凭证：</dt>
		<dd>
			<div class="ncsc-upload-thumb voucher-pic">
				<p>
					<img src="<?php echo $output['apply_info']['cta_costimg_url'];?>" style="cursor: pointer;" onclick="window.open('<?php echo $output['apply_info']['cta_costimg_url'];?>');"/>
				</p>
			</div>
			<p class="hint">以上图片为上次提交申请时上传的付款凭证</p>
		</dd>
	</dl>
	<?php } ?>
	<dl>
		<dt><em class="pngFix"></em>上传付款凭证：</dt>
		<dd>
			<div id="costimg_preview" class="ncsc-upload-thumb voucher-pic">
				<p><i class="icon-picture"></i></p>
			</div>
			<div class="ncsc-upload-btn">
				<a href="javascript:void(0);">
					<span><input type="file" hidefocus="true" size="1" class="input-file" name="costimg" id="costimg" nc_type="costimg"/></span>
					<p><i class="icon-upload-alt"></i>图片上传</p>
				</a>
			</div>
			<p class="hint">建议上传文字信息清晰的图片，便于审核时查看信息</p>
		</dd>
	</dl>
	<div class="bottom">
		<label class="submit-border">
			<a id='submitbtn' class="submit" href="javascript:void(0);"><?php echo $lang['nc_submit'];?></a>
		</label>
	</div>
</form>
</div>
<script>
//判断是否显示预览模块
<?php if (!empty($output['apply_info']['cta_costimg'])){?>
$('#costimg_preview').show();
<?php }?>

$(document).ready(function(){
	$('#costimg').change(function(){
		var filepath=$(this).val();
		var extStart=filepath.lastIndexOf(".");
		var ext=filepath.substring(extStart,filepath.length).toUpperCase();
		if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
			$(this).attr('value','');
			showDialog("图片限于png,gif,jpeg,jpg格式");
			return false;
		}
		var src = getFullPath($(this)[0]);
		if(navigator.userAgent.indexOf("Firefox")>0){
			$('#costimg_preview').show();
			$('#costimg_preview').children('p').html('<img src="'+src+'">');
		}
	});

	$("#submitbtn").click(function(){
		if (!$("#costimg").val()) {
			showDialog('请上传付款凭证');
			return false;
		}
		ajaxpost('pay_form', '', '', 'onerror');
	});
});
</script>