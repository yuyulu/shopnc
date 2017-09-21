<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="javascript:history.back(-1)" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>虚拟订单退款 - 查看退单“退单编号：<?php echo $output['refund']['refund_sn']; ?>”</h3>
        <h5>虚拟类商品订单退款申请及审核处理</h5>
      </div>
    </div>
  </div>
  <div class="ncap-form-default">
    <div class="title">
      <h3>买家退款申请</h3>
    </div>
      <dl class="row">
        <dt class="tit">申请时间</dt>
        <dd class="opt"><?php echo date('Y-m-d H:i:s',$output['refund']['add_time']); ?> </dd>
      </dl>
    <dl class="row">
      <dt class="tit">商品名称</dt>
      <dd class="opt"><a href="<?php echo urlShop('goods','index',array('goods_id'=> $output['refund']['goods_id']));?>" target="_blank"><?php echo $output['refund']['goods_name']; ?></a></dd>
    </dl>
    <dl class="row">
      <dt class="tit">兑换码</dt>
      <dd class="opt">
        <?php if (is_array($output['code_array']) && !empty($output['code_array'])) { ?>
        <?php foreach ($output['code_array'] as $key => $val) { ?>
        <?php echo $val;?><br />
        <?php } ?>
        <?php } ?>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit"><?php echo $lang['refund_order_refund'];?></dt>
      <dd class="opt"><?php echo ncPriceFormat($output['refund']['refund_amount']); ?></dd>
    </dl>
    <dl class="row">
      <dt class="tit">退款说明</dt>
      <dd class="opt"><?php echo $output['refund']['buyer_message']; ?></dd>
    </dl>
    <div class="title">
      <h3>平台退款审核</h3>
    </div>
    <dl class="row">
      <dt class="tit">平台处理</dt>
      <dd class="opt"><?php echo $output['admin_array'][$output['refund']['admin_state']];?></dd>
    </dl>
    <?php if ($output['refund']['admin_state'] > 1) { ?>
    <dl class="row">
      <dt class="tit"><?php echo $lang['refund_message'];?></dt>
      <dd class="opt"><?php echo $output['refund']['admin_message']; ?></dd>
    </dl>
    <dl class="row">
      <dt class="tit">处理时间</dt>
      <dd class="opt"><?php echo $output['refund']['admin_time'] ? date('Y-m-d H:i:s',$output['refund']['admin_time']) : null; ?> </dd>
    </dl>
    <?php if ($output['detail_array']['refund_state'] == 2) { ?>
    <div class="title">
      <h3>退款详细</h3>
    </div>
    <dl class="row">
      <dt class="tit">支付方式</dt>
      <dd class="opt"><?php echo orderPaymentName($output['detail_array']['refund_code']);?></dd>
    </dl>
    <dl class="row">
      <dt class="tit">在线退款金额</dt>
      <dd class="opt"><?php echo ncPriceFormat($output['detail_array']['pay_amount']); ?> </dd>
    </dl>
    <dl class="row">
      <dt class="tit">预存款金额</dt>
      <dd class="opt"><?php echo ncPriceFormat($output['detail_array']['pd_amount']); ?> </dd>
    </dl>
    <dl class="row">
      <dt class="tit">充值卡金额</dt>
      <dd class="opt"><?php echo ncPriceFormat($output['detail_array']['rcb_amount']); ?> </dd>
    </dl>
    <?php } ?>
    <?php } ?>
  </div>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script type="text/javascript">
$(function(){
    $('.nyroModal').nyroModal();
});
</script>