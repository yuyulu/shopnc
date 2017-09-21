<?php defined('In33hao') or exit('Access Invalid!');?>
<style type="text/css">
.warp-all { width:1210px!important }
.hao-zt-mainbox .ml-item { position:relative; width:585px; float:left; height:213px; border:1px solid #f6f6f6; border-right-color:#dbdbdb; border-bottom-color:#dbdbdb; margin-bottom:20px; zoom:1; margin-right:10px; box-shadow:1px 2px 2px -1px #dbdbdb }
.hao-zt-mainbox .ml-item .mli-img { position:relative; margin-left:206px }
.hao-zt-mainbox .ml-item .mli-info { position:absolute; left:0; top:0; width:210px; height:213px; overflow:hidden; background:#fff; text-align:center }
.hao-zt-mainbox .ml-item .mli-info .brand-active { overflow:hidden; width:210px; padding-top:20px; padding-bottom:15px; border-bottom:1px dotted #eee; margin-bottom:10px; font:400 12px/14px arial, '\5fae\8f6f\96c5\9ed1'; color:#666 }
.hao-zt-mainbox .ml-item .mli-info .brand-active a { display:block; font-size:14px; overflow:hidden; width:210px; height:14px; text-overflow:ellipsis; white-space:nowrap; color:#484848 }
.hao-zt-mainbox .ml-item .mli-info .brand-rebate { width:190px; margin:10px; cursor:default }
.hao-zt-mainbox .ml-item .mli-info .brand-rebate span { font:400 12px/1em '\5fae\8f6f\96c5\9ed1'; color:#666 }
.hao-zt-mainbox .ml-item .mli-info .brand-rebate a { position:absolute; bottom:20px; width:100px; display:block; box-sizing:border-box; line-height:30px; height:30px; border-radius:2px; background:#dd2727; color:#FFF; padding:0 20px; margin-left:40px }
.no-content { font: normal 16px/20px Arial, "microsoft yahei"; color: #999999; text-align: center; padding: 150px 0; }
</style>
<div class="warp-all">
  <div class="hao-zt-mainbox">
    <?php if(!empty($output['special_list']) && is_array($output['special_list'])) {?>
    <ul class="special-list clearfix">
      <?php foreach($output['special_list'] as $value) {?>
      <div class="ml-item">
        <div class="mli-img" ><a href="<?php echo urlshop('special','special_detail', array('special_id'=>$value['special_id']));?>" target="_blank"><img width="380" height="213" src="<?php echo getCMSSpecialImageUrl($value['special_image']);?>" class=""></a></div>
        <div class="mli-info">
          <div class="brand-active"><a href="<?php echo urlshop('special','special_detail', array('special_id'=>$value['special_id']));?>" target="_blank"><?php echo $value['special_title'];?></a></div>
          <div class="brand-rebate"><span><?php echo $value['special_stitle'];?></span> <a href="<?php echo urlshop('special','special_detail', array('special_id'=>$value['special_id']));?>" class="brand-con">点击查看</a></div>
        </div>
      </div>
      <?php } ?>
    </ul>
    <div class="pagination"> <?php echo $output['show_page'];?> </div>
    <?php } else { ?>
    <div class="no-content">暂无专题内容</div>
    <?php } ?>
  </div>
</div>
