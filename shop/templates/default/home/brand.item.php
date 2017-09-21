<?php defined('In33hao') or exit('Access Invalid!');?>
<div id="box">
<?php foreach($output['brand_c'] as $key=>$brand_c){?>
<?php if ($brand_c['image']){ $i=0;?>
<?php foreach($brand_c['image'] as $key=>$brand){$i++?>
<div class="brandWrap clearfix"> <a class="brandDesc f-fl" href="<?php echo urlShop('brand', 'list', array('brand'=>$brand['brand_id']));?>" target="_blank" style="top: 0px;"> <img class="brandLogo" data-url="<?php echo brandImage($brand['brand_pic']);?>"  rel='lazy' src="<?php echo SHOP_SITE_URL;?>/img/loading.gif"  title="<?php echo $brand['brand_name'];?>" alt="<?php echo $brand['brand_name'];?>">
  <p class="brandName" title="<?php echo $brand['brand_name'];?>"><?php echo $brand['brand_name'];?></p>
  <!--<span class="brandBtn">进入品牌</span>--></a></div>
<?php } }?>
<?php if ($brand_c['text']){?>
<div class="barnd-list-text"><strong>更多品牌：</strong>
  <?php foreach($brand_c['text'] as $key=>$brand){ ?>
  <a href="<?php echo urlShop('brand', 'list', array('brand'=>$brand['brand_id']));?>"><?php echo $brand['brand_name'];?></a>
  <?php } ?>
</div>
<?php } }?>
</div>
