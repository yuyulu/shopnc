<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="wrapper mt10">
  <div class="ncs-sale-rule">
  <div class="left"></div>
    <div class="mjs-tit"><?php echo $output['mansong_info']['mansong_name'];?>
      <time>( <?php echo date('Y-m-d',$output['mansong_info']['start_time']).'--'.date('Y-m-d',$output['mansong_info']['end_time']);?> )</time>
    </div>
    <ul class="mjs-info">
      <?php foreach($output['mansong_info']['rules'] as $value) { ?>
      <li> <span class="sale-rule">单笔订单消费满<em><?php echo $lang['currency'].ncPriceFormat($value['price']);?></em>
        <?php if(!empty($value['discount'])) { ?>
        ， 立减现金<em><?php echo $lang['currency'].ncPriceFormat($value['discount']);?></em>
        <?php } ?>
        <?php if(!empty($value['goods_id'])) { ?>
        ， 还可获赠品 <a href="<?php echo $value['goods_url'];?>" title="<?php echo $value['mansong_goods_name'];?>" target="_blank"> <img src="<?php echo cthumb($value['goods_image'], 60);?>" alt="<?php echo $value['mansong_goods_name'];?>"> </a>&nbsp;。
        <?php } ?>
        </span> </li>
      <?php } ?>
    </ul>
    <div class="mjs-remark"><?php echo $output['mansong_info']['remark'];?></div>
  </div>
  <div class="ncs-main-container">
    <div class="title">
      <h4>全部活动商品</h4>
    </div>
    <?php if(!empty($output['recommended_goods_list']) && is_array($output['recommended_goods_list'])){?>
    <div class="content ncs-all-goods-list mb15">
      <ul>
        <?php foreach($output['recommended_goods_list'] as $value){?>
        <li>
          <dl>
            <dt><a href="<?php echo urlShop('goods', 'index',array('goods_id'=>$value['goods_id']));?>" class="goods-thumb" target="_blank"><img src="<?php echo thumb($value, 240);?>" alt="<?php echo $value['goods_name'];?>" /></a>
              <ul class="goods-thumb-scroll-show">
                <?php if (is_array($value['image'])) { array_splice($value['image'], 5);?>
                <?php $i=0;foreach ($value['image'] as $val) {$i++?>
                <li<?php if($i==1) {?> class="selected"<?php }?>><a href="javascript:void(0);"><img src="<?php echo thumb($val, 60);?>"/></a></li>
                <?php }?>
                <?php } else {?>
                <li class="selected"><a href="javascript:void(0)"><img src="<?php echo thumb($value, 60);?>"></a></li>
                <?php }?>
              </ul>
            </dt>
            <dd class="goods-name"><a href="<?php echo urlShop('goods', 'index', array('goods_id'=>$value['goods_id']));?>" title="<?php echo $value['goods_name'];?>" target="_blank"><?php echo $value['goods_name']?></a></dd>
            <dd class="goods-info"><span class="price"><?php echo $lang['currency'];?> <?php echo ncPriceFormat($value['goods_promotion_price']);?> </span><span class="goods-sold"><?php echo $lang['nc_sell_out'];?><strong><?php echo $value['goods_salenum'];?></strong> <?php echo $lang['nc_jian'];?></span></dd>
            <?php if (C('groupbuy_allow') && $value['goods_promotion_type'] == 1) {?>
            <dd class="goods-promotion"><span>抢购商品</span></dd>
            <?php } elseif (C('promotion_allow') && $value['goods_promotion_type'] == 2)  {?>
            <dd class="goods-promotion"><span>限时折扣</span></dd>
            <?php }?>
          </dl>
        </li>
        <?php }?>
      </ul>
    </div>
    <div class="pagination"><?php echo $output['show_page']; ?></div>
    <?php }else{?>
    <div class="content ncs-all-goods-list">
      <div class="nothing">
        <p><?php echo $lang['show_store_index_no_record'];?></p>
      </div>
    </div>
    <?php }?>
  </div>
</div>
<script>
$(function(){
    // 图片切换效果
    $('.goods-thumb-scroll-show').find('a').mouseover(function(){
        $(this).parents('li:first').addClass('selected').siblings().removeClass('selected');
        var _src = $(this).find('img').attr('src');
        _src = _src.replace('_60.', '_240.');
        _src = _src.replace('-60', '-240');
        $(this).parents('dt').find('.goods-thumb').find('img').attr('src', _src);
    });
});
</script>