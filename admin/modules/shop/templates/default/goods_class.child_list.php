<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=goods_class&op=goods_class&gc_id=<?php echo $output['parent_id']?>" title="返回上级分类列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['goods_class_index_class'];?> - <?php echo $output['title']?></h3>
        <h5><?php echo $lang['goods_class_index_class_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
    </div>
    <ul>
      <li><?php echo $lang['goods_class_index_help1'];?></li>
      <li><?php echo $lang['goods_class_index_help3'];?></li>
    </ul>
  </div>
  <form method='post'>
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="submit_type" id="submit_type" value="" />
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" class="handle" align="center"><?php echo $lang['nc_handle'];?></th>
          <th width="60" align="center"><?php echo $lang['nc_sort'];?></th>
          <th width="300" align="left"><?php echo $lang['goods_class_index_name'];?></th>
          <th width="80" align="center"><?php echo $lang['goods_class_add_type'];?></th>
          <th width="80" align="center"><?php echo $lang['goods_class_add_commis_rate'];?></th>
          <th width="80" align="center">虚拟</th>
          <th width="120" align="center">商品展示</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['class_list']) && is_array($output['class_list'])){ ?>
        <?php foreach($output['class_list'] as $k => $v){ ?>
        <tr data-id="<?php echo $v['gc_id'];?>">
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle">
            <a class="btn red" href="javascript:void(0);" onclick="fg_del(<?php echo $v['gc_id'];?>);"><i class="fa fa-trash-o"></i><?php echo $lang['nc_del'];?></a>
            <span class="btn"><em><i class="fa fa-cog"></i><?php echo $lang['nc_set'];?><i class="arrow"></i></em>
            <ul>
              <li><a href="index.php?act=goods_class&op=goods_class_edit&gc_id=<?php echo $v['gc_id'];?>">编辑分类信息</a></li>
              <?php if ($output['deep'] != 3) {?>
              <li><a href="index.php?act=goods_class&op=goods_class_add&gc_parent_id=<?php echo $v['gc_id'];?>">新增下级分类</a></li>
              <?php }?>
              <?php if ($v['have_child'] == 1) {?>
              <li><a href="index.php?act=goods_class&op=goods_class&gc_id=<?php echo $v['gc_id'];?>">查看下级分类</a></li>
              <?php }?>
            </ul>
            </span></td>
          <td class="sort"><span title="<?php echo $lang['nc_editable'];?>" column_id="<?php echo $v['gc_id'];?>" fieldname="gc_sort" nc_type="inline_edit" class="editable "><?php echo $v['gc_sort'];?></span></td>
          <td class="name"><span title="<?php echo $lang['nc_editable'];?>"  column_id="<?php echo $v['gc_id'];?>" fieldname="gc_name" nc_type="inline_edit" class="editable "><?php echo $v['gc_name'];?></span></td>
          <td><?php echo $v['type_name'];?></td>
          <td><?php echo $v['commis_rate'];?> %</td>
          <td><?php if ($v['gc_virtual'] == 1) {?><span class="yes"><i class="fa fa-check-circle"></i>允许</span><?php } else {?><span class="no"><i class="fa fa-ban"></i>禁止</span><?php }?></td>
          <td><?php echo $output['show_type'][$v['show_type']]?></td>
          <td></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr>
          <td class="no-data" colspan="100"><i class="fa fa-exclamation-circle"></i><?php echo $lang['nc_no_record'];?></td>
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
        title: '分类<?php echo $output['title']?>',// 表格标题
        reload: false,// 不使用刷新
        columnControl: false,// 不使用列控制
        buttons : [
                   {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', onpress : fg_operation },
                   {display: '<i class="fa fa-trash"></i>批量删除', name : 'del', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operation }
               ]
    });

    $('span[nc_type="inline_edit"]').inline_edit({act: 'goods_class',op: 'ajax'});
});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=goods_class&op=goods_class_add&gc_parent_id=<?php echo $output['gc_id']?>';
    }
    if (name == 'del') {
        if ($('.trSelected', bDiv).length == 0) {
            showError('请选择要操作的数据项！');
        }
        var itemids = new Array();
        $('.trSelected', bDiv).each(function(i){
            itemids[i] = $(this).attr('data-id');
        });
        fg_del(itemids);
    }
}
function fg_del(ids) {
    if (typeof ids == 'number') {
        var ids = new Array(ids.toString());
    };
    id = ids.join(',');
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=goods_class&op=goods_class_del', {id:id}, function(data){
            if (data.state) {
                location.reload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script>