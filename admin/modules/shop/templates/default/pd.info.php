<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="ncap-form-default">
  <dl class="row">
    <dt class="tit">
      <label><?php echo $lang['admin_predeposit_sn']; ?></label>
    </dt>
    <dd class="opt"> <?php echo $output['info']['pdr_sn']; ?> </dd>
  </dl>
  <dl class="row">
    <dt class="tit">
      <label><?php echo $lang['admin_predeposit_membername']; ?></label>
    </dt>
    <dd class="opt"> <?php echo $output['info']['pdr_member_name']; ?> </dd>
  </dl>
  <dl class="row">
    <dt class="tit">
      <label><?php echo $lang['admin_predeposit_recharge_price'];?>(<?php echo $lang['currency_zh'];?>)</label>
    </dt>
    <dd class="opt"> <?php echo $output['info']['pdr_amount']; ?> </dd>
  </dl>
  <dl class="row">
    <dt class="tit">
      <label><?php echo $lang['admin_predeposit_apptime']?></label>
    </dt>
    <dd class="opt"> <?php echo @date('Y-m-d H:i:s',$output['info']['pdr_add_time']); ?> </dd>
  </dl>
  <?php if (intval($output['info']['pdr_payment_time'])) {?>
  <dl class="row">
    <dt class="tit">
      <label><?php echo $lang['admin_predeposit_payment'];?></label>
    </dt>
    <dd class="opt"> <?php echo $output['info']['pdr_payment_name']; ?> </dd>
  </dl>
  <dl class="row">
    <dt class="tit">
      <label><?php echo $lang['admin_predeposit_paytime'];?></label>
    </dt>
    <dd class="opt">
      <?php if (date('His',$output['info']['pdr_payment_time']) == 0) {?>
      <?php echo date('Y-m-d',$output['info']['pdr_payment_time']);?>
      <?php } else {?>
      <?php echo date('Y-m-d H:i:s',$output['info']['pdr_payment_time']);?>
      <?php } ?>
    </dd>
  </dl>
  <dl class="row">
    <dt class="tit">
      <label>第三方支付平台交易号</label>
    </dt>
    <dd class="opt"> <?php echo $output['info']['pdr_trade_sn'];?> </dd>
  </dl>
  <?php } ?>
  <!-- 显示管理员名称 -->
  <?php if (trim($output['info']['pdr_admin']) != ''){ ?>
  <dl class="row">
    <dt class="tit">
      <label><?php echo $lang['admin_predeposit_adminname'];?></label>
    </dt>
    <dd class="opt"> <?php echo $output['info']['pdr_admin']; ?> </dd>
  </dl>
  <?php }?>
  <?php if (!intval($output['info']['pdr_payment_state'])) {?>
  <div class="bot"><a  class="ncap-btn-big ncap-btn-green" href="index.php?act=predeposit&op=recharge_edit&id=<?php echo $output['info']['pdr_id']; ?>"><span><?php echo  $lang['admin_predeposit_payed'];?></span></a></div>
  <?php } ?>
</div>
