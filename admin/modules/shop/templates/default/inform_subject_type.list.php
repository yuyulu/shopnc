<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['inform_manage_title'];?></h3>
        <h5><?php echo $lang['inform_manage_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['inform_help4'];?></li>
    </ul>
  </div>
  <table class="flex-table">
    <thead>
      <tr>
        <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
        <th width="60" align="center" class="handle-s"><?php echo $lang['nc_handle'];?></th>
        <th width="200" align="left"><?php echo $lang['inform_type'];?></th>
        <th width="400" align="left"><?php echo $lang['inform_type_desc'];?></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
      <?php foreach($output['list'] as $v){ ?>
      <tr class="hover">
        <td class="sign"><i class="ico-check"></i></td>
        <td class="handle-s"><a class="btn red" href="index.php?act=inform&op=inform_subject_type_drop&inform_type_id=<?php echo $v['inform_type_id'];?>" onclick="if(confirm('删除后将不能恢复，确认删除这  1 项吗？')){return true;} else {return false;}"><i class="fa fa-trash-o"></i>删除</a></td>
        <td><?php echo $v['inform_type_name'];?></td>
        <td><?php echo $v['inform_type_desc'];?></td>
        <td></td>
        <?php } ?>
        <?php }else { ?>
      <tr class="no_data">
        <td class="no-data" colspan="100"><i class="fa fa-exclamation-circle"></i><?php echo $lang['nc_no_record']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<script>
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped: true,// 使用斑马线
		resizable: false,// 不调节大小		
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制 
		title: '举报类型列表',// 表格标题     
		buttons : [
                   {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', onpress : fg_operation }
               ]
		});

    function fg_operation(name, grid) {
        if (name == 'add') {
            window.location.href = 'index.php?act=inform&op=inform_subject_type_add';
        }
    }
</script> 
