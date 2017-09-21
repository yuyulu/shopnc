<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="ncc-main">
<div class="ncc-title">
  <h3>填写核对购物信息</h3>
  <h5>请仔细填写手机号，以确保电子兑换码准确发到您的手机。</h5>
</div>
<form action="<?php echo urlShop('buy_virtual','buy_step3');?>" method="POST" id="form_buy" name="form_buy">
  <input type="hidden" name="goods_id" value="<?php echo $output['goods_info']['goods_id'];?>">
  <input type="hidden" name="quantity" value="<?php echo $output['goods_info']['quantity'];?>">
  <div class="ncc-receipt-info">
    <div class="ncc-receipt-info-title">
      <h3>电子兑换码/券接收方式</h3>
    </div>
    <div id="invoice_list" class="ncc-candidate-items">
      <ul style="overflow: visible;">
        <li>手机号码：
          <div class="parentCls">
            <input name="buyer_phone" class="inputElem text" autocomplete = "off"  type="text" id="buyer_phone" value="<?php echo $output['member_info']['member_mobile'];?>" maxlength="11">
          </div>
        </li>
      </ul>
      <p><i class="icon-info-sign"></i>您本次购买的商品不需要收货地址，请正确输入接收手机号码，确保及时获得“电子兑换码”。可使用您已经绑定的手机或重新输入其它手机号码。</p>
    </div>
  </div>
  <div class="ncc-receipt-info">
    <div class="ncc-receipt-info-title">
      <h3>虚拟服务类商品清单</h3>
      </div>
    <table class="ncc-table-style" nc_type="table_cart">
      <thead>
        <tr>
          <th colspan="3">商品</th>
          <th class="w150">单价(<?php echo $lang['currency_zh'];?>)</th>
          <th class="w80">数量</th>
          <th class="w150">小计(元)</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th colspan="20"><!-- S 店铺名称 -->
            
            <div class="ncc-store-name">店铺：<a href="<?php echo urlShop('show_store','index',array('store_id'=>$output['store_info']['store_id']));?>"><?php echo $output['store_info']['store_name'];?></a> <span member_id="<?php echo $output['store_info']['member_id'];?>"></span></div>
          
          <!-- E 店铺名称 --> </th>
        </tr>
        <tr class="shop-list">
          <td class="w10"></td>
          <td class="w100"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$output['goods_info']['goods_id']));?>" target="_blank" class="ncc-goods-thumb"><img src="<?php echo thumb($output['goods_info'],60);?>" alt="<?php echo $output['goods_info']['goods_name']; ?>" /></a></td>
          <td class="tl"><dl class="ncc-goods-info">
              <dt><a href="<?php echo urlShop('goods','index',array('goods_id'=>$output['goods_info']['goods_id']));?>" target="_blank"><?php echo $output['goods_info']['goods_name']; ?></a></dt>
              <?php if ($output['goods_info']['goods_spec']) { ?>
              <dd><?php echo $output['goods_info']['goods_spec'];?></dd>
              <?php } ?>
              <!-- S消费者保障服务 -->
              <?php if($output['goods_info']["contractlist"]){?>
              <dd class="goods-cti">
                <?php foreach($output['goods_info']["contractlist"] as $gcitem_k=>$gcitem_v){?>
                <span <?php if($gcitem_v['cti_descurl']){ ?>onclick="window.open('<?php echo $gcitem_v['cti_descurl'];?>');" style="cursor: pointer;"<?php }?> title="<?php echo $gcitem_v['cti_name']; ?>"> <img src="<?php echo $gcitem_v['cti_icon_url_60'];?>" style="width: 20px; height: 20px;"/> </span>
                <?php }?>
              </dd>
              <?php }?>
              <!-- E消费者保障服务 -->
              
            </dl></td>
          <td><em id="item_price" class="goods-price"><?php echo $output['goods_info']['goods_price'];?></em><!-- S 商品促销-抢购 -->
            
            <?php if ($output['goods_info']['ifgroupbuy']) { ?>
            <dl class="ncc-goods-sale">
              <dt>商家促销<i class="icon-angle-down"></i></dt>
              <dd>
                <p>活动名称：抢购</p>
                <?php if ($cart_info['upper_limit']) {?>
                <p>最多限购：<strong><?php echo $output['goods_info']['virtual_limit'];?></strong>件 </p>
                <?php } ?>
              </dd>
            </dl>
            <?php }?>
            
            <!-- E 商品促销-抢购 --></td>
          <td><?php echo $output['goods_info']['quantity'];?></td>
          <td><em id="item_subtotal" class="goods-subtotal"><?php echo $output['goods_info']['goods_total'];?></em></td>
        </tr>
        
        <!-- S 留言 -->
        <tr>
          <td class="w10"></td>
          <td class="tl" colspan="2">买家留言：
            <textarea name="buyer_msg" class="ncc-msg-textarea" maxlength="150" placeholder="选填：对本次交易的说明（建议填写已经和商家达成一致的说明）" title="选填：对本次交易的说明（建议填写已经和商家达成一致的说明）"></textarea></td>
          <td class="tl" colspan="10"></td>
        </tr>
        <!-- E 留言 --> 

      </tbody>
      <tfoot>
        <tr>
          <td colspan="20"><a href="index.php?act=buy_virtual&op=buy_step1&goods_id=<?php echo $_POST['goods_id'];?>&quantity=<?php echo $_POST['quantity'];?>" class="ncc-prev-btn"><i class="icon-angle-left"></i>返回上一步</a><div class="ncc-all-account">订单总金额：<em id="orderTotal"><?php echo $output['goods_info']['goods_total']; ?></em>元</div><a id="submitOrder" href="javascript:void(0)" class="ncc-next-submit ok">提交订单</a></td>
        </tr>
      </tfoot>
    </table>
  </div>
</form>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/input_max.js"></script> 
<script>
//input内容放大
$(function(){
	new TextMagnifier({
		inputElem: '.inputElem',
			align: 'top'
	});
});

//计算应支付金额计算
function calcOrder() {
    var allTotal = parseFloat($('#item_subtotal').html());
    if ($('#storeVoucher').length > 0) {
    	allTotal += parseFloat($('#storeVoucher').html());
    }
    $('#cartTotal').html(number_format(allTotal,2));
}

$(document).ready(function(){

    $('select[nctype="voucher"]').on('change',function(){
        if ($(this).val() == '') {
        	$('#storeVoucher').html('-0.00');
        } else {
            var items = $(this).val().split('|');
            $('#storeVoucher').html('-'+number_format(items[1],2));
        }
        calcOrder();
    });


    var SUBMIT_FORM = true;
    $('#submitOrder').on('click',function(){
        if (!$("#form_buy").valid()) return;
    	if (!SUBMIT_FORM) return;
    	SUBMIT_FORM = false;
    	$('#form_buy').submit();
    });

	$("#form_buy").validate({
		onkeyup: false,
		rules: {
			buyer_phone : {
				required : true,
				digits : true,
				minlength : 11
			}
		},
		messages: {
			buyer_phone : {
				required : "请填写手机号码",
				digits : "请正确填写手机号码",
				minlength : "请正确填写手机号码"
			}
		}
	});
});
</script> 
