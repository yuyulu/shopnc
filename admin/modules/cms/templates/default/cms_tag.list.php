<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_cms_tag_manage'];?></h3>
        <h5><?php echo $lang['nc_cms_tag_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['cms_article_class_list_tip1'];?></li>
    </ul>
  </div>
  <form id="list_form" method='post'>
    <input id="tag_id" name="tag_id" type="hidden" />
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="60" align="center" class="handle-s"><?php echo $lang['nc_handle'];?></th>
          <th width="60" align="center"><?php echo $lang['nc_sort'];?></th>
          <th width="350" align="left"><?php echo $lang['cms_tag_name'];?></th>
          <th width="150" align="center"><?php echo $lang['cms_tag_count'];?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <?php foreach($output['list'] as $val){ ?>
        <tr data-id="<?php echo $val['tag_id'];?>">
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle-s"><a href="javascript:submit_delete(<?php echo $val['tag_id'];?>)" class="btn red"><i class="fa fa-trash-o"></i><?php echo $lang['nc_del'];?></a></td>
          <td class="sort"><span nc_type="tag_sort" column_id="<?php echo $val['tag_id'];?>" title="<?php echo $lang['nc_editable'];?>" class="editable "><?php echo $val['tag_sort'];?></span></td>
          <td class="name"><span nc_type="tag_name" column_id="<?php echo $val['tag_id'];?>" title="<?php echo $lang['nc_editable'];?>" class="editable "><?php echo $val['tag_name'];?></span></td>
          <td><?php echo $val['tag_count'];?></td>
          <td></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="no-data" colspan="100"><i class="fa fa-exclamation-triangle"></i><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.edit.js" charset="utf-8"></script> 
<script type="text/javascript">
$(document).ready(function(){
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		title: '标签列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
        buttons : [ 
                   {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operation },
                   {display: '<i class="fa fa-trash"></i>批量删除', name : 'delete', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operation }
               ]
		});
    //行内ajax编辑
    $('span[nc_type="tag_sort"]').inline_edit({act: 'cms_tag',op: 'update_tag_sort'});
    $('span[nc_type="tag_name"]').inline_edit({act: 'cms_tag',op: 'update_tag_name'});
});

function submit_delete(id){
	if (typeof id == 'number') {
    	var id = new Array(id.toString());
	};
	if(confirm('删除后将不能恢复，确认删除这 ' + id.length + ' 项吗？')){
		id = id.join(',');
        $('#list_form').attr('method','post');
        $('#list_form').attr('action','index.php?act=cms_tag&op=cms_tag_drop');
        $('#tag_id').val(id);
        $('#list_form').submit();
    }
}
function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=cms_tag&op=cms_tag_add';
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