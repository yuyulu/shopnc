<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=goods_album&op=list" title="返回<?php echo $lang['g_album_list'];?>"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['g_album_manage'];?> - <?php echo $output['title'];?></h3>
        <h5><?php echo $lang['g_album_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form method='post' action="index.php" name="picForm" id="picForm">
    <input type="hidden" name="act" value="goods_album" />
    <input type="hidden" name="op" value="del_more_pic" />
    <div class="ncap-store-album">
      <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
      <ul class="ncap-thumb-list">
        <?php foreach($output['list'] as $k => $v){ ?>
        <li class="picture">
          <input class="checkitem" type="checkbox" name="delbox[]" value="<?php echo $v['apic_id'];?>">
          <div class="thumb-list-pics">
            <?php if($v['apic_cover'] != ''){ ?>
            <a nctype="nyroModal" href="<?php echo cthumb($v['apic_cover'], 1280, $v['store_id']);?>" rel="gal"> <img src="<?php echo cthumb($v['apic_cover'], 240, $v['store_id']);?>"> </a>
            <?php }else{?>
            <a href="javascript:void(0);"><img src="<?php echo ADMIN_SITE_URL.'/templates/'.TPL_NAME.'/images/member/default_image.png';?>"></a>
            <?php }?>
          </div>
          <a href="javascript:void(0);" nc_type="delete" nc_key="<?php echo $v['apic_id'].'|'.$v['apic_cover'];?>" class="del" title="<?php echo $lang['nc_del'];?>">X</a>
          <p><?php echo date('Y-m-d',$v['upload_time']) . '<br/>' . $v['apic_spec'] . '<br/>' . number_format($v['apic_size']/1024,2) . 'k';?> </p>
        </li>
        <?php } ?>
      </ul>
      <?php }else { ?>
      <div class="no-data"><i class="fa fa-exclamation-circle"></i><?php echo $lang['nc_no_record'];?></div>
      <?php } ?>
    </div>
    <div class="bot">
      <input id="checkallBottom" class="checkall" type="checkbox" />
      <label for="checkallBottom"><?php echo $lang['nc_select_all'];?></label>
      <a class="ncap-btn-mini ncap-btn-red" href="javascript:void(0);" onclick="if(confirm('<?php echo $lang['nc_ensure_del'];?>')){$('#picForm').submit();}"><span><?php echo $lang['nc_del'];?></span></a>
      <div class="pagination"><?php echo $output['page'];?> </div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>

<script>
$(function(){
	$('a[nctype="nyroModal"]').nyroModal();
	$('a[nc_type="delete"]').bind('click',function(){
		if(!confirm('<?php echo $lang['nc_ensure_del'];?>')) return false;
		cur_note = this;
		$.get("index.php?act=goods_album&op=del_album_pic", {'key':$(this).attr('nc_key')}, function(data){
            if (data == 1) {
            	$(cur_note).parents('li:first').remove();
            } else {
            	alert('<?php echo $lang['nc_common_del_fail'];?>');
            }
        });
	});

});
</script> 
