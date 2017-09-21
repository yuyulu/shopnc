<?php defined('In33hao') or exit('Access Invalid!');?>

  <div class="ncm-flow-item">
    <div class="title">相关商品交易信息</div>
    <div class="item-goods">
        <?php if (is_array($output['order']) && !empty($output['order'])) { ?>
      <dl>
        <dt>
          <div class="ncm-goods-thumb-mini"><a target="_blank" href="<?php echo urlShop('goods','index',array('goods_id'=> $output['order']['goods_id'])); ?>">
            <img src="<?php echo thumb($output['order'],60); ?>" onMouseOver="toolTip('<img src=<?php echo thumb($output['order'],240); ?>>')" onMouseOut="toolTip()" /></a></div>
        </dt>
        <dd><a target="_blank" href="<?php echo urlShop('goods','index',array('goods_id'=> $output['order']['goods_id'])); ?>"><?php echo $output['order']['goods_name']; ?></a>
            <?php echo $lang['currency'];?><?php echo ncPriceFormat($output['order']['goods_price']); ?> * <?php echo $output['order']['goods_num']; ?> <font color="#AAA">(数量)</font>
        </dd>
      </dl>
        <?php } ?>
    </div>
    <div class="item-order">
      <dl>
        <dt>使用时效：</dt>
        <dd>即日起 至 <?php echo date("Y-m-d",$output['order']['vr_indate']);?></dd>
      </dl>
      <dl>
        <dt>订单总额：</dt>
        <dd><strong><?php echo $lang['currency'];?><?php echo ncPriceFormat($output['order']['order_amount']); ?>
          <?php if ($output['order']['refund_amount'] > 0) { ?>
          (<?php echo $lang['refund_add'].$lang['nc_colon'].$lang['currency'].$output['order']['refund_amount'];?>)
          <?php } ?>
          </strong> </dd>
      </dl>
      <dl class="line">
        <dt>订单编号：</dt>
        <dd><a target="_blank" href="index.php?act=member_vr_order&op=show_order&order_id=<?php echo $output['order']['order_id']; ?>"><?php echo $output['order']['order_sn'];?></a>
            <a href="javascript:void(0);" class="a">更多<i class="icon-angle-down"></i>
          <div class="more"> <span class="arrow"></span>
            <ul>
              <li><?php echo $lang['member_order_pay_method'].$lang['nc_colon'];?><span><?php echo $output['order']['payment_name']; ?></span></li>
              <li><?php echo $lang['member_order_time'].$lang['nc_colon'];?><span><?php echo date("Y-m-d H:i:s",$output['order']['add_time']); ?></span></li>
              <?php if ($output['order']['payment_time'] > 0) { ?>
              <li><?php echo $lang['member_show_order_pay_time'].$lang['nc_colon'];?><span><?php echo date("Y-m-d H:i:s",$output['order']['payment_time']); ?></span></li>
              <?php } ?>
              <?php if ($output['order']['finnshed_time'] > 0) { ?>
              <li><?php echo $lang['member_show_order_finish_time'].$lang['nc_colon'];?><span><?php echo date("Y-m-d H:i:s",$output['order']['finnshed_time']); ?></span></li>
              <?php } ?>
            </ul>
          </div>
          </a> </dd>
      </dl>
      <dl class="line">
        <dt>商&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;家：</dt>
        <dd><?php echo $output['order']['store_name'];?><a href="javascript:void(0)" class="ncbtn a" nc_type="dialog" dialog_width="800" dialog_title="查看地图" dialog_id="show_map" uri="index.php?act=show_map&op=index&w=440&h=400&store_id=<?php echo $output['order']['store_id'];?>">商家所在信息
          </a>
        </dd>
      </dl>
    </div>
  </div>