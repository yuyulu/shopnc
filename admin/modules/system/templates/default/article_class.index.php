<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['article_class_index_class'];?></h3>
        <h5><?php echo $lang['article_class_index_class_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['article_class_index_help1'];?></li>
      <li><?php echo $lang['article_class_index_help2'];?></li>
    </ul>
  </div>
  <form method='post'>
    <input type="hidden" name="form_submit" value="ok" />
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" align="center" class="handle"><?php echo $lang['nc_handle'];?></th>
          <th width="60" align="center"><?php echo $lang['nc_sort'];?></th>
          <th width="350" align="left"><?php echo $lang['article_class_index_name'];?></th>
          <th></th>
        </tr>
      </thead>
      <tbody id="treet1">
        <?php if(!empty($output['class_list']) && is_array($output['class_list'])){ ?>
        <?php foreach($output['class_list'] as $k => $v){ ?>
        <tr <?php if ($v['deep'] != 1) {?>style="display:none;"<?php }?> nctype="<?php echo $v['ac_parent_id'];?>">
          <td class="sign">
            <?php if($v['have_child'] == '1'){ ?>
            <img src="<?php echo ADMIN_TEMPLATES_URL;?>/images/tv-expandable.gif" fieldid="<?php echo $v['ac_id'];?>" status="open" nc_type="flex">
            <?php }else{ ?>
            <img fieldid="<?php echo $v['ac_id'];?>" status="close" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/tv-item.gif">
            <?php } ?></td>
          <td class="handle">
            <?php if($v['ac_code'] == ''){?><a href="javascript:if(confirm('<?php echo $lang['article_class_index_ensure_del'];?>'))window.location = 'index.php?act=article_class&op=article_class_del&ac_id=<?php echo $v['ac_id'];?>';" class="btn red"><i class="fa fa-trash-o"></i><?php echo $lang['nc_del'];?></a>
            <?php }?>
            <span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em>
            <ul><li><a href="index.php?act=article_class&op=article_class_edit&ac_id=<?php echo $v['ac_id'];?>">编辑分类信息</a></li>
              <?php if ($v['deep'] == 1) {?>
              <li><a href="index.php?act=article_class&op=article_class_add&ac_parent_id=<?php echo $v['ac_id'];?>"><?php echo $lang['nc_add_sub_class'];?>分类</a></li>
              <?php }?>
            </ul>
            </span>
            </td>
          <td class="sort"><span title="<?php echo $lang['nc_editable'];?>" ajax_branch='article_class_sort' datatype="number" fieldid="<?php echo $v['ac_id'];?>" fieldname="ac_sort" nc_type="inline_edit" class="editable"><?php echo $v['ac_sort'];?></span></td>
          <td class="name"><span title="<?php echo $lang['nc_editable'];?>" required="1" fieldid="<?php echo $v['ac_id'];?>" ajax_branch='article_class_name' fieldname="ac_name" nc_type="inline_edit" class="editable "><?php echo $v['ac_name'];?></span></td>
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
                   {display: '<i class="fa fa-plus"></i>新增分类', name : 'add', bclass : 'add', title : '新增分类', onpress : fg_operation }
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
        window.location.href = 'index.php?act=article_class&op=article_class_add';
    }
}
</script> 
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.edit.js" charset="utf-8"></script> 
