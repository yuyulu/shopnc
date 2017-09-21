<?php defined('In33hao') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/snapshot.css" rel="stylesheet" type="text/css">

<div class="wrapper">
  <div class="snapshot-goods-name"><em>商品SKU：<?php echo $output['goods']['goods_id'];?></em>
    <h1><?php echo $output['goods']['goods_name']; ?><span>交易快照</span></h1>
  </div>
  <div class="ncs-detail<?php if ($output['store_info']['is_own_shop']) echo ' ownshop'; ?>">
    <div id="ncs-goods-picture" class="ncs-goods-picture"><img alt="" src="<?php echo thumb($output['goods'], 360);?>"></div>
    <div class="ncs-goods-summary">
      <dl class="ncs-price">
        <dt>成 交 价：</dt>
        <dd><em><?php echo ncPriceFormat($output['goods']['goods_price']);?></em>元</dd>
      </dl>
      <?php if ($output['goods']['goods_type_cn'] != '') {?>
      <dl class="ncs-sale">
        <dt>促销：</dt>
        <dd><span class="sale-name"><?php echo $output['goods']['goods_type_cn'];?></span> </dd>
      </dl>
      <?php }?>
      <?php if (!empty($output['goods']['goods_spec'])) { ?>
      <dl class="ncs-spec">
        <?php foreach ($output['goods']['goods_spec'] as $key => $val) {?>
        <dt><?php echo $key;?><?php echo $lang['nc_colon'];?></dt>
        <dd><?php echo $val;?> </dd>
        <?php }?>
      </dl>
      <?php }?>
      <div class="snap">
        <p>您正在查看订单编号：<strong><?php echo encryptShow($output['order_info']['order_sn'],4,11);?></strong> 的交易快照</p>
        <p>该交易快照生成时间：<?php echo date('Y-m-d H:i:s', $output['goods']['create_time'])?></p>
        <p><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $output['goods']['goods_id']));?>" target="_blank">点此查看最新商品详情</a></p>
      </div>
      <dl>
        <dt>运费：</dt>
        <dd><?php echo $output['order_info']['shipping_fee'] == 0 ? '免运费' : ncPriceFormat($output['order_info']['shipping_fee']);?></dd>
      </dl>
      <?php if($output['goods']["contractlist"]){?>
      <dl class="ncs-cti">
        <dt>服务承诺：</dt>
        <dd>
          <?php foreach($output['goods']["contractlist"] as $gcitem_k=>$gcitem_v){?>
          <span <?php if($gcitem_v['cti_descurl']){ ?>onclick="window.open('<?php echo $gcitem_v['cti_descurl'];?>');"<?php }?> title="<?php echo $gcitem_v['cti_name'];?>"> <img src="<?php echo $gcitem_v['cti_icon_url_60'];?>" alt="<?php echo $gcitem_v['cti_name'];?>"/> </span>
          <?php }?>
        </dd>
      </dl>
      <?php }?>
    </div>
    <?php if (!empty($output['store_info'])) {?>
    <div class="ncs-info">
      <div class="title">
        <h4><?php echo $output['store_info']['store_name']; ?></h4>
      </div>
      <div class="content">
        <div class="ncs-detail-rate">
          <ul>
            <?php  foreach ($output['store_info']['store_credit'] as $value) {?>
            <li>
              <h5><?php echo $value['text'];?></h5>
              <div class="<?php echo $value['percent_class'];?>" title="<?php echo $value['percent_text'];?><?php echo $value['percent'];?>"><?php echo $value['credit'];?><i></i></div>
            </li>
            <?php } ?>
          </ul>
        </div>
        <div class="btns"><a href="<?php echo urlShop('show_store', 'index', array('store_id' => $output['store_info']['store_id']), $output['store_info']['store_domain']);?>" class="goto" >进店逛逛</a><a href="javascript:collect_store('<?php echo $output['store_info']['store_id'];?>','count','store_collect')" >收藏店铺<span>(<em nctype="store_collect"><?php echo $output['store_info']['store_collect']?></em>)</span></a></div>
        <dl class="no-border">
          <dt>公司名称：</dt>
          <dd><?php echo $output['store_info']['store_company_name'];?></dd>
        </dl>
        <?php if(!empty($output['store_info']['store_phone'])){?>
        <dl>
          <dt>电&#12288;&#12288;话：</dt>
          <dd><?php echo $output['store_info']['store_phone'];?></dd>
        </dl>
        <?php } ?>
        <dl>
          <dt>所&nbsp;在&nbsp;地：</dt>
          <dd><?php echo $output['store_info']['area_info'];?></dd>
        </dl>
        <?php if(!empty($output['store_info']['store_qq']) || !empty($output['store_info']['store_ww'])){?>
        <dl class="messenger">
          <dt>联系方式：</dt>
          <dd><span member_id="<?php echo $output['store_info']['member_id'];?>"></span>
            <?php if(!empty($output['store_info']['store_qq'])){?>
            <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $output['store_info']['store_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $output['store_info']['store_qq'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $output['store_info']['store_qq'];?>:52" style=" vertical-align: middle;"/></a>
            <?php }?>
            <?php if(!empty($output['store_info']['store_ww'])){?>
            <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&amp;uid=<?php echo $output['store_info']['store_ww'];?>&site=cntaobao&s=1&charset=<?php echo CHARSET;?>" ><img border="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $output['store_info']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="<?php echo $lang['nc_message_me'];?>" style=" vertical-align: middle;"/></a>
            <?php }?>
          </dd>
        </dl>
        <?php } ?>
      </div>
    </div>
    <?php }?>
  </div>
  <div id="content" class="ncs-goods-layout" >
    <div class="title"><span>订单详情</span></div>
    <div class="ncs-intro" id="ncGoodsIntro">
      <?php if(is_array($output['goods']['goods_attr']) && !empty($output['goods']['goods_attr'])){?>
        <ul class="nc-goods-sort">
          <?php if(is_array($output['goods']['goods_attr']) && !empty($output['goods']['goods_attr'])){?>
          <?php
            foreach ($output['goods']['goods_attr'] as $k => $v) {
                if ($v != '') {
                    echo '<li>'.$k.$lang['nc_colon'].$v.'</li>';
                }
            }
            }?>
        </ul>
     <?php } ?>
        <div class="ncs-goods-info-content">
          <?php if ($output['goods']['plate_top']) {?>
          <div class="top-template"><?php echo $output['goods']['plate_top']?></div>
          <?php }?>
          <div class="default"><?php echo $output['goods']['goods_body']; ?></div>
          <?php if ($output['goods']['plate_bottom']) {?>
          <div class="bottom-template"><?php echo $output['goods']['plate_bottom']?></div>
          <?php }?>
        </div>
      </div>
    </div>
</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.thumb.min.js"></script> 
<script>
$(function(){	
	$('.ncs-goods-picture img').jqthumb({
		width: 300,
		height: 300,
		after: function(imgObj){
			imgObj.css('opacity', 0).attr('title',$(this).attr('alt')).animate({opacity: 1}, 2000);
		}
	});
});
</script>