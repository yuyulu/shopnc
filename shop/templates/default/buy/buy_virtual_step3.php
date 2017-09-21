<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="ncc-main">
  <div class="ncc-title">
    <h3>在线支付</h3>
    <h5><a href="index.php?act=member_vr_order&op=show_order&order_id=<?php echo $output['order_info']['order_id'];?>" target="_blank">查看订单详情</a></h5>
  </div>
  <form action="index.php?act=payment&op=vr_order" method="POST" id="buy_form">
    <input type="hidden" name="order_sn" value="<?php echo $output['order_info']['order_sn'];?>">
    <input type="hidden" id="payment_code" name="payment_code" value="">
    <input type="hidden" value="" name="password_callback" id="password_callback">
    <div class="ncc-receipt-info">
      <div class="ncc-receipt-info-title">
        <h3>下单成功，请及时付款！应付金额：<strong>￥<?php echo ncPriceFormat($output['pay']['pay_amount_online']);?></strong></h3>
      </div>
      <table class="ncc-table-style">
        <thead>
          <tr>
            <th class="w50"></th>
            <th class="w150 tc">订单号</th>
            <th class="w250 tc">商品</th>
            <th class="tc">单价</th>
            <th class="w150 tc">数量</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td></td>
            <td class="tc"><?php echo $output['order_info']['order_sn']; ?></td>
            <td class="tc"><?php echo $output['order_info']['goods_name']; ?></td>
            <td class="tc">￥<?php echo $output['order_info']['goods_price']; ?></td>
            <td class="tc"><?php echo $output['order_info']['goods_num']; ?></td>
          </tr>
        </tbody>
      </table>
      <!-- S 预存款 & 充值卡 -->
      <dl class="ncc-pd-pay">
      <?php if (!empty($output['pay']['payd_pd_amount']) || !empty($output['pay']['payd_rcb_amount'])) {?>
      <dd>您已选择
   <?php echo !empty($output['pay']['payd_rcb_amount']) ? "使用充值卡支付<em>".ncPriceFormat($output['pay']['payd_rcb_amount'])."</em>元；" : null; ?>
   <?php echo !empty($output['pay']['payd_pd_amount']) ? "使用预存款支付<em>".ncPriceFormat($output['pay']['payd_pd_amount'])."</em>元；" : null; ?>
   还需在线支付 <strong id="api_pay_amount"><?php echo ncPriceFormat($output['pay']['payd_diff_amount']);?></strong>元。</dd>
      <?php } ?>
        <?php if ($output['pay']['if_show_pdrcb_select']) { ?>
        <dt>使用余额支付</dt>
        <dd>
          <label><input type="checkbox" class="checkbox" value="1" name="rcb_pay">
          使用充值卡支付</label> （可用余额：<em><?php echo ncPriceFormat($output['pay']['member_rcb']);?></em>元）</dd>
        <dd>
          <label><input type="checkbox" class="checkbox" value="1" name="pd_pay">
          使用预存款支付</label> （可用余额：<em><?php echo ncPriceFormat($output['pay']['member_pd']);?></em>元）</dd>
        <dd>（同时勾选时，系统将优先使用充值卡，不足时扣除预存款，目前还需在线支付 <strong id="api_pay_amount"><?php echo ncPriceFormat($output['pay']['pay_amount_online']);?></strong>元。）余额不足？<a href="<?php echo urlMember('predeposit', 'pd_log_list');?>" class="predeposit">马上充值</a></dd>
        <dd id="pd_password" style="display: none">请输入支付密码
          <input type="password" value="" name="password" id="pay-password" maxlength="35" autocomplete="off">
          <a href="javascript:void(0);" class="ncbtn-mini ncbtn-bittersweet" id="pd_pay_submit"><i class="icon-shield"></i>确认支付</a>
        <?php if (!$output['pay']['member_paypwd']) {?>
              还未设置支付密码，<a href="<?php echo urlMember('member_security', 'auth', array('type' => 'modify_paypwd'));?>" target="_blank">马上设置</a>
        <?php } ?>
        </dd>
      <?php } ?>
      </dl>
    </div>
    <div class="ncc-receipt-info">
      <?php if (!isset($output['payment_list'])) {?>
      <?php }else if (empty($output['payment_list'])){?>
      <div class="nopay"><?php echo $lang['cart_step2_paymentnull_1']; ?> <a href="<?php echo MEMBER_SITE_URL;?>/index.php?act=member_message&op=sendmsg&member_id=<?php echo $output['order']['seller_id'];?>"><?php echo $lang['cart_step2_paymentnull_2'];?></a> <?php echo $lang['cart_step2_paymentnull_3'];?></div>
      <?php } else {?>
      <div class="ncc-receipt-info-title">
        <h3>支付选择</h3>
      </div>
      <ul class="ncc-payment-list">
        <?php foreach($output['payment_list'] as $val) { ?>
        <li payment_code="<?php echo $val['payment_code']; ?>">
          <label for="pay_<?php echo $val['payment_code']; ?>">
          <i></i>
          <div class="logo" for="pay_<?php echo $val['payment_id']; ?>"> <img src="<?php echo SHOP_TEMPLATES_URL?>/images/payment/<?php echo $val['payment_code']; ?>_logo.gif" /> </div>
          </label>
        </li>
        <?php } ?>
      </ul>
      <?php } ?>
    </div>
    <div class="ncc-bottom"><a href="javascript:void(0);" id="next_button" class="pay-btn"><i class="icon-shield"></i>确认支付</a></div>
  </form>
</div>
<script type="text/javascript">
var pay_amount_online = <?php echo $output['pay']['pay_amount_online'];?>;
var member_rcb = <?php echo $output['pay']['member_rcb'];?>;
var member_pd = <?php echo $output['pay']['member_pd'];?>;
var pay_diff_amount = <?php echo $output['pay']['pay_amount_online'] ? $output['pay']['pay_amount_online'] : $output['pay']['payd_diff_amount'];?>;
$(function(){
    $('.ncc-payment-list > li').on('click',function(){
    	$('.ncc-payment-list > li').removeClass('using');
    	if ($('#payment_code').val() != $(this).attr('payment_code')) {
    		$('#payment_code').val($(this).attr('payment_code'));
    		$(this).addClass('using');
        } else {
            $('#payment_code').val('');
        }
    });
    $('#next_button').on('click',function(){
    	if (($('input[name="pd_pay"]').attr('checked') || $('input[name="rcb_pay"]').attr('checked')) && $('#password_callback').val() != '1') {
    		showDialog('使用充值卡/预存款支付，需输入支付密码并确认  ', 'error','','','','','','','',2);
    		return;
    	}
        if ($('#payment_code').val() == '' && parseFloat($('#api_pay_amount').html()) > 0) {
        	showDialog('请选择一种在线支付方式', 'error','','','','','','','',2);
        	return;
        }
        $('#buy_form').submit();
    });
    <?php if ($output['pay']['if_show_pdrcb_select']) { ?>
    function showPaySubmit() {
        if ($('input[name="pd_pay"]').attr('checked') || $('input[name="rcb_pay"]').attr('checked')) {
        	$('#pay-password').val('');
        	$('#password_callback').val('');
        	$('#pd_password').show();
        } else {
        	$('#pd_password').hide();
        }
        var _diff_amount = pay_diff_amount;
    	if ($('input[name="rcb_pay"]').attr('checked')) {
    		_diff_amount -= member_rcb;
        }
    	if ($('input[name="pd_pay"]').attr('checked')) {
    		_diff_amount -= member_pd;
        }
        if (_diff_amount < 0) {
        	_diff_amount = 0;
        }
        $('#api_pay_amount').html(_diff_amount.toFixed(2));
    }

    $('#pd_pay_submit').on('click',function(){
        if ($('#pay-password').val() == '') {
        	showDialog('请输入支付密码', 'error','','','','','','','','',2);return false;
        }
        $('#password_callback').val('');
		$.get("index.php?act=buy&op=check_pd_pwd", {'password':$('#pay-password').val()}, function(data){
            if (data == '1') {
            	$('#password_callback').val('1');
            	$('#pd_password').hide();
            } else {
            	$('#pay-password').val('');
            	showDialog('支付密码错误', 'error','','','','','','','','',2);
            }
        });
    });

    $('input[name="rcb_pay"]').on('change',function(){
    	showPaySubmit();
    	if ($(this).attr('checked') && !$('input[name="pd_pay"]').attr('checked')) {
        	if (member_rcb >= pay_amount_online) {
            	$('input[name="pd_pay"]').attr('checked',false).attr('disabled',true);
        	}
    	} else {
    		$('input[name="pd_pay"]').attr('disabled',false);
    	}
    });

    $('input[name="pd_pay"]').on('change',function(){
    	showPaySubmit();
    	if ($(this).attr('checked') && !$('input[name="rcb_pay"]').attr('checked')) {
        	if (member_pd >= pay_amount_online) {
            	$('input[name="rcb_pay"]').attr('checked',false).attr('disabled',true);
        	}
    	} else {
    		$('input[name="rcb_pay"]').attr('disabled',false);
    	}
    });
<?php } ?>
});
</script>