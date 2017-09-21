<?php defined('In33hao') or exit('Access Invalid!');?>

  <?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){ $i = 0; ?>
  <div class="favorite-goods-list">
    <ul>
      <?php foreach($output['favorites_list'] as $key=>$favorites){$i++;?>
      <li class="favorite-pic-list<?php if (!isset($favorites['goods']) || !$favorites['goods']['state']) {?> disable<?php }?>" >
        <div class="favorite-goods-thumb"><a href="index.php?act=goods&goods_id=<?php echo $favorites['goods']['goods_id'];?>" target="_blank" title="<?php echo $favorites['goods']['goods_name'];?>"><img src="<?php echo thumb($favorites['goods'], 240);?>" /></a></div>
        <div class="handle"> <a href="javascript:void(0)" onclick="ajax_get_confirm('<?php echo $lang['nc_ensure_del'];?>', 'index.php?act=member_favorite_goods&op=delfavorites&type=goods&fav_id=<?php echo $favorites['fav_id'];?>');" class="fr ml5" title="<?php echo $lang['nc_del'];?>"><i class="icon-trash"></i></a>
          <?php if (!isset($favorites['goods']) || !$favorites['goods']['state']) {?>
          <a href="<?php echo urlShop('show_store','index',array('store_id'=> $favorites['goods']['store_id']), $favorites['goods']['store_domain']);?>" class="fr" title="逛逛店铺" target="_blank" ><i class="icon-home"></i></a>
          <?php } else if($favorites['goods']['is_virtual'] == 1 || $favorites['goods']['is_fcode'] == 1 || $favorites['goods']['is_presell'] == 1 || $favorites['goods']['is_book'] == 1) {?>
          <a href="javascript:void(0)" class="fr" title="立即购买" nctype="buy_now" data-gid="<?php echo $favorites['goods']['goods_id'];?>"><i class="icon-shopping-cart"></i></a>
          <?php } else {?>
          <a href="javascript:void(0)" class="fr" title="加入购物车" nctype="add_cart" data-gid="<?php echo $favorites['goods']['goods_id'];?>"><i class="icon-shopping-cart"></i></a>
          <?php }?>
          <a href="javascript:void(0)"  nc_type="sharegoods" data-param='{"gid":"<?php echo $favorites['goods']['goods_id'];?>"}' class="fl w40" title="<?php echo $lang['favorite_snsshare_goods'];?>"><i class="icon-share"></i><?php echo $lang['favorite_snsshare_goods'];?></a> </div>
        <dl class="favorite-goods-info">
          <dt><a href="index.php?act=goods&goods_id=<?php echo $favorites['goods']['goods_id'];?>" target="_blank" title="<?php echo $favorites['goods']['goods_name'];?>"><?php echo $favorites['goods']['goods_name'];?></a></dt>
          <?php if (!isset($favorites['goods']) || !$favorites['goods']['state']) {?>
          <dd class="lose"><em></em>商品已失效</dd>
          <?php } else {?>
          <dd class="goods-price">
            <?php if ($favorites['goods']['goods_promotion_price'] < $favorites['log_price']) {?>
            <em class="down"></em>&yen;<strong><?php echo ncPriceFormat($favorites['goods']['goods_promotion_price']);?></strong><span>&yen;<?php echo ncPriceFormat($favorites['log_price']);?></span>
            <?php } else {?>
            &yen;<strong><?php echo ncPriceFormat($favorites['goods']['goods_promotion_price']);?></strong>
            <?php }?>
          </dd>
          <?php if (isset($output['voucher_template'][$favorites['store_id']])) {?>
          <dd class="voucher <?php if (is_int($i/6)) { ?>r<?php }?>"><span class="pic"></span>
            <div class="voucher-box"><i></i>
              <?php foreach ($output['voucher_template'][$favorites['store_id']] as $voucher) {?>
              <div class="voucher-list"><span class="par">&yen;<?php echo $voucher['voucher_t_price'];?></span> <span class="rule">
                <p>全店通用，满<?php echo ncPriceFormat($voucher['voucher_t_limit']);?>元可折扣<?php echo $voucher['voucher_t_price'];?>元</p>
                <time>限<?php echo date('Y-m-d', $voucher['voucher_t_end_date']);?>前使用</time>
                </span><a href="javascript:;" data-tid='<?php echo $voucher['voucher_t_id'];?>'>领取</a> </div>
              <?php }?>
              <p>我领到的店铺代金券都在这里，<a href="<?php echo urlMember('member_voucher', 'voucher_list');?>" target="_blank">去看看</a>。</p>
            </div>
          </dd>
          <?php }?>
          <?php }?>
        </dl>
      </li>
      <?php }?>
    </ul>
  </div>
  <?php }?>
