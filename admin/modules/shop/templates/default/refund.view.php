<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="javascript:history.back(-1)" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['refund_manage'];?> - 查看退单“退单编号：<?php echo $output['refund']['refund_sn']; ?>”</h3>
        <h5><?php echo $lang['refund_manage_subhead'];?></h5>
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
      <dd class="opt">
        <?php if ($output['refund']['goods_id'] > 0) { ?>
        <a href="<?php echo urlShop('goods','index',array('goods_id'=> $output['refund']['goods_id']));?>" target="_blank"><?php echo $output['refund']['goods_name']; ?></a>
        <?php }else { ?>
        <?php echo $output['refund']['goods_name']; ?>
        <?php } ?>
        </dd>
    </dl>
    <dl class="row">
      <dt class="tit"><?php echo $lang['refund_order_refund'];?></dt>
      <dd class="opt"><?php echo ncPriceFormat($output['refund']['refund_amount']); ?> </dd>
    </dl>
    <dl class="row">
      <dt class="tit"><?php echo $lang['refund_buyer_message'];?></dt>
      <dd class="opt"><?php echo $output['refund']['reason_info']; ?> </dd>
    </dl>
    <dl class="row">
      <dt class="tit">退款说明</dt>
      <dd class="opt"><?php echo $output['refund']['buyer_message']; ?> </dd>
    </dl>
    <dl class="row">
      <dt class="tit">凭证上传</dt>
      <dd class="opt">
        <?php if (is_array($output['pic_list']) && !empty($output['pic_list'])) { ?>
        <?php foreach ($output['pic_list'] as $key => $val) { ?>
        <?php if(!empty($val)){ ?>
        <a href="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/refund/'.$val;?>" class="nyroModal" rel="gal"> <img height="64" class="show_image" src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/refund/'.$val;?>"></a>
        <?php } ?>
        <?php } ?>
        <?php } ?>
      </dd>
    </dl>
    <div class="title">
      <h3>商家退款处理</h3>
    </div>
    <dl class="row">
      <dt class="tit">审核结果</dt>
      <dd class="opt"><?php echo $output['state_array'][$output['refund']['seller_state']];?> </dd>
    </dl>
    <dl class="row">
      <dt class="tit">处理备注</dt>
      <dd class="opt"><?php echo $output['refund']['seller_message']; ?> </dd>
    </dl>
    <dl class="row">
      <dt class="tit">处理时间</dt>
      <dd class="opt"><?php echo $output['refund']['seller_time'] ? date('Y-m-d H:i:s',$output['refund']['seller_time']) : null; ?> </dd>
    </dl>
    <?php if ($output['refund']['seller_state'] == 2) { ?>
    <div class="title">
      <h3>平台退款审核</h3>
    </div>
    <dl class="row">
      <dt class="tit">平台确认</dt>
      <dd class="opt"><?php echo $output['admin_array'][$output['refund']['refund_state']];?> </dd>
    </dl>
    <dl class="row">
      <dt class="tit">处理备注</dt>
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