<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <ul id="listpj" class="tab">
      <li class="active"><a href="index.php?act=<?php echo $output['pj_act'];?>&op=list">交易评价/晒单</a></li>
    </ul>
  </div>
  <form id="goodsevalform" method="get" class="tc">
    <input type="hidden" name="act" value="<?php echo $output['pj_act'];?>"/>
    <input type="hidden" name="op" value="list"/>
    <input type="hidden" name="type" value="<?php echo $_GET['type'];?>"/>
    <?php if (is_array($output['goodsevallist']) && !empty($output['goodsevallist'])) { ?>
    <div class="ncm-evaluation-list">
      <?php foreach ((array)$output['goodsevallist'] as $k=>$v){?>
      <div class="ncm-evaluation-timeline">
        <div class="date"><?php echo @date('Y-m-d H:i:s',$v['geval_addtime']);?></div>
        <div class="goods-thumb"><a target="_blank" href="<?php echo urlShop('goods', 'index', array('goods_id' => $v['geval_goodsid']));?>"> <img src="<?php echo cthumb($v['geval_goodsimage'], 60);?>"> </a></div>
        <dl class="detail">
          <dt><a target="_blank" href="<?php echo urlShop('goods', 'index', array('goods_id'=>$v['geval_goodsid'])); ?>"><?php echo $v['geval_goodsname']?></a><span class="ml30">商品评分：<em class="raty" data-score="<?php echo $v['geval_scores'];?>"></em></span></dt>
          <dd><?php echo $v['geval_content'];?></dd>
          <dd>
            <?php if (!empty($v['geval_image'])){?>
            <ul class="photos-thumb">
              <?php $image_array = explode(',', $v['geval_image']);?>
              <?php foreach ($image_array as $value) { ?>
              <li> <a nctype="nyroModal"  href="<?php echo snsThumb($value, 1024);?>"> <img src="<?php echo snsThumb($value);?>"> </a> </li>
              <?php } ?>
            </ul>
            <?php }?>
          </dd>
          <?php if (!empty($v['geval_explain'])){?>
          <dd class="explain">[<?php echo $lang['member_evaluation_explain'];?>]<?php echo $v['geval_explain'];?> </dd>
          <?php }?>
          <?php if (!empty($v['geval_content_again'])){?>
          <hr/>
          <dd>[追加评价]&nbsp;<?php echo $v['geval_content_again'];?> </dd>
          <?php if (!empty($v['geval_image_again'])){?>
          <dd>
            <ul class="photos-thumb">
              <?php $image_array = explode(',', $v['geval_image_again']);?>
              <?php foreach ($image_array as $value) { ?>
              <li> <a nctype="nyroModal"  href="<?php echo snsThumb($value, 1024);?>"> <img src="<?php echo snsThumb($value);?>"> </a> </li>
              <?php } ?>
            </ul>
          </dd>
          <?php }?>
          <dd class="pubdate">确认收货并评价后<?php echo ($d = floor($v['geval_addtime_again']/ 60 / 60 / 24) - floor($v['geval_addtime']/ 60 / 60 / 24)) == 0? '当' : $d;?>天再次追加评价</dd>
          <?php if (!empty($v['geval_explain_again'])){?>
          <dd class="explain"> [<?php echo $lang['member_evaluation_explain'];?>]<?php echo $v['geval_explain_again'];?> </dd>
          <?php }?>
          <?php }?>
        </dl>
      </div>
      <?php }?>
    </div>
    <div class="pagination mt30"><?php echo $output['show_page']; ?></div>
    <?php } else { ?>
    <div class="norecord">
      <div class="warning-option"><i></i><span><?php echo $lang['no_record'];?></span></div>
    </div>
    <?php } ?>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/jquery.raty.min.js"></script> 
<script type="text/javascript">
$(document).ready(function(){
    $('.raty').raty({
        path: "<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/img",
        readOnly: true,
        score: function() {
          return $(this).attr('data-score');
        }
    });

   $('a[nctype="nyroModal"]').nyroModal();
});
</script> 
