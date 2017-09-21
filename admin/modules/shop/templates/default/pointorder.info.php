<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"> <a class="back" href="<?php echo urlAdminShop('pointprod', 'pointorder_list'); ?>" title="返回列表"> <i class="fa fa-arrow-circle-o-left"></i> </a>
      <div class="subject">
        <h3>积分兑换 - 兑换详情</h3>
        <h5>查看商城会员使用积分兑换礼品的详情</h5>
      </div>
    </div>
  </div>
  <div class="ncap-order-style">
    <div class="ncap-order-flow">
      <ol class="num3">
        <li class="current">
          <h5>提交兑换</h5>
          <i class="fa fa-arrow-circle-right"></i>
          <time><?php echo @date('Y-m-d H:i:s',$output['order_info']['point_addtime']);?></time>
        </li>
        <li class="<?php if ($output['order_info']['point_shippingtime'] != ''){?>current<?php } ?>">
          <h5>礼品发货</h5>
          <i class="fa fa-arrow-circle-right"></i>
          <time><?php echo @date('Y-m-d H:i:s',$output['order_info']['point_shippingtime']);?></time>
        </li>
        <li class="<?php if($output['order_info']['point_finnshedtime'] != '') { ?>current<?php } ?>">
          <h5>确认收货</h5>
          <time><?php echo date("Y-m-d H:i:s",$output['order_info']['point_finnshedtime']); ?></time>
        </li>
      </ol>
    </div>
    <div class="ncap-order-details">
      <ul class="tabs-nav">
        <li class="current"><a href="javascript:void(0);"><?php echo $lang['admin_pointorder_info_orderdetail'];?></a></li>
      </ul>
      <div class="tabs-panels">
        <div class="misc-info">
          <h4><?php echo $lang['admin_pointorder_info_ordersimple'];?></h4>
          <dl>
            <dt><?php echo $lang['admin_pointorder_ordersn'];?><?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo $output['order_info']['point_ordersn'];?></dd>
            <dt><?php echo $lang['admin_pointorder_orderstate'];?><?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo $output['order_info']['point_orderstatetext']; ?></dd>
            <dt><?php echo $lang['admin_pointorder_addtime'];?><?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo @date('Y-m-d H:i:s',$output['order_info']['point_addtime']);?></dd>
          </dl>
        </div>
        <div class="addr-note">
          <h4>购买/收货方信息</h4>
          <dl>
            <dt><?php echo $lang['admin_pointorder_membername'];?><?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo $output['order_info']['point_buyername'];?></dd>
            <dt><?php echo $lang['admin_pointorder_info_memberemail']; ?><?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo $output['order_info']['point_buyeremail'];?></dd>
          </dl>
          <dl>
            <dt>收货地址<?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo $output['orderaddress_info']['point_truename'];?>&nbsp;,&nbsp;<?php echo $output['orderaddress_info']['point_telphone'];?>&nbsp;,&nbsp;<?php echo $output['orderaddress_info']['point_mobphone'];?>&nbsp;,&nbsp;<?php echo $output['orderaddress_info']['point_areainfo'];?>&nbsp;<?php echo $output['orderaddress_info']['point_address'];?></dd>
          </dl>
          <dl>
            <dt><?php echo $lang['admin_pointorder_info_ordermessage']; ?><?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo $output['order_info']['point_ordermessage'];?></dd>
          </dl>
        </div>
        <div class="contact-info">
          <h4>发货信息</h4>
          <dl>
            <dt>物流公司<?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo $output['express_info']['e_name']; ?></dd>
            <dt><?php echo $lang['admin_pointorder_shipping_code'];?><?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo $output['order_info']['point_shippingcode']; ?></dd>
            <dt><?php echo $lang['admin_pointorder_shipping_time'];?><?php echo $lang['nc_colon'];?></dt>
            <dd><?php if ($output['order_info']['point_shippingtime'] > 0) echo @date('Y-m-d',$output['order_info']['point_shippingtime']);?></dd>
          </dl>
        </div>
        <div class="goods-info">
          <h4><?php echo $lang['admin_pointorder_info_prodinfo'];?></h4>
          <table>
            <thead>
              <tr>
                <th colspan="2">兑换礼品</th>
                <th><?php echo $lang['admin_pointorder_exchangepoints'];?></th>
                <th><?php echo $lang['admin_pointorder_info_prodinfo_exnum'];?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($output['prod_list'] as $v){?>
              <tr>
                <td class="w30"><div class="goods-thumb"><a href="<?php echo urlShop('pointprod', 'pinfo', array('id' => $v['point_goodsid']));?>" target="_blank" class="order_info_pic"> <img src="<?php echo $v['point_goodsimage_small'];?>"/></a></div></td>
                <td style="text-align: left;"><a href="<?php echo urlShop('pointprod', 'pinfo', array('id' => $v['point_goodsid']));?>" target="_blank"><?php echo $v['point_goodsname'];?></a></td>
                <td class="w150"><?php echo $v['point_goodspoints'];?></td>
                <td class="w150"><?php echo $v['point_goodsnum'];?></td>
              </tr>
              <?php }?>
            </tbody>
          </table>
          <div class="total-amount">
            <h3><?php echo $lang['admin_pointorder_exchangepoints'];?><?php echo $lang['nc_colon'];?><strong class="red_common"><?php echo $output['order_info']['point_allpoint'];?></strong></h3>
            <?php if ($output['order_info']['point_shippingcharge'] == 1){ ?>
            <h4>(<?php echo $lang['admin_pointorder_shippingfee'];?><?php echo $lang['nc_colon'];?><?php echo $output['order_info']['point_shippingfee'];?>)</h4>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
