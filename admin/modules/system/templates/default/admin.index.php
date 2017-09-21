<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_limit_manage'];?></h3>
        <h5><?php echo $lang['nc_limit_manage_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" align="center" class="handle"><?php echo $lang['nc_handle'];?></th>
          <th width="100" align="left"><?php echo $lang['admin_index_username'];?></th>
          <th width="120" align="center"><?php echo $lang['admin_index_last_login'];?></th>
          <th width="60" align="center"><?php echo $lang['admin_index_login_times'];?></th>
          <th width="120" align="center"><?php echo $lang['gadmin_name'];?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['admin_list']) && is_array($output['admin_list'])){ ?>
        <?php foreach($output['admin_list'] as $k => $v){ ?>
        <tr class="hover">
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle">
          <?php if ($v['admin_is_super'] != 1){?>
          <a class="btn red" href="index.php?act=admin&op=admin_del&admin_id=<?php echo $v['admin_id'];?>" onclick="if(confirm('删除后将不能恢复，确认删除这  1 项吗？')){return true;} else {return false;}"><i class="fa fa-trash-o"></i>删除</a>
          <a class="btn blue" href="index.php?act=admin&op=admin_edit&admin_id=<?php echo $v['admin_id']; ?>"><i class="fa fa-pencil-square-o"></i><?php echo $lang['nc_edit'];?></a>
          <?php } else { ?>
          <span>--</span>
          <?php } ?>
          </td>
          <td><?php echo $v['admin_name'];?></td>
          <td><?php echo $v['admin_login_time'] ? date('Y-m-d H:i:s',$v['admin_login_time']) : $lang['admin_index_login_null']; ?></td>
          <td><?php echo $v['admin_login_num']; ?></td>
          <td><?php echo $v['gname']; ?></td>
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
<script>
$('.flex-table').flexigrid({	
	height:'auto',// 高度自动
	usepager: false,// 不翻页
	striped: true,// 使用斑马线
	resizable: false,// 不调节大小
	reload: false,// 不使用刷新
	columnControl: false,// 不使用列控制 
	title: '管理员列表',
	buttons : [
               {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', onpress : fg_operation }
           ]
	});

function fg_operation(name, grid) {
    if (name == 'add') {
        window.location.href = 'index.php?act=admin&op=admin_add';
    }
}
</script>