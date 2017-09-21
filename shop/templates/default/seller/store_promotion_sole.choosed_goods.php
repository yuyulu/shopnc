<?php defined('In33hao') or exit('Access Invalid!');?>
<style type="text/css">
.eject_con dl dt { width: 24%; }
.eject_con dl dd { width: 75%; }
.eject_con li p { float: none; }
</style>
<div class="eject_con">
  <div id="warning" class="alert alert-error"></div>
  <?php if ($output['error'] == '') {?>
  <form id="choosed_goods_form" action="<?php echo urlShop('store_promotion_sole', 'choosed_goods_save');?>" method="post" >
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="gid" value="<?php echo $output['goods_info']['goods_id'];?>" />
    <div class="selected-goods-info">
      <div class="goods-thumb"><img src="<?php echo thumb($output['goods_info'], 240);?>" alt=""></div>
      <dl class="goods-info">
        <dt><?php echo $output['goods_info']['goods_name']?> (SKU：<?php echo $output['goods_info']['goods_id'];?>)</dt>
        <dd>实际售价：<strong class="red"><?php echo $lang['currency']; ?><?php echo ncPriceFormat($output['goods_info']['goods_promotion_price']);?></strong></dd>
        <dd>库存：<span><?php echo $output['goods_info']['goods_storage']?></span> 件</dd>
        <?php if (!empty($output['goods_spec'])) {?>
        <dd>
          <?php foreach ($output['goods_spec'] as $key => $val) {?>
          <?php echo $output['spec_name'][$key];?>：<span class="mr20"><?php echo $val?></span>
          <?php }?>
        </dd>
        <?php }?>
      </dl>
    </div>
    <dl>
      <dt>专享价格：</dt>
      <dd>
        <input name="sole_price" type="text" class="text w70" value="<?php echo ncPriceFormat($output['solegoods_info']['sole_price']);?>">
        <em class="add-on"><i class="icon-renminbi"></i></em>
        <p class="hint">移动端的专享价格，销售价格要小于商品的实际销售价格。当前商品实际售价为(<?php echo $lang['currency']; ?><?php echo ncPriceFormat($output['goods_info']['goods_promotion_price']);?>)</p>
      </dd>
    </dl>
    <div class="eject_con">
      <div class="bottom">
        <label class="submit-border"><a id="btn_submit" class="submit" href="javascript:void(0);">提交</a></label>
      </div>
    </div>
  </form>
  <?php } else {?>
  <table class="ncsc-default-table ncsc-promotion-buy">
    <tbody>
      <tr>
        <td colspan="20" class="norecord"><div class="no-promotion"><span><?php echo $output['error'];?></span></div></td>
      </tr>
    </tbody>
  </table>
  <?php }?>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css" />
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js"></script> 
<script>
$(function(){
    // 提交表单
    $("#btn_submit").click(function(){
        $("#choosed_goods_form").submit();
    });

    // 页面输入内容验证
    $("#choosed_goods_form").validate({
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
               $('#warning').show();
        },
        submitHandler:function(form){
            ajaxpost('choosed_goods_form', '', '', 'onerror');
        },
        rules : {
            sole_price: {
                required : true,
                max : <?php echo $output['goods_info']['goods_promotion_price'];?>,
                min : 0.01
            }
        },
        messages : {
            sole_price: {
                required : "<i class='icon-exclamation-sign'></i>专享价格不能为空，不能超过商品实际销售价格",
                max : "<i class='icon-exclamation-sign'></i>专享价格不能为空，不能超过商品实际销售价格",
                min : "<i class='icon-exclamation-sign'></i>专享价格不能为空，不能超过商品实际销售价格"
            }
        }
    });
});


</script>