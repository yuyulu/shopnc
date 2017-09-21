<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php defined('In33hao') or exit('Access Invalid!');?>

<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/seller_center.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
body { background: #FFF none;
}
</style>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.printarea.js" charset="utf-8"></script>
<title><?php echo $lang['member_printorder_print'];?>--<?php echo $output['store_info']['store_name'];?><?php echo $lang['member_printorder_title'];?></title>
<style>
.print-layout .a5-size, .print-layout .a4-size {
	background: #FFF;
	border: dashed 1px #ccc;
	width: 210mm;
	position: absolute;
	top: 5mm;
	left: 5mm;
	padding: 1px;
	
	height: 148mm;
    z-index: 2;
}
</style>
</head>

<body>
<?php if (!empty($output['order_list'])){?>
<div class="print-layout">
  <div class="print-btn" id="printbtn" title="<?php echo $lang['member_printorder_print_tip'];?>"><i></i><a href="javascript:void(0);"><?php echo $lang['member_printorder_print'];?></a></div>
  <div class="a5-size"></div>
  <!--<dl class="a5-tip">
    <dt>
      <h1>A5</h1>
      <em>Size: 210mm x 148mm</em></dt>
    <dd><?php echo $lang['member_printorder_print_tip_A5'];?></dd>
  </dl>
  <div class="a4-size"></div>
  <dl class="a4-tip">
    <dt>
      <h1>A4</h1>
      <em>Size: 210mm x 297mm</em></dt>
    <dd><?php echo $lang['member_printorder_print_tip_A4'];?></dd>
  </dl>-->
 
  <div class="print-page" style="width:58mm;">
    <div id="printarea">
	
	
	  <div id="print_container">
		<table border=0 style="width:58mm;font-size:10px;">
		<?php foreach ($output['order_list'] as $item_k =>$item_v){?>
		<tr>
		<td><strong><?php echo $item_v['store_name'];?>订单</strong><td>
		</tr>
		<!--<tr>
		<td>------------------------------------<td>
		</tr>-->

		<tr>
		<td>订单号：<?php echo $item_v['order_sn'];?></td>
		</tr>

		<tr>
		<td>支付状态：<?php echo $item_v['state_desc'];?></td>
		</tr>
		<tr>
		<td>下单时间：<?php echo @date("Y-m-d H:i:s",$item_v['add_time']); ?></td>
		</tr>
		<tr>
		<td>收货人：<?php echo $item_v['extend_order_common']['reciver_name']; ?> </td>
		</tr>

		<tr>
		<td>手机：<?php echo $item_v['extend_order_common']['reciver_info']['phone'];?></td>
		</tr>

		<tr>
		<td>地址：<?php echo $item_v['extend_order_common']['reciver_info']['address'];?></td>
		</tr>

		<!--<tr>
		<td>配送方式：{$v.delivery_name}</td>
		</tr>

		<tr>
		<td>收款方式：{$v.payment_name}</td>
		</tr>-->

		<tr>
		<td>订单备注：<?php echo $item_v['extend_order_common']['order_message'];?></td>
		</tr>

		<tr><td>
		<table border='0' style='width:58mm;font-size:10px;text-align:left;'>
		<tr>
		<td colspan="4"><strong>商品明细</strong></td>
		<!--<td>序号</td>
		<td>名称</td>
		<td style="width:24px;">数量</td>
		<td>单价</td>-->
		</tr>
		<?php foreach($item_v['goods_list'] as $k => $goods) { ?>
		<tr>
		<td><?php echo $k+1;?></td>
		<td><?php echo $goods['goods_name'];?></td>
		<td style="width:10mm;">数量：<?php echo $goods['goods_num'];?></td>
		<td style="width:10mm;">金额：<?php echo $goods['goods_price'];?></td>
		</tr>
		<?php } ?>
		</table>
		</td></tr>

		<tr>
		<td><span style="float:right;">总计：<?php echo $item_v['goods_total_price'];?>元</span></td>
		</tr>
		<!--<tr>
		<td>&nbsp;<td>
		</tr>-->
		<tr>
		<td>&nbsp;<td>
		</tr>
		<!--<tr>
		<td>------------------------------------<td>
		</tr>-->
		<tr>
		<td>&nbsp;<td>
		</tr>
		<tr>
		<td>&nbsp;<td>
		</tr>
		<tr>
		<td>&nbsp;<td>
		</tr>
		<tr>
		<td>&nbsp;<td>
		</tr>
		<tr>
		<td>&nbsp;<td>
		</tr>
		<?php } ?>
		</table>
		</div>
		<!--endprint-->
	  
    </div>
    <?php }?>
  </div>
  
</div>
</body>
<script>

$(function(){
	$("#printarea").printArea();
	//window.print();
	$("#printbtn").click(function(){
	   $("#printarea").printArea();
	});
	
});

//打印提示
$('#printbtn').poshytip({
	className: 'tip-yellowsimple',
	showTimeout: 1,
	alignTo: 'target',
	alignX: 'center',
	alignY: 'bottom',
	offsetY: 5,
	allowTipHover: false
});
</script>
</html>