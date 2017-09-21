<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <div class="ncm-favorite-store-more">
    <?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){?>
    <div class="store-list">
      <div class="store-info current">
        <div class="store-pic"><img src="<?php echo getStoreLogo($output['store_info']['store_avatar']);?>"/></div>
        <span class="store-name"><?php echo $output['store_info']['store_name'];?></span> </div>
      <?php foreach($output['favorites_list'] as $key=>$favorites){?>
      <?php if (!isset($favorites['store'])) {continue;}?>
      <div class="store-info"><a href="<?php echo urlShop('member_favorite_store', 'more', array('store_id' => $favorites['store']['store_id']));?>"> <span class="store-pic"><img src="<?php echo getStoreLogo($favorites['store']['store_avatar']);?>"/></span> <span class="store-name"><?php echo $favorites['store']['store_name'];?></span></a> </div>
      <?php }?>
    </div>
    <div class="item-list">
      <div class="store-info">
        <div class="store-pic"><img src="<?php echo getStoreLogo($output['store_info']['store_avatar']);?>"/></div>
        <dl>
          <dt><a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$output['store_info']['store_id']), $output['store_info']['store_domain'])?>" ><?php echo $output['store_info']['store_name'];?></a></dt>
          <dd><?php echo $lang['favorite_message'].$lang['nc_colon'];?><span member_id="<?php echo $output['store_info']['member_id'];?>"></span>
            <?php if(!empty($output['store_info']['store_qq'])){?>
            <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $output['store_info']['store_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $output['store_info']['store_qq'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $output['store_info']['store_qq'];?>:52" style=" vertical-align: middle;"/></a>
            <?php }?>
            <?php if(!empty($favorites['store']['store_ww'])){?>
            <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo $output['store_info']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" ><img border="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $output['store_info']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="Wang Wang"  style=" vertical-align: middle;"/></a>
            <?php }?>
          </dd>
          <dd>所在地：<?php echo $output['store_info']['area_info'];?></dd>
        </dl>
      </div>
      <div class="show-tab" data-sid="<?php echo $output['store_info']['store_id'];?>">
        <a href="<?php if ($output['count']['new'] == 0) {?>javascript:;<?php } else {echo urlShop('member_favorite_store', 'more', array('store_id' => $output['store_info']['store_id'], 'sign' => 'new')) ;}?>" class="<?php if ($_GET['sign'] == 'new' || empty($_GET['sign'])) {?>current<?php }?> <?php if ($output['count']['new'] == 0) {?>disabled<?php }?>">店铺新品</a>
        <a href="<?php if ($output['count']['promotion'] == 0) {?>javascript:;<?php } else { echo urlShop('member_favorite_store', 'more', array('store_id' => $output['store_info']['store_id'], 'sign' => 'promotion')) ;}?>" class="<?php if ($_GET['sign'] == 'promotion') {?>current<?php }?> <?php if ($output['count']['promotion'] == 0) {?>disabled<?php }?>">优惠促销</a>
        <a href="<?php if ($output['count']['hot'] == 0) {?>javascript:;<?php } else { echo urlShop('member_favorite_store', 'more', array('store_id' => $output['store_info']['store_id'], 'sign' => 'hot')) ;}?>" class="<?php if ($_GET['sign'] == 'hot') {?>current<?php }?> <?php if ($output['count']['hot'] == 0) {?>disabled<?php }?>">热销商品</a>
       </div>
      <ul id="favoritesMoreItem" class="<?php echo $_GET['sign'];?>">
        <?php require(BASE_TPL_PATH.'/member/favorites_store_index.more_item.php');?>
      </ul>
      <div class="tc mt20 mb20">
        <div class="pagination" id="page-nav"></div>
      </div>
      <?php }?>
    </div>
    <div id="page-more"><a href="<?php echo urlShop('member_favorite_store', 'more', array('store_id' => $output['store_info']['store_id'], 'sign' => $_GET['sign']));?>&curpage=2"></a> </div>
  </div>
</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.infinitescroll.js" type="text/javascript"></script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/sns.js"></script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.thumb.min.js"></script> 
<script>
$(function(){
	$('#favoritesMoreItem').infinitescroll({  
        navSelector : '#page-more',
        nextSelector : '#page-more a',
        itemSelector : '.favorites-store-items',
        loading: {
        	selector:'#page-nav',
            img: '<?php echo SHOP_TEMPLATES_URL;?>/images/loading.gif',
            msgText:'努力加载中...',
            maxPage : <?php echo $output['total_page'];?>,
            finishedMsg : '没有记录了',
            finished : function() {
            	$('.goods-thumb img').jqthumb({
            		width: 150,
            		height: 150,
            		after: function(imgObj){
            			imgObj.css('opacity', 0).attr('title',$(this).attr('alt')).animate({opacity: 1}, 2000);
            		}
            	});
            }
        }
    });

	$('.goods-thumb img').jqthumb({
		width: 150,
		height: 150,
		after: function(imgObj){
			imgObj.css('opacity', 0).attr('title',$(this).attr('alt')).animate({opacity: 1}, 2000);
		}
	});
});
</script> 
