<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=sns_malbum&op=class_list" title="返回<?php echo $lang['snsalbum_class_list'];?>"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['nc_member_album_manage'];?> - <?php echo $lang['snsalbum_pic_list'];?></h3>
        <h5><?php echo $lang['nc_member_album_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form method='post' id="form_pic">
    <input type="hidden" name="form_submit" value="ok" />
    <?php if(!empty($output['pic_list'])){ ?>
    <ul class="ncap-thumb-list">
      <?php foreach($output['pic_list'] as $val){?>
      <li class="picture">
        <input class="checkitem" type="checkbox" name="id[]" value="<?php echo $val['ap_id'];?>" />
        <div class="thumb-list-pics"> <a nctype="nyroModal" href="<?php echo UPLOAD_SITE_URL.DS.ATTACH_MALBUM.DS.$val['member_id'].DS.str_ireplace('.', '_240.', $val['ap_cover']);?>" rel="gal"> <img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_MALBUM.DS.$val['member_id'].DS.str_ireplace('.', '_240.', $val['ap_cover']);?>"> </a> </div>
        <a href="javascript:void(0);" onclick="if(confirm('<?php echo $lang['nc_ensure_del'];?>')){location.href='index.php?act=sns_malbum&op=del_pic&id=<?php echo $val['ap_id'];?>';}else{return false;}" class="del" title="<?php echo $lang['nc_del'];?>">X</a> </li>
      <?php } ?>
    </ul>
    <?php }else { ?>
    <div class="no-data"><i class="fa fa-exclamation-circle"></i><?php echo $lang['nc_no_record']; ?>
      <?php } ?>
    </div>
    <div class="bot">
      <input id="checkallBottom" class="checkall" type="checkbox" />
      <label for="checkallBottom"><?php echo $lang['nc_select_all'];?></label>
      <a class="ncap-btn-mini ncap-btn-red" href="javascript:void(0);" onclick="if(confirm('<?php echo $lang['nc_ensure_del'];?>')){$('#form_pic').submit();}"><span><?php echo $lang['nc_del'];?></span></a>
      <div class="pagination"><?php echo $output['showpage'];?> </div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script> 
<script>
$(function(){
	$('a[nctype="nyroModal"]').nyroModal();
	
});
</script> 
