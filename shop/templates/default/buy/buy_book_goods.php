<?php defined('In33hao') or exit('Access Invalid!');?>
<style>
.ncc-table-style tbody tr.item_disabled td { background: none repeat scroll 0 0 #F9F9F9; height: 30px; padding: 10px 0; text-align: center; }
</style>
<div class="ncc-receipt-info">
  <div class="ncc-receipt-info-title">
    <h3>商品清单
      <input value="1" type="hidden" name="is_book">
    </h3>
  </div>
  <table class="ncc-table-style">
    <thead>
      <tr>
        <th class="w10"></th>
        <th></th>
        <th><?php echo $lang['cart_index_store_goods'];?></th>
        <th class="w150">预定价(<?php echo $lang['currency_zh'];?>)</th>
        <th class="w100"><?php echo $lang['cart_index_amount'];?></th>
        <th class="w150"><?php echo $lang['cart_index_sum'].'('.$lang['currency_zh'].')';?></th>
      </tr>
    </thead>
    <?php $store_id = key($output['store_cart_list']);?>
    <?php $cart_list = current($output['store_cart_list']);?>
    <?php $cart_info = $cart_list[0];?>
    <tbody>
      <tr>
        <th colspan="20"><!-- S 店铺名称 -->
          
          <div class="ncc-store-name">店铺：<a href="<?php echo urlShop('show_store','index',array('store_id'=>$store_id));?>"><?php echo $cart_list[0]['store_name']; ?></a><span member_id="<?php echo $output['store_list'][$store_id]['member_id'];?>"></span></div>
          
          <!-- E 店铺名称 --> 
          <!-- S 店铺满金额包邮 -->
          
          <?php if (!empty($output['cancel_calc_sid_list'][$store_id])) {?>
          <div class="ncc-store-sale"> <span>免运费</span><?php echo $output['cancel_calc_sid_list'][$store_id]['desc'];?></div>
          <?php } ?>
          
          <!-- S 店铺满金额包邮 --> </th>
      </tr>
      <tr id="cart_item" class="shop-list <?php echo ($cart_info['state'] && $cart_info['storage_state']) ? '' : 'item_disabled';?>">
        <td class="td-border-left"><?php if ($cart_info['state'] && $cart_info['storage_state']) {?>
          <input type="hidden" value="<?php echo $cart_info['cart_id'].'|'.$cart_info['goods_num'];?>" store_id="<?php echo $store_id?>" name="cart_id[]">
          <?php } ?></td>
        <td class="w100"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$cart_info['goods_id']));?>" target="_blank" class="ncc-goods-thumb"><img src="<?php echo thumb($cart_info);?>" alt="<?php echo $cart_info['goods_name']; ?>" /></a></td>
        <td class="tl"><dl class="ncc-goods-info">
            <dt><a href="<?php echo urlShop('goods','index',array('goods_id'=>$cart_info['goods_id']));?>" target="_blank"><?php echo $cart_info['goods_name']; ?></a></dt>
            <!-- S消费者保障服务 -->
            <?php if($cart_info["contractlist"]){?>
            <dd class="goods-cti">
              <?php foreach($cart_info["contractlist"] as $gcitem_k=>$gcitem_v){?>
              <span <?php if($gcitem_v['cti_descurl']){ ?>onclick="window.open('<?php echo $gcitem_v['cti_descurl'];?>');" style="cursor: pointer;"<?php }?> title="<?php echo $gcitem_v['cti_name']; ?>"> <img src="<?php echo $gcitem_v['cti_icon_url_60'];?>"/> </span>
              <?php }?>
            </dd>
            <?php }?>
            <!-- E消费者保障服务 -->
          </dl></td>
        <td><em class="goods-price">定金：<?php echo $cart_info['book_down_payment'];?></em>
          <p>+</p>
          <em class="goods-price"> 尾款：<?php echo $cart_info['book_final_payment'];?></em></td>
        <td><?php echo $cart_info['state'] ? $cart_info['goods_num'] : ''; ?></td>
        <td class="td-border-right"><?php if ($cart_info['state'] && $cart_info['storage_state']) {?>
          <em class="goods-subtotal"><?php echo $cart_info['goods_total']; ?></em> <span id="no_send_tpl" style="color: #F00;display:none">无货</span>
          <?php } elseif (!$cart_info['storage_state']) {?>
          <span style="color: #F00;">库存不足</span>
          <?php }elseif (!$cart_info['state']) {?>
          <span style="color: #F00;">无效</span>
          <?php }?></td>
      </tr>
      <tr>
        <td colspan="20"><div class="ncc-msg">买家留言：
            <textarea  name="pay_message[<?php echo $store_id;?>]" class="ncc-msg-textarea" placeholder="选填：对本次交易的说明（建议填写已经和商家达成一致的说明）" title="选填：对本次交易的说明（建议填写已经和商家达成一致的说明）"  maxlength="150"></textarea>
          </div>
          <div class="ncc-form-default"> </div>
          <div class="ncc-store-account">
            <dl>
              <dt>支付方式：</dt>
              <dd class="all">
                <label for="book_pay_part" class="mr10">
                  <input type="radio" value="part" name="book_pay_type" checked="checked" id="book_pay_part" class="vm">
                  支付定金</label>
                <label for="book_pay_full">
                  <input type="radio" value="full" name="book_pay_type" id="book_pay_full" class="vm">
                  全款支付(享受优先发货特权)</label>
              </dd>
            </dl>
            <dl>
              <dt>物流运费：</dt>
              <dd class="rule">在尾款阶段或全款支付时支付</dd>
              <dd class="sum"><em id="eachStoreFreight_<?php echo $store_id;?>">0.00</em></dd>
            </dl>
            <dl nctype="book_pay_content_part" class="total">
              <dt>定金合计：</dt>
              <dd class="rule"><i>*</i>
                <input type="checkbox" value="1" name="agree_part" id="agree_part" class="vm mr5">
                同意支付定金 (不退)</span></dd>
              <dd class="sum"><em><?php echo ncPriceFormat($cart_info['book_down_payment']*$cart_info['goods_num']);?></em></dd>
            </dl>
            <dl nctype="book_pay_content_part">
              <dt>定时通知：</dt>
              <dd class="all"><i>*</i>短信提示尾款到期通知
                <input name="buyer_phone" autocomplete="off" class="w150 ml10" type="text" id="buyer_phone" placeholder="请填写接受通知的手机号码" value="<?php echo $output['member_info']['member_mobile'];?>" maxlength="11">
              </dd>
            </dl>
            <dl nctype="book_pay_content_full" style="display: none" class="total">
              <dt>全款合计：</dt>
              <dd class="rule"><i>*</i>
                <input type="checkbox" value="1" name="agree_full" id="agree_full" class="vm mr5">
                同意支付定金 (不退)</dd>
              <dd class="sum"><em id="storeGoodsTotal"><?php echo $output['store_goods_total'][$store_id];?></em></dd>
            </dl>
          </div></td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="20"><div class="ncc-all-account">本次需支付：<em id="orderBookTotal">...</em><?php echo $lang['currency_zh'];?></div>
          <a href="javascript:void(0)" id='submitOrder' class="ncc-next-submit ok"><?php echo $lang['cart_index_submit_order'];?></a></td>
      </tr>
    </tfoot>
  </table>
</div>
<script>
//计算和显示运费价格
function calcOrder() {
    var allTotal = 0;
	if (no_send_tpl_ids[<?php echo $cart_info['transport_id']?>]) {
	   $('#no_send_tpl').show();
	   $('#cart_item').addClass('item_disabled');
	} else {
		$('#no_send_tpl').hide();
	   $('#cart_item').removeClass('item_disabled');
	}
    store_id = <?php echo $store_id;?>;
    if ($('#book_pay_part').prop('checked')) {
    	$('#orderBookTotal').html('<?php echo ncPriceFormat($cart_info['book_down_payment'] * $cart_info['goods_num']);?>');
    } else {
    	allTotal = parseFloat($('#eachStoreFreight_'+store_id).html()) + <?php echo $output['store_goods_total'][$store_id];?> - <?php echo floatval($output['store_mansong_rule_list'][$store_id]['discount']);?>;
    	$('#orderBookTotal').html(number_format(allTotal,2));
    }
}

function submitNext(){
	if (!SUBMIT_FORM) return;

	if ((!$('#agree_part').prop('checked') && $('#book_pay_part').prop('checked')) || (!$('#agree_full').prop('checked') && $('#book_pay_full').prop('checked'))){
		showDialog('预定商品定金恕不退换，请同意支付定金', 'error','','','','','','','',2);
		return;
	}

	if ($('input[name="cart_id[]"]').size() == 0) {
		showDialog('所购商品无效', 'error','','','','','','','','',2);
		return;
	}
    if ($('#address_id').val() == ''){
		showDialog('<?php echo $lang['cart_step1_please_set_address'];?>', 'error','','','','','','','','',2);
		return;
	}
	if ($('#buy_city_id').val() == '') {
		showDialog('正在计算运费,请稍后', 'error','','','','','','','',2);
		return;
	}
	var re = /^1\d{10}$/;
	if (!re.test($('#buyer_phone').val()) && $('#book_pay_part').prop('checked')) {
		showDialog('请正确输入通知号码', 'error','','','','','','','',2);
		return;
	}

	if (no_send_tpl_ids.length > 0) {
		showDialog('有部分商品配送范围无法覆盖您选择的收货地址，请更换其它商品！', 'error','','','','','','','','',4);
		return;
	}

	SUBMIT_FORM = false;

	$('#order_form').submit();
}
$(function(){
	$('input[name="book_pay_type"]').on('change',function(){
		if ($(this).val() == 'full') {
			$('dl[nctype="book_pay_content_part"]').hide();
			$('dl[nctype="book_pay_content_full"]').show();
		} else {
			$('dl[nctype="book_pay_content_part"]').show();
			$('dl[nctype="book_pay_content_full"]').hide();
		}
		calcOrder();
	});
    $(document).keydown(function(e) {
        if (e.keyCode == 13) {
        	submitNext();
        	return false;
        }
    });
	$('#submitOrder').on('click',function(){submitNext()});
});
</script> 
