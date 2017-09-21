<?php defined('In33hao') or exit('Access Invalid!');?>
<?php if (!empty($output['goods_list'])) {$i=1;?>
<?php foreach ($output['goods_list'] as $goods) {?>
<?php if (empty($_GET['curpage']) && $i==1) {$i++;?>

<li class="gallery-album-title"><i></i>
  <h4><?php echo date('Y-m-d', $goods['goods_addtime']);?></h4>
</li>
<?php } else if ($time != date('Y-m-d', $goods['goods_addtime'])) {?>
<li class="gallery-album-title"><i></i>
  <h4><?php echo date('Y-m-d', $goods['goods_addtime']);?></h4>
</li>
<?php }?>
<li class="fav-item favorites-store-items">
  <div class="goods-thumb"><a href="index.php?act=goods&goods_id=<?php echo $goods['goods_id'];?>" title="<?php echo $goods['goods_name'];?>" target="_blank"><img src="<?php echo thumb($goods, 240);?>" /></a></div>
  <p class="goods-price">￥<?php echo $goods['goods_promotion_price'];?></p>
</li>
<?php $time = date('Y-m-d', $goods['goods_addtime']);}?>
<?php }?>
