<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['navigation_index_nav'];?></h3>
        <h5><?php echo $lang['navigation_index_nav_subhead'];?></h5>
      </div>
    </div>
  </div>
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" class="handle" align="center"><?php echo $lang['nc_handle'];?></th>
          <th width="100" align="center"><?php echo $lang['nc_sort'];?></th>
          <th width="200"><?php echo $lang['navigation_index_title'];?></th>
          <th width="400"><?php echo $lang['navigation_index_url'];?></th>
          <th width="50" align="center"><?php echo $lang['navigation_index_location'];?></th>
          <th width="100" align="center"><?php echo $lang['navigation_index_open_new'];?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['navigation_list']) && is_array($output['navigation_list'])){ ?>
        <?php foreach($output['navigation_list'] as $k => $v){ ?>
        <tr data-id="<?php echo $v['nav_id'];?>">
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle"><a href="javascript:submit_delete(<?php echo $v['nav_id'];?>);" class="btn red"><i class="fa fa-trash-o"></i><?php echo $lang['nc_del'];?></a>
          <a class="btn blue" href="index.php?act=navigation&op=navigation_edit&nav_id=<?php echo $v['nav_id'];?>"><i class="fa fa-pencil-square-o"></i><?php echo $lang['nc_edit'];?></a>
          </td>
          <td class="sort"><span nc_type="tag_sort" column_id="<?php echo $v['nav_id'];?>" title="<?php echo $lang['nc_editable'];?>" class="editable "><?php echo $v['nav_sort'];?></span></td>
          <td><?php echo $v['nav_title'];?></td>
          <td><?php echo $v['nav_url'];?></td>
          <td><?php echo $v['nav_location'];?></td>
          <td><span class="<?php echo $v['nav_new_open'] ? 'yes' : 'no';?>">
            <i class="fa fa-check-circle"></i>
            <?php echo $v['nav_new_open'] ? '是' : '否';?>
          </span></td>
          <td></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr>
          <td class="no-data" colspan="100"><i class="fa fa-exclamation-triangle"></i><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		title: '页面列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
        buttons : [ 
           {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operation },
           {display: '<i class="fa fa-trash"></i>批量删除', name : 'delete', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operation }
        ]
	});
    //行内ajax编辑
    $('span[nc_type="tag_sort"]').inline_edit({act: 'navigation',op: 'ajax'});
    $('span[nc_type="tag_name"]').inline_edit({act: 'navigation',op: 'ajax'});
});

function submit_delete(id){
	if (typeof id == 'number') {
    	var id = new Array(id.toString());
	};
	if(confirm('删除后将不能恢复，确认删除这 ' + id.length + ' 项吗？')){
		id = id.join(',');
        window.location.href = 'index.php?act=navigation&op=navigation_del&nav_id='+id;
    }
}
function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=navigation&op=navigation_add';
    }else if (name == 'delete') { 
        if($('.trSelected',bDiv).length>0){
            var items = $('.trSelected',bDiv);
            var itemlist = new Array();
            $('.trSelected',bDiv).each(function(){
            	itemlist.push($(this).attr('data-id'));
            });
            submit_delete(itemlist);
        }
    }
}
</script>