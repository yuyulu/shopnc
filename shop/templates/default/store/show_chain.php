<?php defined('In33hao') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_goods.css" rel="stylesheet" type="text/css">
<div class="ncs-chain-detail">
  <div class="chain-img"><img src="<?php echo getChainImage($output['chain_info']['chain_img'], $output['chain_info']['store_id']);?>"></div>
  <div class="chain-info">
    <div class="chain-name">
      <h1><?php echo $output['chain_info']['chain_name'];?></h1>
      <a href="javascript:;" onclick="show_map();"><i></i>查看地图</a></div>
    <dl>
      <dt>门店地址：</dt>
      <dd><?php echo $output['chain_info']['area_info'].' '.$output['chain_info']['chain_address'];?></dd>
    </dl>
    <dl>
      <dt>联系电话：</dt>
      <dd><?php echo $output['chain_info']['chain_phone'];?></dd>
    </dl>
    <dl>
      <dt>营业时间：</dt>
      <dd><?php echo nl2br($output['chain_info']['chain_opening_hours']);?></dd>
    </dl>
    <?php if ($output['chain_info']['chain_traffic_line'] != '') {?>
    <dl>
      <dt>交通线路：</dt>
      <dd><?php echo nl2br($output['chain_info']['chain_traffic_line']);?></dd>
    </dl>
    <?php }?>
    <div class="delivery-map"></div>
  </div>
  
</div>

<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.thumb.min.js"></script> 
<script>
$(function(){	
	$('.chain-img img').jqthumb({
		width: 350,
		height: 350,
		after: function(imgObj){
			imgObj.css('opacity', 0).attr('title',$(this).attr('alt')).animate({opacity: 1}, 2000);
		}
	});
});
function show_map() {
	$('.delivery-map').html('<img width="740" height="320" src="http://api.map.baidu.com/staticimage?center=&width=740&height=320&zoom=18&markers=<?php echo str_replace(' ', '', $output['chain_info']['area_info'].$output['chain_info']['chain_address']);?>">');
}
</script>