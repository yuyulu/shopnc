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
          <th width="150" align="left"><?php echo $lang['gadmin_name'];?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <?php foreach($output['list'] as $k => $v){ ?>
        <tr class="hover">
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle">
          <a class="btn red" href="index.php?act=admin&op=gadmin_del&gid=<?php echo $v['gid'];?>" onclick="if(confirm('删除后将不能恢复，确认删除这  1 项吗？')){return true;} else {return false;}"><i class="fa fa-trash-o"></i>删除</a>
          <a class="btn blue" href="index.php?act=admin&op=gadmin_edit&gid=<?php echo $v['gid'];?>"><i class="fa fa-pencil-square-o"></i><?php echo $lang['nc_edit'];?></a>
          </td>
          <td><?php echo $v['gname'];?></td>
          <td></td>
        </tr>
        <?php } ?>
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
	title: '权限组列表',
	buttons : [
               {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', onpress : fg_operation }
           ]
	});

function fg_operation(name, grid) {
    if (name == 'add') {
        window.location.href = 'index.php?act=admin&op=gadmin_add';
    }
}
</script>
