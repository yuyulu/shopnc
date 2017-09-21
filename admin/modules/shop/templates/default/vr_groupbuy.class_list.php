<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>虚拟抢购</h3>
        <h5>虚拟商品抢购促销活动相关设定及管理</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="javascript:;" class="current">分类管理</span></a></li>
        <li><a href="index.php?act=vr_groupbuy&op=area_list">区域管理</a></li>
      </ul>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>商家发布虚拟商品的抢购时，需要选择虚拟抢购所属分类</li>
      <li>通过修改排序数字可以控制前台线下商城分类的显示顺序，数字越小越靠前</li>
      <li>可以对分类名称进行修改,可以新增下级分类</li>
      <li>可以对分类进行编辑、删除操作</li>
      <li>点击行首的"+"号，可以展开下级分类</li>
    </ul>
  </div>
  <form method='post' id="list_form">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="submit_type" id="submit_type" value="" />
    <input type="hidden" name="class_id" id="class_id">
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
        <?php if ($val['parent_class_id']==0) { ?>
        <tr class="hover edit" data-id="<?php echo $val['class_id']; ?>">
          <td class="sign"><img class="class_parent" class_id="<?php echo 'class_id'.$val['class_id'];?>" status="open" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/tv-expandable.gif"></td>
          <td class="handle"><a href="javascript:;" onclick="submit_delete(<?php echo $val['class_id']; ?>)" class="btn red"><i class="fa fa-trash-o"></i>删除</a> <span class="btn"> <em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em>
            <ul>
              <li><a href="index.php?act=vr_groupbuy&op=class_edit&class_id=<?php echo $val['class_id']; ?>">编辑分类</a></li>
              <li><a href="index.php?act=vr_groupbuy&op=class_add&parent_class_id=<?php echo $val['class_id']; ?>">新增下级</a></li>
            </ul>
            </span></td>
          <td class="sort"><span nc_type="class_sort" column_id="<?php echo $val['class_id']; ?>" title="可编辑" class="editable"><?php echo $val['class_sort'];?></span></td>
          <td class="name"><span nc_type="class_name" column_id="<?php echo $val['class_id']; ?>" title="可编辑" class="editable"><?php echo $val['class_name']; ?></span></td>
          <td></td>
        </tr>
        <?php foreach($output['list'] as $val1) { ?>
        <?php if ($val1['parent_class_id'] == $val['class_id']) { ?>
        <tr class="<?php echo 'class_id'.$val['class_id'];?>" style="display:none;" data-id="<?php echo $val1['class_id']; ?>">
          <td></td>
          <td class="handle"><a href="javascript:;" onclick="submit_delete(<?php echo $val1['class_id']; ?>)" class="btn red"><i class="fa fa-trash-o"></i>删除</a> <a href="index.php?act=vr_groupbuy&op=class_edit&class_id=<?php echo $val1['class_id']; ?>" class="btn blue"><i class="fa fa-pencil-square-o"></i>编辑</a></td>
          <td class="sort"><span nc_type="class_sort" column_id="<?php echo $val1['class_id']; ?>" title="可编辑" class="editable"><?php echo $val1['class_sort'];?></span></td>
          <td class="name"><span nc_type="class_name" column_id="<?php echo $val1['class_id']; ?>" title="可编辑" class="editable"><?php echo $val1['class_name']; ?></span></td>
          <td></td>
        </tr>
        <?php } ?>
        <?php } ?>
        <?php } ?>
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
        title: '虚拟抢购分类列表', // 表格标题
        reload: false, // 不使用刷新
        columnControl: false, // 不使用列控制
        buttons: [
            {
                display: '<i class="fa fa-plus"></i>新增分类',
                name: 'add',
                bclass: 'add',
                title: '新增分类',
                onpress: function() {
                    location.href = '<?php echo urlAdminShop('vr_groupbuy', 'class_add'); ?>';
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

    $(".class_parent").click(function() {
        if ($(this).attr("status") == "open") {
            $(this).attr("status","close");
            $(this).attr("src","<?php echo ADMIN_TEMPLATES_URL;?>/images/tv-collapsable.gif");
            $("."+$(this).attr("class_id")).show();
        } else {
            $(this).attr("status","open");
            $(this).attr("src","<?php echo ADMIN_TEMPLATES_URL;?>/images/tv-expandable.gif");
            $("."+$(this).attr("class_id")).hide();
        }
    });

    //行内ajax编辑
    $('span[nc_type="class_sort"]').inline_edit({act: 'vr_groupbuy', op: 'update_class_sort'});
    $('span[nc_type="class_name"]').inline_edit({act: 'vr_groupbuy', op: 'update_class_name'});

});

function submit_delete(id) {
    if (confirm('<?php echo $lang['nc_ensure_del']; ?>')) {
        $('#list_form').attr('method','post');
        $('#list_form').attr('action','index.php?act=vr_groupbuy&op=class_del');
        $('#class_id').val(id);
        $('#list_form').submit();
    }
}

</script> 
