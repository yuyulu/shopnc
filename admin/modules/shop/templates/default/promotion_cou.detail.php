<?php defined('In33hao') or exit('Access Invalid!');?>
<?php
$detail = $output['couDetail'];
if (empty($detail)) {
    return;
}

$info = $detail['info'];
if (empty($info)) {
    return;
}
?>

<div class="ncap-form-default">
  <dl class="row">
    <dt class="tit">活动名称</dt>
    <dd class="opt"> <?php echo $info['name']; ?> </dd>
  </dl>
  <dl class="row">
    <dt class="tit">活动店铺</dt>
    <dd class="opt"> <?php echo $info['store_name']; ?> </dd>
  </dl>
  <dl class="row">
    <dt class="tit">活动时间段</dt>
    <dd class="opt"> <?php echo date('Y-m-d H:i', $info['tstart']); ?> ~ <?php echo date('Y-m-d H:i', $info['tend']); ?> </dd>
  </dl>
  <dl class="row">
    <dt class="tit">活动参与商品</dt>
    <dd class="opt">
      <div class="rule-goods-list">
        <ul>
          <?php foreach ((array) $detail['skus'] as $sku) { $g = $detail['items'][$sku]; if (empty($g)) { continue; } ?>
          <li title="<?php echo $g['goods_name']; ?>"> <img alt="" src="<?php echo cthumb($g['goods_image'], 60); ?>" style="width:30px;" /><a target="_blank" href="<?php echo urlShop('goods', 'index', array('goods_id' => $sku, )); ?>"><?php echo $g['goods_name']; ?> </a> <span>商品价：<em> ￥<?php echo $g['goods_promotion_price']; ?></em></span>
            <?php } ?>
          </li>
        </ul>
      </div>
    </dd>
  </dl>
  <dl class="row">
    <dt class="tit">活动换购规则</dt>
    <dd class="opt">
      <div class="rule-goods-list">
        <?php foreach ((array) $detail['levels'] as $levelId => $v) { ?>
        <div class="cou-rule"><span>规则<?php echo $levelId; ?>：消费满<strong><?php echo $v['mincost']; ?></strong>元
          <?php if ($v['maxcou'] > 0) { ?>
          可换购最多<strong><?php echo $v['maxcou']; ?></strong>种优惠商品
          <?php } else { ?>
          可换购任意多种优惠商品
          <?php } ?>
          </span>
          <ul>
            <?php foreach ((array) $detail['levelSkus'][$levelId] as $sku => $vv) { $g = $detail['items'][$sku]; if (empty($g)) { continue; }?>
            <li title="<?php echo $g['goods_name']; ?>"><img alt="" src="<?php echo cthumb($g['goods_image'], 60); ?>" style="width:30px;" /><a target="_blank" href="<?php echo urlShop('goods', 'index', array('goods_id' => $sku, )); ?>"><?php echo $g['goods_name']; ?></a><span>换购价：<em> ￥<?php echo $vv['price']; ?></em></span> </li>
            <?php } ?>
          </ul></div>
          <?php } ?>

      </div>
    </dd>
  </dl>
</div>
<script>
$(function(){
    $(".rule-goods-list").perfectScrollbar();
});
</script>
