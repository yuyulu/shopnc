<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['circle_memberlevel'];?></h3>
        <h5><?php echo $lang['nc_circle_memberlevel_subhead'];?></h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="index.php?act=circle_memberlevel"><?php echo $lang['circle_defaultlevel'];?></a></li>
        <li><a href="JavaScript:void(0);" class="current"><?php echo $lang['circle_memberlevelref'];?></a></li>
      </ul>
    </div>
  </div>
  <form method="post" name="clmdForm" id="clmdForm">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" align="center" class="handle"><?php echo $lang['nc_handle'];?></th>
          <th width="150" align="left"><?php echo $lang['circle_memberlevelgroup'];?></th>
          <th width="150" align="center"><?php echo $lang['circle_addtime'];?></th>
          <th width="150" align="center"><?php echo $lang['nc_status'];?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['mlref_list'])){?>
        <?php foreach($output['mlref_list'] as $val){?>
        <tr>
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle"><a href="javascript:void(0);" onclick="if(confirm('<?php echo $lang['nc_ensure_del'];?>')){location.href='index.php?act=circle_memberlevel&op=ref_del&mlref_id=<?php echo $val['mlref_id'];?>'}" class="btn red"><i class="fa fa-trash-o"></i><?php echo $lang['nc_del'];?></a><a href="index.php?act=circle_memberlevel&op=ref_edit&mlref_id=<?php echo $val['mlref_id'];?>" class="btn blue"><i class="fa fa-pencil-square-o"></i><?php echo $lang['nc_edit'];?></a></td>
          <td><?php echo $val['mlref_name'];?></td>
          <td><?php echo date('Y-m-d H:i:s', $val['mlref_addtime']);?></td>
          <td class="yes-onoff"><a href="JavaScript:void(0);" class="<?php echo $val['mlref_status']? 'enabled' : 'disabled' ;?>" ajax_branch="status" nc_type="inline_edit" fieldname="mlref_status" fieldid="<?php echo $val['mlref_id']?>" fieldvalue="<?php echo $val['mlref_status']? '1' : '0' ;?>" title="<?php echo $lang['nc_editable'];?>"><img src="<?php echo ADMIN_TEMPLATES_URL;?>/images/transparent.gif" /></a></td>
          <td></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr>
          <td class="no-data" colspan="100"><i class="fa fa-exclamation-triangle"></i><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php }?>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript">
$(function(){
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		title: '头衔参考列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
        buttons : [
                   {display: '<i class="fa fa-plus"></i>新增头衔', name : 'add', bclass : 'add', title : '新增参考头衔', onpress : fg_operation }
               ]
		});

});
function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=circle_memberlevel&op=ref_add';
    }
}
</script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.edit.js" charset="utf-8"></script> 