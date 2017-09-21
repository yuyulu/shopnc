<?php defined('In33hao') or exit('Access Invalid!');?>
<style type="text/css">
.grade-template-thumb { background-color: #FFF; width: 100px; height: 100px; padding: 4px; border: solid 1px #E6E6E6; margin: 0 20px 0 0; position: relative;}
.grade-template-thumb a { line-height: 0; text-align: center; vertical-align: middle; display: table-cell; *display: block; width: 100px; height: 100px; overflow: hidden;}
.grade-template-thumb a img { max-width: 100px; max-height: 100px; margin-top:expression(100-this.height/2);}
.grade-template-thumb .checked { position: absolute; z-index: 1; top: -2px; left: -2px;}
</style>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=store_grade&op=store_grade" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['store_grade'];?> - 选择“<?php echo $output['grade_array']['sg_name'];?>”可用模板</h3>
        <h5><?php echo $lang['store_grade_subhead'];?></h5>
      </div>
    </div>
  </div>
   <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>点击图片可放大查看店铺首页模板预览图。</li>
      <li>模板勾选并提交后，“<?php echo $output['grade_array']['sg_name'];?>”等级所属店铺可选择使用。</li>
    </ul>
  </div>
  <form method="post" id="grade-template">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="sg_id" value="<?php echo $output['grade_array']['sg_id'];?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">店铺模板预览</dt>
        <dd class="opt">
          <ul class="nc-row">
            <?php if(!empty($output['dir_list']) && is_array($output['dir_list'])){ ?>
            <?php foreach($output['dir_list'] as $k => $v){ ?>
            <li>
              <div class="grade-template-thumb"> <a class="nyroModal" rel="gal" href="<?php echo SHOP_SITE_URL;?>/templates/<?php echo TPL_SHOP_NAME;?>/store/style/<?php echo $v;?>/screenshot.jpg"><img src="<?php echo SHOP_SITE_URL;?>/templates/<?php echo TPL_SHOP_NAME;?>/store/style/<?php echo $v;?>/images/preview.jpg" ></a>
                <?php if ($v == 'default'){?>
                <input type="checkbox" value="default" name="template[]" disabled="disabled" checked="checked" class="checked">
                <input type="hidden" value="default" name="template[]" />
                <?php }else {?>
                <input type="checkbox" <?php if(@in_array($v,$output['grade_array']['sg_template'])){ ?>checked="checked"<?php } ?>  class="checked" value="<?php echo $v; ?>" name="template[]">
                <?php }?>
              </div>
            </li>
            <?php } ?>
            <?php } ?>
          </ul>
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
	$(function(){
		$("#submitBtn").click(function(){
    		if($("#grade-template").valid()){
     	$("#grade-template").submit();
		}
	});
	// 点击查看图片
	$('.nyroModal').nyroModal();

});
</script> 
