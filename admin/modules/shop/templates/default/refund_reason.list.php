<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['refund_manage'];?></h3>
        <h5><?php echo $lang['refund_manage_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
    </div>
    <ul>
      <li>系统初始化的原因不能删除</li>
      <li>排序显示规则为排序小的在前，新增的在前</li>
    </ul>
  </div>
  <table class="flex-table">
    <thead>
      <tr>
        <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
        <th width="150" align="center" class="handle"><?php echo $lang['nc_handle'];?></th>
        <th width="300" align="left">排序</th>
        <th width="300" align="left">原因</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['reason_list']) && is_array($output['reason_list'])){ ?>
      <?php foreach($output['reason_list'] as $key => $val){ ?>
      <tr>
        <td class="sign"><i class="ico-check"></i></td>
        <td class="handle">
        <?php if($val['reason_id'] > 99){?>
        <a class="btn red" href="index.php?act=refund&op=del_reason&reason_id=<?php echo $val['reason_id'];?>" onclick="if(confirm('删除后将不能恢复，确认删除这  1 项吗？')){return true;} else {return false;}"><i class="fa fa-trash-o"></i>删除</a>
        <?php } ?>
        <a class="btn blue" href="index.php?act=refund&op=edit_reason&reason_id=<?php echo $val['reason_id'];?>"><i class="fa fa-pencil-square-o"></i><?php echo $lang['nc_edit'];?></a>
        </td>
        <td class="sort"><?php echo $val['sort'];?></td>
        <td><?php echo $val['reason_info'];?></td>
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
$(function(){
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped: true,// 使用斑马线
		resizable: false,// 不调节大小
		title: '<?php echo $lang['nc_list'];?>',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
        title: '退货退款原因列表',
        buttons : [ 
           {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operation }
        ]
		});
});
function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=refund&op=add_reason';
    }
}
</script>
