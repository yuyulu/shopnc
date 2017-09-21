<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_microshop_personal_class'];?></h3>
        <h5><?php echo $lang['nc_microshop_personal_class_subhead'];?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['microshop_goods_class_tip1'];?></li>
    </ul>
  </div>
  <form id="list_form" method='post'>
    <input id="class_id" name="class_id" type="hidden" />
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" align="center" class="handle"><?php echo $lang['nc_handle'];?></th>
          <th width="60" align="center"><?php echo $lang['nc_sort'];?></th>
          <th width="300"><?php echo $lang['microshop_class_name'];?></th>
          <th width="80" align="center">分类图片</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <?php foreach($output['list'] as $val){ ?>
        <tr data-id="<?php echo $val['class_id'];?>">
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle"><a href="index.php?act=personal_class&op=personalclass_drop&class_id=<?php echo $val['class_id'];?>" class="btn red confirm-del"><i class="fa fa-trash-o"></i><?php echo $lang['nc_del'];?></a><a href="index.php?act=personal_class&op=personalclass_edit&class_id=<?php echo $val['class_id'];?>" class="btn blue"><i class="fa fa-pencil-square-o"></i><?php echo $lang['nc_edit'];?></a></td>
          <td class="sort"><span nc_type="class_sort" column_id="<?php echo $val['class_id'];?>" title="<?php echo $lang['nc_editable'];?>" class="editable "><?php echo $val['class_sort'];?></span>
          <td class="name"><span nc_type="class_name" column_id="<?php echo $val['class_id'];?>" title="<?php echo $lang['nc_editable'];?>" class="editable "><?php echo $val['class_name'];?></span></td>
          <td><a href="javascript:;" class="pic-thumb-tip" onmouseout="toolTip()" onmouseover="toolTip('<img src=\'<?php echo UPLOAD_SITE_URL.DS.ATTACH_MICROSHOP.DS. (empty($val['class_image']) ? 'default_goods_class_image.gif' : $val['class_image']); ?>\'>')"> <i class='fa fa-picture-o'></i> </a></td>
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
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript">
$(function(){
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		title: '个人秀分类列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
        buttons : [
                   {display: '<i class="fa fa-plus"></i>新增分类', name : 'add', bclass : 'add', title : '新增分类', onpress : fg_operation },
				   {display: '<i class="fa fa-trash"></i>批量删除', name : 'del', bclass : 'del', title : '将选定行数据批量删除', onpress : function() {
                    var ids = [];
                    $('.trSelected[data-id]').each(function() {
                        ids.push($(this).attr('data-id'));
                    });
                    if (ids.length < 1 || !confirm('确定删除?')) {
                        return false;
                    }
                    location.href = 'index.php?act=personal_class&op=personalclass_drop&class_id=__IDS__'.replace('__IDS__', ids.join(','));
                } }
               ]
		});

    //行内ajax编辑
    $('span[nc_type="class_sort"]').inline_edit({act: 'personal_class',op: 'personalclass_sort_update'});
    $('span[nc_type="class_name"]').inline_edit({act: 'personal_class',op: 'personalclass_name_update'});

    $('a.confirm-del').live('click', function() {
        if (!confirm('确定删除？')) {
            return false;
        }
    });

});
function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=personal_class&op=personalclass_add';
    }
}
</script>