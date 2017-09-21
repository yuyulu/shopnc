<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['complain_manage_title'];?></h3>
        <h5><?php echo $lang['complain_manage_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <table class="flex-table">
    <thead>
      <tr>
        <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
        <th width="60" align="center" class="handle-s"><?php echo $lang['nc_handle'];?></th>
        <th width="200" align="left"><?php echo $lang['complain_subject_content'];?></th>
        <th width="400" align="left"><?php echo $lang['complain_subject_desc'];?></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
      <?php foreach($output['list'] as $v){ ?>
      <tr class="hover">
        <td class="sign"><i class="ico-check"></i></td>
        <td class="handle-s"><a class="btn red" href="<?php echo urlAdminShop('complain', 'complain_subject_drop', array('complain_subject_id' => $v['complain_subject_id']));?>" onclick="if(confirm('删除后将不能恢复，确认删除这  1 项吗？')){return true;} else {return false;}"><i class="fa fa-trash-o"></i>删除</a></td>
        <td><?php echo $v['complain_subject_content'];?></td>
        <td><?php echo $v['complain_subject_desc'];?></td>
        <td></td>
        <?php } ?>
        <?php }else { ?>
        <tr>
          <td class="no-data" colspan="100"><i class="fa fa-exclamation-triangle"></i><?php echo $lang['nc_no_record'];?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<script type="text/javascript">
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped: true,// 使用斑马线
		resizable: false,// 不调节大小
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制 
		title: '投诉主题列表',// 表格标题     
		buttons : [
                   {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', onpress : fg_operation }
               ]
		});

    function fg_operation(name, grid) {
        if (name == 'add') {
            window.location.href = 'index.php?act=complain&op=complain_subject_add';
        }
    }
</script>