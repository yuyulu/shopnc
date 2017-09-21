<?php defined('In33hao') or exit('Access Invalid!');?>
<!--店铺基本信息 S-->
<div class="ncs-info">
  <div class="title">
    <h4><a class="name" title="<?php echo $output['store_info']['store_name'];?>" target="_blank" href="<?php echo urlShop('show_store', 'index', array('store_id' => $output['store_info']['store_id']), $output['store_info']['store_domain']);?>" ><?php echo $output['store_info']['store_name']; ?></a><?php if ($output['store_info']['is_own_shop']) { ?><em>自营</em><?php } ?></h4>
  </div>
  <div class="content">
<div class="store-logo"><a title="<?php echo $output['store_info']['store_name'];?>" target="_blank" href="<?php echo urlShop('show_store', 'index', array('store_id' => $output['store_info']['store_id']), $output['store_info']['store_domain']);?>" ><img src="<?php echo getStoreLogo($output['store_info']['store_label'],'store_logo');?>" alt="<?php echo $output['store_info']['store_name'];?>"></a></div>
    <?php if (!$output['store_info']['is_own_shop']) { ?>
    <div class="ncs-detail-rate">
      <ul>
        <?php  foreach ($output['store_info']['store_credit'] as $value) {?>
        <li>
          <h5><?php echo $value['text'];?></h5>
          <div class="<?php echo $value['percent_class'];?>" title="<?php echo $value['percent_text'];?><?php echo $value['percent'];?>"><?php echo $value['credit'];?><i></i></div>
        </li>
        <?php } ?>
      </ul>
    </div><?php } ?>
    <?php if (!$output['store_info']['is_own_shop']) { ?>
    <dl class="no-border">
      <dt>公司名称：</dt>
      <dd><?php echo $output['store_info']['store_company_name'];?></dd>
    </dl>
     <dl>
      <dt>所 在 地：</dt>
      <dd><?php echo $output['store_info']['area_info'];?></dd>
    </dl>
    <?php if(!empty($output['store_info']['store_phone'])){?>
    <dl>
      <dt>客服电话：</dt>
      <dd><?php echo $output['store_info']['store_phone'];?></dd>
    </dl>
    <?php } ?>
        <?php if($output['store_info']['store_workingtime'] !=''){?>
   
        <dl>
      <dt>工作时间：</dt>
      <dd><?php echo html_entity_decode($output['store_info']['store_workingtime']);?></dd>
    </dl> <?php }?>
    <?php } ?>
    <?php if(!empty($output['store_info']['store_qq']) || !empty($output['store_info']['store_ww'])){?>
    <?php } ?>
    <div class="btns clearfix"><a href="<?php echo urlShop('show_store', 'index', array('store_id' => $output['store_info']['store_id']), $output['store_info']['store_domain']);?>" class="goto" >进店逛逛</a><a href="javascript:collect_store('<?php echo $output['store_info']['store_id'];?>','count','store_collect')" >收藏店铺<span>(<em nctype="store_collect"><?php echo $output['store_info']['store_collect']?></em>)</span></a>
   <?php if(!empty($output['store_info']['store_qq'])){?>
        <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $output['store_info']['store_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $output['store_info']['store_qq'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $output['store_info']['store_qq'];?>:8" style=" vertical-align: middle;"/></a>
        <?php }?>
        <?php if(!empty($output['store_info']['store_ww'])){?>
        <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&amp;uid=<?php echo $output['store_info']['store_ww'];?>&site=cntaobao&s=1&charset=<?php echo CHARSET;?>" ><img border="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $output['store_info']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="<?php echo $lang['nc_message_me'];?>" style=" vertical-align: text-bottom;"/> 旺旺</a>
        <?php }?>
     </div>
  </div>
</div>
 <!--S 看了又看 -->
      <div class="ncs-lal">
        <div class="content">
          <ul>
            <?php foreach ((array) $output['goods_rand_list'] as $g) { ?>
            <li>
              <div class="goods-pic"><a title="<?php echo $g['goods_name']; ?>" href="<?php echo urlShop('goods', 'index', array('goods_id' => $g['goods_id'], )); ?>"> <img alt="" src="<?php echo cthumb($g['goods_image'], 60); ?>" /> </a></div>
              <div class="goods-price">￥<?php echo ncPriceFormat($g['goods_promotion_price']); ?></div>
            </li>
            <?php } ?>
          </ul>
        </div>
      </div>
      <!--E 看了又看 --> 
<script>
$(function(){
	var store_id = "<?php echo $output['store_info']['store_id']; ?>";
	var goods_id = "<?php echo $_GET['goods_id']; ?>";
	var act = "<?php echo trim($_GET['act']); ?>";
	var op  = "<?php echo trim($_GET['op']) != ''?trim($_GET['op']):'index'; ?>";
	$.getJSON("index.php?act=show_store&op=ajax_flowstat_record",{store_id:store_id,goods_id:goods_id,act_param:act,op_param:op});
});
</script> 
