<?php defined('In33hao') or exit('Access Invalid!');?>
<style>
.clear{ clear:both;height: 20px; font-size: 12px; line-height:20px;}
</style>
<div class="tabmenu"><?php include template('layout/submenu');?><a href="javascript:void(0)" class="ncbtn ncbtn-mint addreply" title="添加账号"><i class="icon-group"></i>添加回复</a></div> 
<div class="alert mt10" style="clear:both;"><ul class="mt5"><li>1、主图参考像素900*500px</li><li>2、副图参考像素200*200px</li></ul></div> 
<div class="ncsc-form-default">
	<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/ajaxupload.js"></script>
	<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/addkeyword.js" charset="utf-8"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo SHOP_RESOURCE_SITE_URL;?>/keyword/css/keyword.css" />
	<div class="wrap" style="height:900px;">
	<!--关键词自动回复-->
	<div class="list" id="add_zsc" style="display:none;"></div>
	<!---------回复列表-------------->
       <?php if($output['keyinfo']){ ?>
	   <?php foreach($output['keyinfo'] as $keyword){ ?>
	  <div class="list" style="position:relative">
		<h3>规则：<?php echo $keyword['kename'];?><a href="javascript:void(0);" id="zsc_btninfo<?php echo $keyword['kid'];?>" class="add" onclick="zsc_show('<?php echo $keyword['kid'];?>')"></a><a href="javascript:void(0);" class="del" id="zsc_del_<?php echo $keyword['kid'];?>" onclick="zscdel('<?php echo $keyword['kid'];?>')"></a></h3>
		<div id="zsc_content_<?php echo $keyword['kid'];?>">
			<p>关键字：<?php echo $keyword['kyword'];?></p>
			 <?php if($keyword['type'] == 1){ ?>
			 <p>回复：文本消息 </p>
			<?php }else{ ?>
			<p>回复：图文消息 </p>
			<?php } ?>
		</div>
	</div>
	<?php } ?>
	<?php } ?>
	  <!--{/foreach}-->
	  <!--{/if}-->
	<div id="localImag"><img id="preview" width=-1 height=-1 style="diplay:none" /></div>
	</div>

	<!---关键词自动回复 END--->
	</div>	
</div>
<div class="blank"></div>