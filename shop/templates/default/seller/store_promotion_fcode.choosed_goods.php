<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="eject_con">
  <div id="warning" class="alert alert-error"></div>
  <?php if ($output['error'] == '') {?>
  <form id="choosed_goods_form" action="<?php echo urlShop('store_promotion_fcode', 'choosed_goods');?>" method="post" >
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="gid" value="<?php echo $output['goods_info']['goods_id']?>" />
    <div class="selected-goods-info">
      <div class="goods-thumb"><img src="<?php echo thumb($output['goods_info'], 240);?>" alt=""></div>
      <dl class="goods-info">
        <dt><?php echo $output['goods_info']['goods_name']?> (SKU：<?php echo $output['goods_info']['goods_id'];?>)</dt>
        <dd>销售价格：<strong class="red"><?php echo $lang['currency']; ?><?php echo ncPriceFormat($output['goods_info']['goods_price']);?></strong></dd>
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
      <dt>生成F码数量：</dt>
      <dd>
        <input name="g_fccount" type="text" class="text w100" value="" >
        <p class="hint">请填写500以内的数字。</p>
      </dd>
    </dl>
    <dl>
      <dt>F码前缀：</dt>
      <dd>
        <input name="g_fcprefix" type="text" class="text w100" value="" >
        <p class="hint">请填写3~5位的英文字母或数字。</p>
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

    jQuery.validator.addMethod("checkFCodePrefix", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);       
    },'<i class="icon-exclamation-sign"></i>请填写不多于5位的英文字母或数字');
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
            g_fccount: {
                required : true,
                range : [1,500]
            },
            g_fcprefix: {
                required : true,
                checkFCodePrefix : true,
                rangelength : [3,5]
            }
        },
        messages : {
            g_fccount: {
                required : "<i class='icon-exclamation-sign'></i>请填写F码生成数量",
                range : "<i class='icon-exclamation-sign'></i>请填写500以内的数量"
            },
            g_fcprefix: {
                required : "<i class='icon-exclamation-sign'></i>请填写不多于5位的英文字母或数字",
                rangelength : "<i class='icon-exclamation-sign'></i>请填写不多于5位的英文字母或数字"
            }
        }
    });
});
</script>