<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){?>
  <?php foreach($output['favorites_list'] as $key=>$favorites){?>
  <div class="ncm-favorite-store">
    <div class="store-info">
      <div class="store-pic"><img src="<?php echo getStoreLogo($favorites['store']['store_avatar']);?>"/></div>
      <dl>
        <dt><a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$favorites['store']['store_id']), $favorites['store']['store_domain'])?>" ><?php echo $favorites['store']['store_name'];?></a></dt>
        <dd><?php echo $lang['favorite_message'].$lang['nc_colon'];?><span member_id="<?php echo $favorites['store']['member_id'];?>"></span>
          <?php if(!empty($favorites['store']['store_qq'])){?>
          <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $favorites['store']['store_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $favorites['store']['store_qq'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $favorites['store']['store_qq'];?>:52" style=" vertical-align: middle;"/></a>
          <?php }?>
          <?php if(!empty($favorites['store']['store_ww'])){?>
          <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo $favorites['store']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" ><img border="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $favorites['store']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="Wang Wang"  style=" vertical-align: middle;"/></a>
          <?php }?>
        </dd>
        <dd>所在地：<?php echo $favorites['store']['area_info'];?></dd>
      </dl>
      <div class="handle"><a href="javascript:void(0);" nc_type="sharestore" data-param='{"sid":"<?php echo $favorites['store']['store_id'];?>"}' title="<?php echo $lang['nc_snsshare'];?>"><i class="icon-share-alt"></i></a><a href="javascript:void(0)" onclick="ajax_get_confirm('<?php echo $lang['nc_ensure_del'];?>', 'index.php?act=member_favorite_store&op=delfavorites&type=store&fav_id=<?php echo $favorites['fav_id'];?>');" class="ml10" title="<?php echo $lang['nc_del'];?>"><i class="icon-trash"></i></a></div>
    </div>
    <div class="store-goods"><a href="<?php echo urlShop('member_favorite_store', 'more', array('store_id' => $favorites['store']['store_id']));?>" class="more">查看更多></a>
      <div class="show-tab" data-sid="<?php echo $favorites['store']['store_id'];?>"> 
        <a href="javascript:void(0)" class="<?php if ($favorites['goods']['sign'] == 'new') {?>current<?php }?> <?php if (empty($favorites['goods']['new'])) {?>disabled<?php }?>">本周上新</a>
        <a href="javascript:void(0)" class="<?php if ($favorites['goods']['sign'] == 'promotion') {?>current<?php }?> <?php if (empty($favorites['goods']['promotion'])) {?>disabled<?php }?>">优惠促销</a>
        <a href="javascript:void(0)" class="<?php if ($favorites['goods']['sign'] == 'hot') {?>current<?php }?> <?php if (empty($favorites['goods']['hot'])) {?>disabled<?php }?>">热销商品</a>
      </div>
      <div class="show-list" <?php if ($favorites['goods']['sign'] != 'new') {?>style="display:none;"<?php }?>>
      <?php if (!empty($favorites['goods']['new'])) {?>
        <ul>
         <?php foreach ($favorites['goods']['new'] as $goods) {?>
          <li>
            <div class="goods-thumb"><a href="index.php?act=goods&goods_id=<?php echo $goods['goods_id'];?>" title="<?php echo $goods['goods_name'];?>" target="_blank"><img src="<?php echo thumb($goods, 240);?>" /></a></div>
            <p>￥<?php echo $goods['goods_promotion_price'];?></p>
          </li>
          <?php }?>
        </ul>
      <?php }?>
      </div>
      <div class="show-list" <?php if ($favorites['goods']['sign'] != 'promotion') {?>style="display:none;"<?php }?>>
      <?php if (!empty($favorites['goods']['promotion'])) {?>
        <ul>
         <?php foreach ($favorites['goods']['promotion'] as $goods) {?>
          <li>
            <div class="goods-thumb"><a href="index.php?act=goods&goods_id=<?php echo $goods['goods_id'];?>" title="<?php echo $goods['goods_name'];?>" target="_blank"><img src="<?php echo thumb($goods, 240);?>" /></a></div>
            <p>￥<?php echo $goods['goods_promotion_price'];?></p>
          </li>
          <?php }?>
        </ul>
      <?php }?>
      </div>
      <div class="show-list" <?php if ($favorites['goods']['sign'] != 'hot') {?>style="display:none;"<?php }?>>
      <?php if (!empty($favorites['goods']['hot'])) {?>
        <ul>
         <?php foreach ($favorites['goods']['hot'] as $goods) {?>
          <li>
            <div class="goods-thumb"><a href="index.php?act=goods&goods_id=<?php echo $goods['goods_id'];?>" title="<?php echo $goods['goods_name'];?>" target="_blank"><img src="<?php echo thumb($goods, 240);?>" /></a></div>
            <p>￥<?php echo $goods['goods_promotion_price'];?></p>
          </li>
          <?php }?>
        </ul>
      <?php }?>
      </div>
    </div>
  </div>
  <?php }?>
  <?php }else{?>
  <div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div>
  <?php }?>
  <?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){?>
  <div class="pagination"><?php echo $output['show_page']; ?></div>
  <?php }?>
</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/sns.js"></script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.thumb.min.js"></script> 
<script>
$(function(){	
	$('.goods-thumb img').jqthumb({
		width: 120,
		height: 120,
		after: function(imgObj){
			imgObj.css('opacity', 0).attr('title',$(this).attr('alt')).animate({opacity: 1}, 2000);
		}
	});

	$('.show-tab a').click(function(){
		var _parents = $(this).parents('.show-tab');
		if ($(this).hasClass('disabled')) {
			return false;
		}
	    var _index = $(this).index();
	    _parents.find('a').removeClass('current');
	    $(this).addClass('current');
	    _parents.nextAll('.show-list').hide().eq(_index).show();

	    var _href = '<?php echo urlShop('member_favorite_store', 'more');?>';
	    var _store_id = _parents.attr('data-sid');
	    _href = _href + '&store_id=' + _store_id;
	    switch (_index) {
    	    case 0:
    	        _href = _href + '&sign=new';
    		    break;
    	    case 1:
        	    _href = _href + '&sign=promotion';
        	    break;
    	    case 2:
    	        _href = _href + '&sign=hot';
    		    break;
	    }
	    $(this).parents('.store-goods:first').find('.more').attr('href', _href);
	});
});
</script> 
