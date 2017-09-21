<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
<div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['db_index_db'];?></h3>
        <h5>数据库恢复与备份</h5>
      </div>
      
      <ul class="tab-base nc-row"><li><a href="index.php?act=db&op=db"><span><?php echo $lang['db_index_backup'];?></span></a></li><li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['db_index_restore'];?></span></a></li></ul> </div>
  </div>
   <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
      <span id="explanationZoom" title="收起提示"></span> </div>
    <ul>
      <li><?php echo $lang['db_import_help1'];?></li>
    </ul>
  </div>
  <form method="post" id="form_db">
    <input type="hidden" name="form_submit" value="ok" />
     <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="350" align="left"><?php echo $lang['nc_handle'];?></th>
          <th width="150" align="center" class="handle"><?php echo $lang['db_index_name'];?></th>
          <th width="60" align="center"><?php echo $lang['db_restore_backup_time'];?></th>
          <th width="350" align="left"><?php echo $lang['db_restore_backup_size'];?></th>
          <th width="350" align="left"><?php echo $lang['db_restore_volumn'];?></th>
          
          <th></th>
        </tr>
      </thead>
         <tbody id="treet1">
        <?php if(!empty($output['dir_list']) && is_array($output['dir_list'])){ ?>
        <?php foreach($output['dir_list'] as $k => $v){ ?>
        <tr class="hover">
        <td  align="center" class="handle">
        <span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em>
            <ul><li><a href="javascript:if(confirm('<?php echo $lang['nc_ensure_del'];?>')){location.href='index.php?act=db&op=db_del&dir_name=<?php echo $v['name'];?>'};"><?php echo $lang['nc_del'];?></a></li>
              <li><a href="javascript:if(confirm('<?php echo $lang['db_index_backup_tip'];?>?')){location.href='index.php?act=db&op=db_import&dir_name=<?php echo $v['name'];?>&step=1'};"><?php echo $lang['db_restore_import'];?></a></li>

            </ul>
            </span>
               </td>
         
          <td class="w25pre"><!--<img fieldid="<?php echo $v['name'];?>" status="open" nc_type="flex" src="<?php echo TEMPLATES_PATH;?>/images/tv-expandable.gif">--> 
            <?php echo $v['name'];?></td>
          <td class="w25pre"><?php echo $v['make_time'];?></td>
          <td class="align-center"><?php echo $v['size'];?></td>
          <td class="align-center"><?php echo $v['file_num'];?></td>

        </tr>
        <?php } ?>
        <?php }else { ?>
                <tr>
          <td class="no-data" colspan="100"><i class="fa fa-exclamation-triangle"></i><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
        </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.db_dir.js"></script> 
<script type="text/javascript">
$(function(){
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		title: '文章分类列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
        buttons : [
                   {display: '<i class="fa fa-plus"></i>备份', name : 'add', bclass : 'add', title : '备份', onpress : fg_operation },
			
               ]
	});

    $('img[nc_type="flex"]').toggle(
        function(){
            $('tr[nctype="' + $(this).attr('fieldid') + '"]').show();
            $(this).attr('src', '<?php echo ADMIN_TEMPLATES_URL;?>/images/tv-collapsable.gif');
        },function(){
            $('tr[nctype="' + $(this).attr('fieldid') + '"]').hide();
            $(this).attr('src', '<?php echo ADMIN_TEMPLATES_URL;?>/images/tv-expandable.gif');
        }
    );
});
function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=db&op=db';
    }
	
}
</script> 
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.edit.js" charset="utf-8"></script> 
