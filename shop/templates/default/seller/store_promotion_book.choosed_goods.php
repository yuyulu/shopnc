<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="eject_con">
  <div id="warning" class="alert alert-error"></div>
  <?php if ($output['error'] == '') {?>
  <form id="choosed_goods_form" action="<?php echo urlShop('store_promotion_book', 'choosed_goods');?>" method="post" >
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="gid" value="<?php echo $output['goods_info']['goods_id'];?>" />
    <input type="hidden" name="type" value="<?php echo $output['type'];?>" />
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
    <?php if ($output['type'] == 'book') {?>
    <dl>
      <dt>预定期间：</dt>
      <dd>
        <input name="down_time" type="text" class="text w70">
        <em class="add-on"><i class="icon-calendar"></i></em>
        <p class="hint">请选择预定活动截止时间（当日24:00时），即预定活动开始至结束时间段。同时该时间点也作为订单第二阶段尾款支付时间起始点。（如买家一次性支付定金与尾款系统将不会提醒买家进行尾款支付，商家直接进入备货阶段）。</p>
      </dd>
    </dl>
    <dl>
      <dt>预定活动售价：</dt>
      <dd>
        <input name="total_payment" type="text" class="text w70">
        <em class="add-on"><i class="icon-renminbi"></i></em>
        <p class="hint">预定活动期间商品优惠价格，预定期满后将恢复商品原价。</p>
      </dd>
    </dl>
    <dl>
      <dt>第一阶段 - 定金额：</dt>
      <dd>
        <input name="down_payment" type="text" class="text w70">
        <em class="add-on"><i class="icon-renminbi"></i></em>
        <p class="hint">定金即预定商品第一阶段应付款，注意：定金设置不应超过预定总价的20%。</p>
      </dd>
    </dl>
    <dl>
      <dt>第二阶段 - 尾款金额：</dt>
      <dd>
        <input name="final_payment" type="text" readonly class="text w70">
        <em class="add-on"><i class="icon-renminbi"></i></em>
        <p class="hint">系统将根据预定总价和定金自动计算第二阶段应支付的尾款金额。</p>
      </dd>
    </dl>
    <?php } else if($output['type'] == 'presell') {?>
    <dl>
      <dt>商家发货时间：</dt>
      <dd>
        <input name="presell_deliverdate" type="text" class="text w70">
        <em class="add-on"><i class="icon-calendar"></i></em>
        <p class="hint">请选择预售活动的商家发货时间。</p>
      </dd>
    </dl>
    <?php }?>
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
    // 时间控件
    $('input[name="down_time"]').datepicker({minDate: 0<?php if (!checkPlatformStore()) { echo ", maxDate: '" . date('Y-m-d', $output['book_info']['bkq_endtime']) . "'";}?>});
    $('input[name="presell_deliverdate"]').datepicker({minDate: 0<?php if (!checkPlatformStore()) { echo ", maxDate: '" . date('Y-m-d', $output['book_info']['bkq_endtime']) . "'";}?>});
    // 提交表单
    $("#btn_submit").click(function(){
        $("#choosed_goods_form").submit();
    });
    // 计算合计总价
    $('input[name="down_payment"],input[name="total_payment"]').change(function(){
        totalPayment();
    });

    jQuery.validator.addMethod("checkDownPayment", function(value, element) {
        if (parseFloat($('input[name="total_payment"]').val()) * 0.2 >= parseFloat($('input[name="down_payment"]').val())) {
            return true;
        } else {
            return false;
        }
    },'<i class="icon-exclamation-sign"></i>定金价格不能超过预定价格的20%');

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
            total_payment: {
                required : true,
                max : <?php echo $output['goods_info']['goods_price'];?>,
                min : 0.01
            },
            down_payment: {
                required : true,
                number : true,
                min : 0.01,
                checkDownPayment : true
            },
            down_time: {
                required : true
            }
        },
        messages : {
            total_payment: {
                required : "<i class='icon-exclamation-sign'></i>合计总价不能为空，不能超过商品销售价格",
                max : "<i class='icon-exclamation-sign'></i>合计总价不能为空，不能超过商品销售价格",
                min : "<i class='icon-exclamation-sign'></i>合计总价不能为空，不能超过商品销售价格"
            },
            down_payment: {
                required : "<i class='icon-exclamation-sign'></i>定金价格不能为空，且必须小于商品价格",
                number : "<i class='icon-exclamation-sign'></i>定金价格不能为空，且必须小于商品价格",
                min : "<i class='icon-exclamation-sign'></i>定金价格不能为空，且必须小于商品价格"
            },
            down_time: {
                required : "<i class='icon-exclamation-sign'></i>请选择尾款支付时间"
            }
        }
    });
});

// 计算合计总价
function totalPayment() {
    _down = parseFloat($('input[name="down_payment"]').val());
    _total = parseFloat($('input[name="total_payment"]').val());

    _down = isNaN(_down) ? 0 : _down;
    _total = isNaN(_total) ? 0 : _total;
    _final = _total - _down;
    $('input[name="final_payment"]').val(_final.toFixed(2));
}
</script>