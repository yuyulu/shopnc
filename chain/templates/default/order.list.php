<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="alert alert-block mt10">
  <ul class="mt5">
    <li>该列表可以查看待自提和已经自提的订单，对于到门店付款的自提订单，请确保收到款后再进行自提出货操作。</li>
  </ul>
</div>
<form method="get" action="index.php" target="_self">
  <table class="search-form">
    <input type="hidden" name="act" value="order" />
    <input type="hidden" name="op" value="index" />
    <tr>
      <td>&nbsp;</td>
      <th>订单状态</th>
      <td class="w100"><select name="search_state_type">
          <option value="no" <?php if ($_GET['search_state_type'] == 'no') {?>selected<?php }?>>待自提</option>
          <option value="yes" <?php if ($_GET['search_state_type'] == 'yes') {?>selected<?php }?>>已自提</option>
        </select></td>
      <th> <select name="search_key_type">
          <option value="chain_code" <?php if ($_GET['search_key_type'] == 'chain_code') {?>selected<?php }?>>提货码</option>
          <option value="order_sn" <?php if ($_GET['search_key_type'] == 'order_sn') {?>selected<?php }?>>订单号</option>
          <option value="buyer_phone" <?php if ($_GET['search_key_type'] == 'buyer_phone') {?>selected<?php }?>>手机号</option>
        </select>
      </th>
      <td class="w160"><input type="text" class="text w150" name="keyword" value="<?php echo $_GET['keyword']; ?>"/></td>
      <td class="tc w70"><label class="submit-border">
          <input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>" />
        </label></td>
    </tr>
  </table>
</form>
<table class="ncsc-default-table">
  <thead>
    <tr nc_type="table_header">
    <th class="w20"></th>
      <th colspan="2">商品</th>
      <th class="w150">成交价（元）</th>
      <th class="w60">数量</th>
      <th class="w150">订单金额（元）</th>
      <th class="w180">收货人</th>
      <th class="w150">订单状态</th>
      <th class="w120">操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['order_list']) && is_array($output['order_list'])) { ?>
    <?php foreach ($output['order_list'] as $order_info) { ?>
    <tr>
      <th colspan="20" class="tl"><span class="ml10">订单编号：<?php echo $order_info['order_sn'];?></span><span class="ml20">下单时间：<?php echo date('Y-m-d H:i:s',$order_info['add_time']);?></span></th>
    </tr>
    <?php $_i = 0;?>
    <?php $_cont = count($order_info['extend_order_goods']);?>
    <?php foreach ($order_info['extend_order_goods'] as $goods_info) { ?>
    <tr>
    <td class="bdl"></td>
      <td class="w70">
        <div class="goods-thumb"><a href="<?php echo $goods_info['goods_url'];?>" target="_blank"><img src="<?php echo $goods_info['image_url'] ?>"/></a></div></td>
        <td>
        <dl class="goods-info">
          <dt class="goods-name"><a href="<?php echo $goods_info['goods_url'];?>" target="_blank"><?php echo $goods_info['goods_name'];?></a></dt>
          <dd class="goods-spec"><?php echo $goods_info['goods_spec'];?></dd>
          <dd class="goods-type"><?php echo $goods_info['goods_type'] == 5 ? '赠品':''?></dd>
        </dl>
        </td>
      <td><em class="goods-price"><?php echo $goods_info['goods_price'];?></em></td>
      <td><?php echo $goods_info['goods_num'];?></td>
      <?php if ($_i == 0) { ?>
      <td rowspan="<?php echo $_cont;?>" class="bdl"><em class="order-amount"><?php echo ncPriceFormat($order_info['order_amount']);?></em></td>
      <td rowspan="<?php echo $_cont;?>" class="bdl"><p>收货人：<?php echo $order_info['extend_order_common']['reciver_name'];?></p><p>电话：<?php echo $order_info['buyer_phone'];?></p></td>
      <td rowspan="<?php echo $_cont;?>" class="bdl"><?php echo $order_info['state_desc']; ?></td>
      <td rowspan="<?php echo $_cont;?>" class="nscs-table-handle bdl bdr"><?php if ($order_info['order_state'] != ORDER_STATE_SUCCESS) { ?>
        <?php if ($order_info['payment_code'] == 'chain') { ?>
        <span><a href="javascript:void(0);" class="btn-bluejeans" onclick="javascript:ajax_form('pickup_parcel', '付款并自提', 'index.php?act=order&op=pickup_parcel&order_id=<?php echo $order_info['order_id'];?>&payment_code=<?php echo $order_info['payment_code']?>',900)"><i class="icon-truck"></i>
        <p>付款自提</p>
        </a></span>
        <?php } else { ?>
        <span><a href="javascript:void(0);" class="btn-bluejeans" onclick="javascript:ajax_form('pickup_parcel', '自提', 'index.php?act=order&op=pickup_parcel&order_id=<?php echo $order_info['order_id'];?>',900)"><i class="icon-truck"></i>
        <p>自提</p>
        </a></span>
        <?php } ?>
        <?php } ?></td>
        <?php } ?>
    </tr>
    <tr style="display:none;">
      <td colspan="20"><div class="ncsc-goods-sku ps-container"></div></td>
    </tr>
    <?php $_i ++;?>
    <?php } ?>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php  if (!empty($output['order_list'])) { ?>
    <tr>
      <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?> </div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
