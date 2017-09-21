<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['groupbuy_index_manage'];?></h3>
        <h5><?php echo $lang['groupbuy_index_manage_subhead'];?></h5>
      </div>
      <ul class="tab-base nc-row">
        <?php   foreach($output['menu'] as $menu) {  if($menu['menu_type'] == 'text') { ?>
        <li><a href="JavaScript:void(0);" class="current"><?php echo $menu['menu_name'];?></a></li>
        <?php }  else { ?>
        <li><a href="<?php echo $menu['menu_url'];?>" ><?php echo $menu['menu_name'];?></a></li>
        <?php  } }  ?>
      </ul>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>抢购分类最多为2级分类，商家发布抢购活动时选择分类，用于抢购聚合页对抢购活动进行筛选</li>
    </ul>
  </div>
  <form id="list_form" method='post'>
    <input id="class_id" name="class_id" type="hidden" />
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" align="center" class="handle"><?php echo $lang['nc_handle']; ?></th>
          <th width="60" align="left"><?php echo $lang['nc_sort']; ?></th>
          <th width="400">分类名称</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($output['list']) && is_array($output['list'])) { ?>
        <?php foreach ($output['list'] as $val) { ?>
        <tr class="<?php echo $val['class_parent_id'] == '0' ? '' : 'two'; ?> <?php echo 'parent' . $val['class_parent_id']; ?>" data-id="<?php echo $val['class_id']; ?>">
          <td class="sign"><?php if ($val['have_child'] == '1') { ?>
            <img class="node_parent" state="close" node_id="<?php echo 'parent' . $val['class_id']; ?>" src="<?php echo ADMIN_TEMPLATES_URL; ?>/images/tv-expandable.gif">
            <?php } ?></td>
          <td class="handle"><a href="javascript:;" onclick="submit_delete(<?php echo $val['class_id']; ?>)" class="btn red"><i class="fa fa-trash-o"></i>删除</a>
            <?php if ($val['class_parent_id'] == '0') { ?>
            <a href="index.php?act=groupbuy&op=class_add&parent_id=<?php echo $val['class_id']; ?>" class="btn green"><i class="fa fa-plus"></i><?php echo $lang['nc_add_sub_class']; ?></a>
            <?php } ?></td>
          <td class="sort"><span nc_type="class_sort" column_id="<?php echo $val['class_id']; ?>" title="可编辑" class="editable"><?php echo $val['sort'];?></span></td>
          <td class="name"><?php if ($val['class_parent_id'] != '0') { ?>
            <img fieldid="<?php echo $val['class_id'];?>" status="close" nc_type="flex" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/tv-item1.gif">
            <?php } ?>
            <span nc_type="class_name" column_id="<?php echo $val['class_id']; ?>" title="可编辑" class="editable"><?php echo $val['class_name']; ?></span></td>
          <td></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr class="no_data">
          <td class="no-data" colspan="100"><i class="fa fa-exclamation-circle"></i><?php echo $lang['nc_no_record']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.edit.js" charset="utf-8"></script> 
<script type="text/javascript">

$(function() {
    $('.flex-table').flexigrid({
        height: 'auto', // 高度自动
        usepager: false, // 不翻页
        striped: false, // 不使用斑马线
        resizable: false, // 不调节大小
        title: '抢购分类列表', // 表格标题
        reload: false, // 不使用刷新
        columnControl: false, // 不使用列控制
        buttons: [
            {
                display: '<i class="fa fa-plus"></i>新增分类',
                name: 'add',
                bclass: 'add',
                title: '新增分类',
                onpress: function() {
                    location.href = '<?php echo urlAdminShop('groupbuy', 'class_add'); ?>';
                }
            },
            {
                display: '<i class="fa fa-trash"></i>批量删除',
                name: 'del',
                bclass: 'del',
                title: '将选定行数据批量删除',
                onpress: function() {
                    var ids = [];
                    $('.trSelected[data-id]').each(function() {
                        ids.push($(this).attr('data-id'));
                    });
                    if (ids.length < 1) {
                        return false;
                    }
                    submit_delete(ids.join(','));
                }
            }
        ]
    });

    $(".two").hide();
    $(".node_parent").click(function(){
        var node_id = $(this).attr('node_id');
        var state = $(this).attr('state');
        if(state == 'close') {
            $("."+node_id).show();
            $(this).attr('state','open');
            $(this).attr('src',"<?php echo ADMIN_TEMPLATES_URL;?>/images/tv-collapsable.gif");
        }
        else {
            $("."+node_id).hide();
            $(this).attr('state','close');
            $(this).attr('src',"<?php echo ADMIN_TEMPLATES_URL;?>/images/tv-expandable.gif");
        }
    });

    // 行内ajax编辑
    $('span[nc_type="class_sort"]').inline_edit({act: 'groupbuy', op: 'class_sort_update'});
    $('span[nc_type="class_name"]').inline_edit({act: 'groupbuy', op: 'class_name_update'});

});

function submit_delete(id) {
    if (confirm('<?php echo $lang['nc_ensure_del'];?>')) {
        $('#list_form').attr('method','post');
        $('#list_form').attr('action','index.php?act=groupbuy&op=class_drop');
        $('#class_id').val(id);
        $('#list_form').submit();
    }
}

</script> 
