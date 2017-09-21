<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="nch-module nch-module-style01">
  <div class="title">
    <h3>热销推荐</h3>
  </div>
  <div class="content">
    <?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){ ?>
    <ul class="nch-module-recommend">
      <?php foreach($output['goods_list'] as $k=>$v){?>
      <li>
        <div class="goods-pic"> <a href="<?php echo urlShop('goods','index',array('goods_id'=>$v['goods_id']));?>" target="_blank"><img alt="" src="<?php echo cthumb($v['goods_image'], 240);?>"></a> </div>
        <dl class="goods-info">
          <dt><a href="<?php echo urlShop('goods','index',array('goods_id'=>$v['goods_id']));?>" title="<?php echo $v['goods_name'];?>" target="_blank">
            <?php if ($v['goods_promotion_type'] == 1){ ?>
            <span>抢购</span>
            <?php } elseif ($v['goods_promotion_type'] == 2) { ?>
            <span>限时折扣</span>
            <?php } ?>
            <?php echo $v['goods_name'];?></a></dt>
          <dd class="goods-price">商城价：<em><?php echo ncPriceFormatForList($v['goods_promotion_price']);?></em></dd>
          <dd class="buy-btn"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$v['goods_id']));?>" target="_blank">立即抢购</a></dd>
        </dl>
      </li>
      <?php } ?>
    </ul>
    <?php } else { ?>
    <div class="noguess">暂无商品向您推荐</div>
    <?php }?>
  </div>
</div>
