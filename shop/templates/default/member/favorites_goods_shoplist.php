<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <?php if(!empty($output['store_goods_list']) && is_array($output['store_goods_list'])){ ?>
  <?php foreach($output['store_goods_list'] as $k=>$goods_list){?>
  <div class="ncm-favorite-goods-store">
    <div class="store-info">
      <div class="store-pic"><img src="<?php echo getStoreLogo($goods_list[0]['goods']['store_avatar']);?>"/></div>
      <dl>
        <dt><a href="<?php echo urlShop('show_store','index',array('store_id'=> $goods_list[0]['goods']['store_id']), $favorites['goods']['store_domain']);?>" ><?php echo $goods_list[0]['goods']['store_name'];?></a></dt>
        <dd>联系：<i member_id="<?php echo $goods_list[0]['goods']['member_id'];?>"></i>
          <?php if(!empty($goods_list[0]['goods']['store_qq'])){?>
          <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $goods_list[0]['goods']['store_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $goods_list[0]['goods']['store_qq'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $goods_list[0]['goods']['store_qq'];?>:52" style=" vertical-align: middle;"/></a>
          <?php }?>
          <?php if(!empty($goods_list[0]['goods']['store_ww'])){?>
          <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo $goods_list[0]['goods']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" ><img border="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $goods_list[0]['goods']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="Wang Wang"  style=" vertical-align: middle;"/></a>
          <?php }?>
        </dd>
        <dd>
          <?php if(!empty($output['store_favorites']) && in_array($goods_list[0]['goods']['store_id'],$output['store_favorites'])){ ?>
          <i class="icon-heart green"></i>店铺已收藏
          <?php }else{?>
          <a href="javascript:collect_store('<?php echo $goods_list[0]['goods']['store_id'];?>','store','')" title="<?php echo $lang['favorite_collect_store'];?>" nc_store="<?php echo $goods_list[0]['goods']['store_id'];?>"><i class="icon-heart-empty"></i>收藏该店铺</a>
          <?php }?>
        </dd>
        <?php if (isset($output['voucher_template'][$goods_list[0]['goods']['store_id']])) {?>
        <dd class="voucher"><span class="pic"></span>
          <div class="voucher-box"><i></i>
            <?php foreach ($output['voucher_template'][$goods_list[0]['goods']['store_id']] as $voucher) {?>
            <div class="voucher-list"><span class="par">&yen;<?php echo $voucher['voucher_t_price'];?></span> <span class="rule">
              <p>全店通用，满<?php echo ncPriceFormat($voucher['voucher_t_limit']);?>元可折扣<?php echo $voucher['voucher_t_price'];?>元</p>
              <time>限<?php echo date('Y-m-d', $voucher['voucher_t_end_date']);?>前使用</time>
              </span><a href="javascript:;" data-tid='<?php echo $voucher['voucher_t_id'];?>'>领取</a> </div>
            <?php }?>
            <p class="tc">我领到的店铺代金券都在这里，<a href="javascript:void(0)" target="_blank">去看看</a>。</p>
          </div>
        </dd>
        <?php }?>
      </dl>
    </div>
    <div class="favorite-goods-list">
      <ul>
        <?php foreach($goods_list as $key=>$favorites){?>
        <li class="favorite-pic-list<?php if (!isset($favorites['goods']) || !$favorites['goods']['state']) {?> disable<?php }?>">
          <div class="favorite-goods-thumb"><a href="index.php?act=goods&goods_id=<?php echo $favorites['goods']['goods_id'];?>" title="<?php echo $favorites['goods']['goods_name'];?>" target="_blank"><img src="<?php echo thumb($favorites['goods'], 240);?>" /></a></div>
          <div class="handle">
            <a href="javascript:void(0)" class="fr ml5" onclick="ajax_get_confirm('<?php echo $lang['nc_ensure_del'];?>', 'index.php?act=member_favorite_goods&op=delfavorites&type=goods&fav_id=<?php echo $favorites['fav_id'];?>');" title="<?php echo $lang['nc_del_&nbsp'];?>"><i class="icon-trash"></i></a>
            <?php if (!isset($favorites['goods']) || !$favorites['goods']['state']) {?>
            <a href="<?php echo urlShop('show_store','index',array('store_id'=> $favorites['goods']['store_id']), $favorites['goods']['store_domain']);?>" class="fr" title="逛逛店铺" target="_blank" ><i class="icon-home"></i></a>
            <?php } else if($favorites['goods']['is_virtual'] == 1 || $favorites['goods']['is_fcode'] == 1 || $favorites['goods']['is_presell'] == 1 || $favorites['goods']['is_book'] == 1) {?>
            <a href="javascript:void(0)" class="fr" title="立即购买" nctype="buy_now" data-gid="<?php echo $favorites['goods']['goods_id'];?>"><i class="icon-shopping-cart"></i></a>
            <?php } else {?>
            <a href="javascript:void(0)" class="fr" title="加入购物车" nctype="add_cart" data-gid="<?php echo $favorites['goods']['goods_id'];?>"><i class="icon-shopping-cart"></i></a>
            <?php }?>
            <a href="javascript:void(0)" class="fl" nc_type="sharegoods" data-param='{"gid":"<?php echo $favorites['goods']['goods_id'];?>"}' title="<?php echo $lang['favorite_snsshare_goods'];?>"><i class="icon-share"></i> <?php echo $lang['favorite_snsshare_goods'];?> </a>
          </div>
          <dl class="favorite-goods-info">
            <dt><a href="index.php?act=goods&goods_id=<?php echo $favorites['goods']['goods_id'];?>" title="<?php echo $favorites['goods']['goods_name'];?>" target="_blank"><?php echo $favorites['goods']['goods_name'];?></a></dt>
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
            <?php }?>
          </dl>
        </li>
        <?php }?>
      </ul>
    </div>
  </div>
  <?php }?>
  <?php }else{?>
  <div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div>
  <?php }?>
  <?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){?>
  <div class="pagination"><?php echo $output['show_page']; ?></div>
  <?php }?>
  <!-- 猜你喜欢 -->
  <div id="guesslike_div" style="width:980px;"></div>
</div>
<form id="buynow_form" method="post" action="<?php echo SHOP_SITE_URL;?>/index.php" target="_blank">
  <input id="act" name="act" type="hidden" value="buy" />
  <input id="op" name="op" type="hidden" value="buy_step1" />
  <input id="goods_id" name="cart_id[]" type="hidden"/>
</form>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.thumb.min.js"></script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/sns.js"></script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/fly/jquery.fly.min.js"></script> 
<!--[if lt IE 10]>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fly/requestAnimationFrame.js" charset="utf-8"></script>
<![endif]-->
<script>
$(function(){	
	$('.favorite-goods-thumb img').jqthumb({
		width: 150,
		height: 150,
		after: function(imgObj){
			imgObj.css('opacity', 0).attr('title',$(this).attr('alt')).animate({opacity: 1}, 2000);
		}
	});
	//猜你喜欢
	$('#guesslike_div').load('<?php echo urlShop('search', 'get_guesslike', array()); ?>', function(){
        $(this).show();
    });
    $(".favorite-pic-list").on('click', 'a[data-tid]', function(){
        var _tid = $(this).attr('data-tid');
        ajaxget('index.php?act=voucher&op=getvouchersave&jump=0&tid=' + _tid);
    });

    // 加入购物车
    $(window).resize(function() {
        $('.favorite-pic-list').on('click','a[nctype="add_cart"]', function() {
            flyToCart($(this));
        });
    });
    $('.favorite-pic-list').on('click','a[nctype="add_cart"]', function() {
        flyToCart($(this));
    });
     function flyToCart($this) {
         var rtoolbar_offset_left = $("#rtoolbar_cart").offset().left;
         var rtoolbar_offset_top = $("#rtoolbar_cart").offset().top-$(document).scrollTop();
        var img = $this.parents('li').find('img').attr('src');
        var data_gid = $this.attr('data-gid');
        var flyer = $('<img class="u-flyer" src="'+img+'" style="z-index:999">');
        flyer.fly({
            start: {
                left: $this.offset().left,
                top: $this.offset().top-$(document).scrollTop()
            },
            end: {
                left: rtoolbar_offset_left,
                top: rtoolbar_offset_top,
                width: 0,
                height: 0
            },
            onEnd: function(){
                addcart(data_gid,1,'');
                flyer.remove();
            }
        });
     }

    // 立即购买
    $('a[nctype="buy_now"]').click(function(){
        var data_gid = $(this).attr('data-gid');
        $("#goods_id").val(data_gid+'|1');
        $("#buynow_form").submit();
    });
});
</script> 
