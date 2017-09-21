<?php defined('In33hao') or exit('Access Invalid!');?>

<div>
  <div class="ncap-form-default">
   <dl class="row">
      <dt class="tit">活动名称</dt>
      <dd class="opt">
        <?php echo $output['mansong_info']['mansong_name']; ?>
      </dd>
    </dl>
     <dl class="row">
      <dt class="tit">活动店铺</dt>
      <dd class="opt">
        <?php echo $output['mansong_info']['store_name']; ?>
      </dd>
    </dl>
     <dl class="row">
      <dt class="tit">活动时间段</dt>
      <dd class="opt">
        <?php echo date('Y-m-d H:i', $output['mansong_info']['start_time']); ?>
        ~
        <?php echo date('Y-m-d H:i', $output['mansong_info']['end_time']); ?>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit"> 活动规则 </dt>
      <dd class="opt">
        <ul class="promotion-ms"><?php foreach ((array) $output['list'] as $val): ?>
        <li>
        <span> 单笔订单满 <strong><?php echo $val['price']; ?></strong> 元 </span>
        <?php if ($val['discount']): ?>
        <span> 立减现金 <strong><?php echo $val['discount']; ?></strong> 元 </span>
        <?php endif; ?>
        <?php if ($val['goods_id']): ?>
        <span> 赠送礼品 <a href="<?php echo $val['goods_url']; ?>" title="<?php echo $val['mansong_goods_name']; ?>" target="_blank"><img  onMouseOver="toolTip('<img src=<?php echo cthumb($val['goods_image'], 60); ?>>')" onMouseOut="toolTip()" src="<?php echo cthumb($val['goods_image'], 60); ?>"/></a> </span>
        <?php endif; ?></li>
        <?php endforeach; ?></ul>
      </dd>
    </dl>
  </div>
</div>
