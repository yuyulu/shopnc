<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="javascript:history.back(-1)" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['vr_order_manage'];?></h3>
        <h5><?php echo $lang['vr_order_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="ncap-order-style">
    <div class="titile">
      <h3></h3>
    </div>
    <div class="ncap-order-flow">
      <ol class="num5">
        <li class="current">
          <h5>生成订单</h5>
          <i class="fa fa-arrow-circle-right"></i>
          <time><?php echo date("Y-m-d H:i:s",$output['order_info']['add_time']); ?></time>
        </li>
        <?php if ($output['order_info']['order_state'] == ORDER_STATE_CANCEL) { ?>
        <li class="current">
          <h5>取消订单</h5>
          <time><?php echo date('Y-m-d H:i:s',$output['order_info']['close_time']); ?></time>
        </li>        
        <?php } else { ?>
        <li class="<?php echo $output['order_info']['step_list']['step3'] ? 'current' : null ; ?>" style=" width: 40%;">
          <h5>发放兑换码</h5>
          <i class="fa fa-arrow-circle-right"></i>
          <?php if (!empty($output['order_info']['extend_vr_order_code'])){ ?>
          <div class="code-list tip" title="如列表过长超出显示区域时可滚动鼠标进行查看">
            <div id="codeList">
              <ul>
                <?php foreach($output['order_info']['extend_vr_order_code'] as $code_info){ ?>
                <li class="<?php echo $code_info['vr_state'] == 1 ? 'used' : null;?>"><strong><?php echo $code_info['vr_code'];?></strong> <?php echo $code_info['vr_code_desc'];?> </li>
                <?php } ?>
              </ul>
            </div>
          </div>
          <?php } ?>
        </li>
        <li class="long <?php echo $output['order_info']['step_list']['step4'] ? 'current' : null ; ?>">
          <h5>订单完成</h5>
          <time><?php echo date("Y-m-d H:i:s",$output['order_info']['finnshed_time']); ?></time>
        </li>
        <?php } ?>
      </ol>
    </div>
    <div class="ncap-order-details">
      <ul class="tabs-nav">
        <li class="current"><a href="javascript:void(0);"><?php echo $lang['order_detail'];?></a></li>
        <?php if(is_array($output['refund_list']) and !empty($output['refund_list'])) { ?>
        <li><a href="javascript:void(0);">退款记录</a></li>
        <?php } ?>
      </ul>
      <div class="tabs-panels">
        <div class="misc-info">
          <h4>下单/支付</h4>
          <dl>
            <dt><?php echo $lang['order_number'];?><?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo $output['order_info']['order_sn'];?></dd>
            <dt>订单来源<?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo str_replace(array(1,2), array('PC端','移动端'),$output['order_info']['order_from']);?></dd>
            <dt><?php echo $lang['order_time'];?><?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo date('Y-m-d H:i:s',$output['order_info']['add_time']);?></dd>
          </dl>
          <?php if(intval($output['order_info']['payment_time'])){?>
          <dl>
            <dt>支付单号<?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo $output['order_info']['order_sn'];?></dd>
            <dt><?php echo $lang['payment'];?><?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo orderPaymentName($output['order_info']['payment_code']);?></dd>
            <dt><?php echo $lang['payment_time'];?><?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo intval(date('H:i:s',$output['order_info']['payment_time'])) ? date('Y-m-d H:i:s',$output['order_info']['payment_time']) : date('Y-m-d',$output['order_info']['payment_time']);?></dd>
          </dl>
          <?php }?>
          <?php if ($output['order_info']['order_state'] == ORDER_STATE_CANCEL) { ?>
          <dl>
            <dt>订单取消日志<?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo $output['order_info']['close_reason'];?></dd>
          </dl>
          <?php } ?>
        </div>
        <div class="addr-note">
          <h4>购买/收货方信息</h4>
          <dl>
            <dt><?php echo $lang['buyer_name'];?><?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo $output['order_info']['buyer_name'];?></dd>
            <dt>接收手机<?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo $output['order_info']['buyer_phone'];?></dd>
          </dl>
          <dl>
            <dt>买家留言<?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo $output['order_info']['buyer_msg'];?></dd>
          </dl>
        </div>
        <div class="contact-info">
          <h4>销售/发货方信息</h4>
          <dl>
            <dt><?php echo $lang['store_name'];?><?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo $output['order_info']['store_name'];?></dd>
            <dt>店主名称<?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo $output['store_info']['seller_name'];?></dd>
            <dt>联系电话<?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo !empty($output['store_info']['live_store_tel']) ? $output['store_info']['live_store_tel'] : $output['store_info']['store_phone']; ?></dd>
          </dl>
          <dl>
            <dt>地&#12288;&#12288;址<?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo !empty($output['store_info']['live_store_address']) ? $output['store_info']['live_store_address'] : $output['store_info']['store_address']; ?></dd>
          </dl>
          <dl>
            <dt>交通信息<?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo $output['store_info']['live_store_bus'];?></dd>
          </dl>
        </div>
        <div class="goods-info">
          <h4><?php echo $lang['product_info'];?></h4>
          <table>
            <thead>
              <tr>
                <th colspan="2">商品</th>
                <th>单价</th>
                <th><?php echo $lang['product_num'];?></th>
                <th>佣金比例</th>
                <th>收取佣金</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="w30"><div class="goods-thumb"><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=goods&goods_id=<?php echo $output['order_info']['goods_id'];?>" target="_blank"><img alt="<?php echo $lang['product_pic'];?>" src="<?php echo thumb($output['order_info'], 60);?>" /> </a></span></div></td>
                <td style="text-align: left;"><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=goods&goods_id=<?php echo $output['order_info']['goods_id'];?>" target="_blank"><?php echo $output['order_info']['goods_name'];?></a><span class="rec"><a target="_blank" href="<?php echo urlShop('vr_snapshot', 'index', array('order_id' => $output['order_info']['order_id']));?>">[交易快照]</a></span>
                <?php if ($output['order_info']['goods_spec']) { ?>
                <?php echo $output['order_info']['goods_spec'];?>
                <?php } ?>
                  <?php if ($output['order_info']['order_promotion_type'] == 1) {?>
                  抢购，
                  <?php } ?>
                  使用时效：即日起 至 <?php echo date("Y-m-d",$output['order_info']['vr_indate']);?>
                  <?php if ($output['order_info']['vr_invalid_refund'] == '0') { ?>
                  ，过期不退款
                  <?php } ?></td>
                <td class="w80"><?php echo $lang['currency'].ncPriceFormat($output['order_info']['goods_price']);?></td>
                <td class="w60"><?php echo $output['order_info']['goods_num'];?></td>
                <td class="w60"><?php echo $output['order_info']['commis_rate'] == 200 ? '' : $output['order_info']['commis_rate'].'%';?></td>
                <td class="w80"><?php echo $output['order_info']['commis_rate'] == 200 ? '' : ncPriceFormat($output['order_info']['goods_price']*$output['order_info']['commis_rate']/100);?></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="total-amount">
          <h3><?php echo $lang['order_total_price'];?><?php echo $lang['nc_colon'];?><strong class="red_common"><?php echo $lang['currency'].ncPriceFormat($output['order_info']['order_amount']);?></strong></h3>
        </div>
      </div>
      <?php if(is_array($output['refund_list']) and !empty($output['refund_list'])) { ?>
      <div class="tabs-panels tabs-hide">
        <div>
          <h4>退款信息</h4>
          <?php foreach($output['refund_list'] as $val) { ?>
          <dl>
            <dt>退款单号<?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo $val['refund_sn'];?></dd>
            <dt>退款金额<?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo $lang['currency'];?><?php echo ncPriceFormat($val['refund_amount']); ?></dd>
            <dt>管理员操作时间<?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo intval($val['admin_time']) ? date("Y-m-d H:i:s",$val['admin_time']) : null; ?></dd>
            <dt>备注<?php echo $lang['nc_colon'];?></dt>
            <dd><?php echo $val['goods_name'];?></dd>
            <dt>状态：</dt>
            <dd><?php echo str_replace(array(1,2,3), array('待审核','成功退款','管理员拒绝退款'), $val['admin_state'])?></dd>
          </dl>
          <?php } ?>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
<script type="text/javascript">
//兑换码列表过多时出现滚条
$(function(){
	$('#codeList').perfectScrollbar();
    $(".tabs-nav > li > a").mousemove(function(e) {
        if (e.target == this) {
            var tabs = $(this).parent().parent().children("li");
            var panels = $(this).parents('.ncap-order-details:first').children(".tabs-panels");
            var index = $.inArray(this, $(this).parents('ul').find("a"));
            if (panels.eq(index)[0]) {
                tabs.removeClass("current").eq(index).addClass("current");
               panels.addClass("tabs-hide").eq(index).removeClass("tabs-hide");
            }
        }
    });
});
</script>