<?php defined('In33hao') or exit('Access Invalid!');?>
<script type="text/javascript">
//是否允许表单提交
var SUBMIT_FORM = true;
//记录无货的店铺ID数组
var no_send_tpl_ids = [];
//当前可用的红包
var rpt_list_json = $.parseJSON('<?php echo $output['rpt_list_json'];?>');
//记录选中的门店自提ID，查询库存需要
var chain_id = '';
var chain_store_id = '<?php echo $output['chain_store_id'];?>';
//哪 些商品ID不可以门店自提
var no_chain_goods_ids = [];

//如果商品详细页选择了门店后，保存选择的信息
<?php if ($_POST['chain_id'] && $_POST['area_id'] && $_POST['area_name']) {?>
var ref_chain_id = "<?php echo $_POST['chain_id']?>";
var ref_area_id = "<?php echo $_POST['area_id']?>";
var ref_area_name = "<?php echo $_POST['area_name']?>";
var ref_area_id_2 = "<?php echo $_POST['area_id_2']?>";
<?php } ?>

function iniRpt(order_total) {
    var _tmp,_hide_flag = true;
	$('#rpt').empty();
	$('#rpt').append('<option value="|0.00">-选择使用平台红包-</option>');
	for (i = 0; i < rpt_list_json.length; i++) {
		_tmp = parseFloat(rpt_list_json[i]['rpacket_limit']);
		order_total = parseFloat(order_total);
    	if (order_total > 0 && order_total >= _tmp.toFixed(2)) {
  		   $('#rpt').append("<option value='" + rpt_list_json[i]['rpacket_t_id'] + '|' + rpt_list_json[i]['rpacket_price'] + "'>" + rpt_list_json[i]['desc'] + "</option>")
 		   _hide_flag = false;
    	}
	}
	if (_hide_flag) {
		$('#rpt_panel').hide();
	} else {
		$('#rpt_panel').show();
	}
}
$(function(){
	<?php if ($output['address_info']['chain_id']) { ?>
	showProductChain(<?php echo $output['address_info']['city_id'] ? $output['address_info']['city_id'] : $output['address_info']['area_id']?>);
	<?php } ?>
    $('select[nctype="voucher"]').on('change',function(){
        if ($(this).val() == '') {
        	$('#eachStoreVoucher_'+items[1]).html('-0.00');
        } else {
            var items = $(this).val().split('|');
            $('#eachStoreVoucher_'+items[1]).html('-'+number_format(items[2],2));
        }
        calcOrder();
    });

    $('#rpt').on('change',function(){
        if (typeof allTotal == 'undefined') {
            alert('系统正忙，请稍后再试');return false
        }
        if ($(this).val() == '') {
        	$('#orderRpt').html('-0.00');
        	$('#orderTotal').html(allTotal.toFixed(2));
        } else {
            var items = $(this).val().split('|');
            $('#orderRpt').html('-'+number_format(items[1],2));
            var paytotal = allTotal - parseFloat(items[1]);
            if (paytotal < 0) paytotal = 0;
            $('#orderTotal').html(paytotal.toFixed(2));
        }
    });

    if (rpt_list_json.length == 0) {
    	$('#rpt_panel').remove();
    }
});
function disableOtherEdit(showText){
	$('a[nc_type="buy_edit"]').each(function(){
	    if ($(this).css('display') != 'none'){
			$(this).after('<font color="#B0B0B0">' + showText + '</font>');
		    $(this).hide();
	    }
	});
	disableSubmitOrder();
}
function ableOtherEdit(){
	$('a[nc_type="buy_edit"]').show().next('font').remove();
	ableSubmitOrder();

}
function ableSubmitOrder(){
	$('#submitOrder').on('click',function(){submitNext()}).addClass('ok');
}
function disableSubmitOrder(){
	$('#submitOrder').unbind('click').removeClass('ok');
}

</script> 
<form method="post" id="order_form" name="order_form" action="index.php">

<!-- S fcode -->
<?php if ($output['current_goods_info']['is_fcode']) { ?>
    <?php include template('buy/buy_fcode');?>
<?php } ?>
<!-- E fcode -->

<div class="ncc-main">
  <div class="ncc-title">
    <h3><?php echo $lang['cart_index_ensure_info'];?></h3>
    <h5>请仔细核对填写收货、发票等信息，以确保物流快递及时准确投递。</h5>
  </div>
    <?php include template('buy/buy_address');?>
    <?php include template('buy/buy_payment');?>
    <?php include template('buy/buy_invoice');?>

    <?php if ($output['current_goods_info']['is_book']) { ?>
        <?php include template('buy/buy_book_goods');?>
    <?php } else { ?>
        <?php include template('buy/buy_goods_list');?>
    <?php } ?>

    <input value="buy" type="hidden" name="act">
    <input value="buy_step2" type="hidden" name="op">
    <!-- 来源于购物车标志 -->
    <input value="<?php echo $output['ifcart'];?>" type="hidden" name="ifcart">

    <!-- offline/online -->
    <input value="online" name="pay_name" id="pay_name" type="hidden">

    <!-- 是否保存增值税发票判断标志 -->
    <input value="<?php echo $output['vat_hash'];?>" name="vat_hash" type="hidden">

    <!-- 收货地址ID -->
    <input value="<?php echo $output['address_info']['address_id'];?>" name="address_id" id="address_id" type="hidden">

    <!-- 城市ID(运费) -->
    <input value="" name="buy_city_id" id="buy_city_id" type="hidden">

    <!-- 自提门店 -->
    <input value="" name="chain[id]" id="input_chain_id" type="hidden">
    <input value="" name="chain[buyer_name]" id="input_chain_buyer_name" type="hidden">
    <input value="" name="chain[tel_phone]" id="input_chain_tel_phone" type="hidden">
    <input value="" name="chain[mob_phone]" id="input_chain_mob_phone" type="hidden">

    <!-- 记录所选地区是否支持货到付款 第一个前端JS判断 第二个后端PHP判断 -->
    <input value="" id="allow_offpay" name="allow_offpay" type="hidden">
    <input value="" id="allow_offpay_batch" name="allow_offpay_batch" type="hidden">
    <input value="" id="offpay_hash" name="offpay_hash" type="hidden">
    <input value="" id="offpay_hash_batch" name="offpay_hash_batch" type="hidden">

    <!-- 默认使用的发票 -->
    <input value="<?php echo $output['inv_info']['inv_id'];?>" name="invoice_id" id="invoice_id" type="hidden">
    <input value="<?php echo getReferer();?>" name="ref_url" type="hidden">
</div>
</form>
