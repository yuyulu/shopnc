<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>友情连接</h3>
        <h5>管理友情连接信息</h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
      <span id="explanationZoom" title="收起提示"></span> </div>
    <ul>
      <li>通过合作伙伴管理你可以，编辑、查看、删除合作伙伴信息</li>
      <li>在搜索处点击图片则表示将搜索图片标识仅为图片的相关信息，点击文字则表示将搜索图片标识仅为文字的相关信息，点击全部则搜索所有相关信息</li>
    </ul>
  </div>
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" class="handle" align="center"><?php echo $lang['nc_handle'];?></th>
          <th width="100" align="center"><?php echo $lang['nc_sort'];?></th>
          <th width="200">连接名称</th>
          <th width="100">图片标识</th>
          <th width="200" align="center">连接地址</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['link_list']) && is_array($output['link_list'])){ ?>
        <?php foreach($output['link_list'] as $k => $v){ ?>
        <tr data-id="<?php echo $v['link_id'];?>">
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle"><a href="javascript:submit_delete(<?php echo $v['link_id'];?>);" class="btn red"><i class="fa fa-trash-o"></i><?php echo $lang['nc_del'];?></a>
          <a class="btn blue" href="index.php?act=link&op=link_edit&link_id=<?php echo $v['link_id'];?>"><i class="fa fa-pencil-square-o"></i><?php echo $lang['nc_edit'];?></a>
          </td>
          <td class="sort"><span nc_type="tag_sort" column_id="<?php echo $v['link_id'];?>" title="<?php echo $lang['nc_editable'];?>" class="editable "><?php echo $v['link_sort'];?></span></td>
          <td><?php echo $v['link_title'];?></td>
          <td>
		  <?php if ($v['link_pic'] != '') { ?>
            <a class="pic-thumb-tip" onmouseover="toolTip('<img src=<?php echo $v['link_pic']; ?>>')" onmouseout="toolTip()" href="javascript:void(0);"> <i class="fa fa-picture-o"></i></a>
<?php }else {?><?php echo $v['link_title']; ?><?php } ?>
</td>
          <td><?php echo $v['link_url'];?></td>
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
		title: '连接列表',// 表格标题
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
        window.location.href = 'index.php?act=link&op=link_del&link_id='+id;
    }
}
function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=link&op=link_add';
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