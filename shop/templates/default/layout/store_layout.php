<?php defined('In33hao') or exit('Access Invalid!');?>
<?php include template('layout/store_common_layout');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/shop.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_TEMPLATES_URL?>/css/shop_custom.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_TEMPLATES_URL;?>/store/style/<?php echo $output['store_theme'];?>/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/member.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/shop.js" charset="utf-8"></script>
<div id="header" class="ncs-header">
  
</div>
    <div id="store_decoration_content" class="background" style="<?php echo $output['decoration_background_style'];?>">
<?php if(!empty($output['decoration_nav'])) {?>
<style><?php echo $output['decoration_nav']['style'];?></style>
<?php } ?>
  <div class="ncsl-nav">
     <?php if(isset($output['decoration_banner'])) { ?>
     <!-- 启用店铺装修 -->
     <?php if($output['decoration_banner']['display'] == 'true') { ?>
     <div id="decoration_banner" class="ncsl-nav-banner">
         <img src="<?php echo $output['decoration_banner']['image_url'];?>" alt="">
     </div>
     <?php } ?>
      <?php } else { ?>
     <!-- 不启用店铺装修 -->
<div class="banner"><a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$output['store_info']['store_id']));?>" class="img">
      <?php if(!empty($output['store_info']['store_banner'])){?>
      <img src="<?php echo getStoreLogo($output['store_info']['store_banner'],'store_logo');?>" alt="<?php echo $output['store_info']['store_name']; ?>" title="<?php echo $output['store_info']['store_name']; ?>" class="pngFix">
      <?php }else{?>
      <div class="ncs-default-banner"></div>
      <?php }?>
      </a></div>
    <?php } ?>
    <?php if(empty($output['decoration_nav']) || $output['decoration_nav']['display'] == 'true') {?>
    <div id="nav" class="ncs-nav">
      <ul>
        <li id="store_nav_class_button" class="normal ncs-nav-classes"> 
          <!-- 店铺商品分类 --> 
          <a href="javascript:;"><span>查看所有分类<i></i></span></a>
          <ul id="store_nav_class_menu" class="classes-menu" style="display:none;">
            <?php if(!empty($output['goods_class_list']) && is_array($output['goods_class_list'])){?>
            <?php foreach($output['goods_class_list'] as $value){?>
            <li class="store-nav-class-sub-menu"><a href="<?php echo urlShop('show_store', 'goods_all', array('store_id' => $output['store_info']['store_id'], 'stc_id' => $value['stc_id']));?>" title="<?php echo $value['stc_name'];?>"><i></i><?php echo $value['stc_name'];?></a>
              <?php if(!empty($value['children']) && is_array($value['children'])){?>
              <ul class="store-nav-class-menu-item" style="display:none;">
                <?php foreach($value['children'] as $value1){?>
                <li><a href="<?php echo urlShop('show_store', 'goods_all', array('store_id' => $output['store_info']['store_id'], 'stc_id' => $value1['stc_id']));?>" title="<?php echo $value1['stc_name'];?>"><i></i><?php echo $value1['stc_name'];?></a></li>
                <?php }?>
              </ul>
              <?php }?>
            </li>
            <?php }?>
            <?php }?>
          </ul>
        </li>
        <li class="<?php if($output['page'] == 'index'){?>active<?php }else{?>normal<?php }?>"><a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$output['store_info']['store_id']));?>"><span><?php echo $lang['nc_store_index'];?><i></i></span></a></li>
        <li class="<?php if ($output['page'] == 'store_sns') {?>active<?php }else{?>normal<?php }?>"><a href="<?php echo urlShop('store_snshome', 'index', array('sid' => $output['store_info']['store_id']))?>"><span>店铺动态<i></i></span></a></li>
        <?php if(!empty($output['store_navigation_list'])){
      		foreach($output['store_navigation_list'] as $value){
                if($value['sn_if_show']) {
      			if($value['sn_url'] != ''){?>
        <li class="<?php if($output['page'] == $value['sn_id']){?>active<?php }else{?>normal<?php }?>"><a href="<?php echo $value['sn_url']; ?>" <?php if($value['sn_new_open']){?>target="_blank"<?php }?>><span><?php echo $value['sn_title'];?><i></i></span></a></li>
        <?php }else{ ?>
        <li class="<?php if($output['page'] == $value['sn_id']){?>active<?php }else{?>normal<?php }?>"><a href="<?php echo urlShop('show_store', 'show_article', array('store_id' => $output['store_info']['store_id'], 'sn_id' => $value['sn_id']));?>"><span><?php echo $value['sn_title'];?><i></i></span></a></li>
        <?php }}}} ?>
      </ul>
    </div>
    <?php } ?>
  </div>
  <?php require_once($tpl_file); ?>
  <div class="clear">&nbsp;</div>
</div>
<?php include template('footer');?>
<script type="text/javascript">
$(function(){
	$('a[nctype="search_in_store"]').click(function(){
		if ($('#keyword').val() == '') {
			return false;
		}
		$('#search_act').val('show_store');
		$('<input type="hidden" value="<?php echo $output['store_info']['store_id'];?>" name="store_id" /> <input type="hidden" name="op" value="goods_all" />').appendTo("#formSearch");
		$('#formSearch').submit();
	});
	$('a[nctype="search_in_shop"]').click(function(){
		if ($('#keyword').val() == '') {
			return false;
		}
		$('#formSearch').submit();
	});
	$('#keyword').css("color","#999999");

	var storeTrends	= true;
	$('.favorites').mouseover(function(){
		var $this = $(this);
		if(storeTrends){
			$.getJSON('index.php?act=show_store&op=ajax_store_trend_count&store_id=<?php echo $output['store_info']['store_id'];?>', function(data){
				$this.find('li:eq(2)').find('a').html(data.count);
				storeTrends = false;
			});
		}
	});

	$('a[nctype="share_store"]').click(function(){
		<?php if ($_SESSION['is_login'] !== '1'){?>
		login_dialog();
		<?php } else {?>
		ajax_form('sharestore', '分享店铺', 'index.php?act=member_snsindex&op=sharestore_one&inajax=1&sid=<?php echo $output['store_info']['store_id'];?>');
		<?php }?>
	});

    //店铺商品分类
    $('#store_nav_class_button').on('mouseover', function() {
        $('#store_nav_class_menu').show();
    });
    $('#store_nav_class_button').on('mouseout', function() {
        $('#store_nav_class_menu').hide();
    });
    $('.store-nav-class-sub-menu').on('mouseover', function() {
        $('.store-nav-class-menu-item').hide();
        $(this).children('.store-nav-class-menu-item').show();
    });
    $('.store-nav-class-sub-menu').on('mouseout', function() {
        $('.store-nav-class-menu-item').hide();
    });
});
</script>
</body></html>