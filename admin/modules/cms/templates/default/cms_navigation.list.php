<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_cms_navigation_manage'];?></h3>
        <h5><?php echo $lang['nc_cms_navigation_manage_subhead'];?></h5>
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
    <input id="navigation_id" name="navigation_id" type="hidden" />
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="60" align="center" class="handle-s"><?php echo $lang['nc_handle'];?></th>
          <th width="60" align="center"><?php echo $lang['nc_sort'];?></th>
          <th width="300"><?php echo $lang['cms_navigation_name'];?></th>
          <th width="300"><?php echo $lang['cms_navigation_url'];?></th>
          <th width="100" align="center"><?php echo $lang['cms_navigation_open_type'];?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <?php foreach($output['list'] as $val){ ?>
        <tr data-id="<?php echo $val['navigation_id']; ?>">
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle-s"><a href="index.php?act=cms_navigation&op=cms_navigation_drop&navigation_id=<?php echo $val['navigation_id'];?>" class="btn red confirm-del"><i class="fa fa-trash-o"></i><?php echo $lang['nc_del'];?></a></td>
          <td class="sort"><span nc_type="navigation_sort" column_id="<?php echo $val['navigation_id'];?>" title="<?php echo $lang['nc_editable'];?>" class="editable "><?php echo $val['navigation_sort'];?></span>
          <td class="name"><span nc_type="navigation_title" column_id="<?php echo $val['navigation_id'];?>" title="<?php echo $lang['nc_editable'];?>" class="editable "><?php echo $val['navigation_title'];?></span></td>
          <td class="name"><span nc_type="navigation_link" column_id="<?php echo $val['navigation_id'];?>" title="<?php echo $lang['nc_editable'];?>" class="editable "><?php echo $val['navigation_link'];?></span></td>
          <td class="yes-onoff"><a href="JavaScript:void(0);" class=" <?php echo $val['navigation_open_type']? 'enabled':'disabled'?>" ajax_branch='navigation_open_type'  nc_type="inline_edit" fieldname="navigation_open_type" fieldid="<?php echo $val['navigation_id']?>" fieldvalue="<?php echo $val['navigation_open_type']?'1':'0'?>" title="<?php echo $lang['nc_editable'];?>"><img src="<?php echo ADMIN_TEMPLATES_URL;?>/images/transparent.gif"></a></td>
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
        title: '导航列表',// 表格标题
        reload: false,// 不使用刷新
        columnControl: false,// 不使用列控制
        buttons : [
            {
                display: '<i class="fa fa-plus"></i>新增导航',
                name : 'add',
                bclass : 'add',
                title : '新增导航',
                onpress : fg_operation
            },
            {
                display: '<i class="fa fa-trash"></i>批量删除',
                name : 'del',
                bclass : 'del',
                title : '将选定行数据批量删除',
                onpress : function() {
                    var ids = [];
                    $('.trSelected[data-id]').each(function() {
                        ids.push($(this).attr('data-id'));
                    });
                    if (ids.length < 1 || !confirm('确定删除?')) {
                        return false;
                    }
                    location.href = 'index.php?act=cms_navigation&op=cms_navigation_drop&navigation_id=__IDS__'.replace('__IDS__', ids.join(','));
                }
            }
        ]
    });


    //行内ajax编辑
    $('span[nc_type="navigation_sort"]').inline_edit({act: 'cms_navigation',op: 'update_navigation_sort'});
    $('span[nc_type="navigation_title"]').inline_edit({act: 'cms_navigation',op: 'update_navigation_title'});
    $('span[nc_type="navigation_link"]').inline_edit({act: 'cms_navigation',op: 'update_navigation_link'});

    $('a.confirm-del').live('click', function() {
        if (!confirm('确定删除？')) {
            return false;
        }
    });

});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=cms_navigation&op=cms_navigation_add';
    }
}
</script>