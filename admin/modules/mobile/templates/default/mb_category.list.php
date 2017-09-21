<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['link_index_mb_category'];?></h3>
        <h5><?php echo $lang['link_index_mb_category_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['link_help1'];?></li>
    </ul>
  </div>
  <form method='post' id="form_link">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" align="center" class="handle"><?php echo $lang['nc_handle'];?></th>
          <th width="300"><?php echo $lang['link_index_title'];?></th>
          <th width="60" align="center"><?php echo $lang['link_index_pic_sign'];?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['link_list']) && is_array($output['link_list'])){ ?>
        <?php foreach($output['link_list'] as $k => $v){ ?>
        <tr class="edit">
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle"><a href="javascript:if(confirm('<?php echo $lang['nc_ensure_del'];?>'))window.location = 'index.php?act=mb_category&op=mb_category_del&gc_id=<?php echo $v['gc_id'];?>';" class="btn red"><i class="fa fa-trash-o"></i><?php echo $lang['nc_del'];?></a><a href="index.php?act=mb_category&op=mb_category_edit&gc_id=<?php echo $v['gc_id'];?>" class="btn blue"><i class="fa fa-pencil-square-o"></i><?php echo $lang['nc_edit'];?></a></td>
          <td><?php echo $output['goods_class'][$v['gc_id']]['gc_name'];?></td>
          <td>
<?php if ($v['gc_thumb'] != '') { ?>
            <a class="pic-thumb-tip" onmouseover="toolTip('<img src=<?php echo $v['gc_thumb']; ?>>')" onmouseout="toolTip()" href="javascript:void(0);"> <i class="fa fa-picture-o"></i></a>
<?php } ?>
          </td>
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
		title: '移动客户端分类图片列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
        buttons : [
                   {display: '<i class="fa fa-plus"></i>新增分类图片', name : 'add', bclass : 'add', title : '新增分类图片', onpress : fg_operation }
               ]
		});

    });

	function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=mb_category&op=mb_category_add';
    }
}
</script>