<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>商家入驻</h3>
        <h5>开店招商及商家开店申请页面内容管理</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="index.php?act=store_joinin&op=edit_info"><?php echo '入驻首页';?></a></li>
        <li><a href="JavaScript:void(0);" class="current"><?php echo '入驻指南';?></a></li>
      </ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>入驻指南会出现在招商首页的最下方，以切换卡形式出现，可编辑但不可删减数量。</li>
      <li>排序显示规则为排序小的在前。</li>
    </ul>
  </div>
  <table class="flex-table">
    <thead>
      <tr>
        <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
        <th width="60" class="handle-s" align="center"><?php echo $lang['nc_handle'];?></th>
        <th width="80" align="center"><?php echo $lang['nc_sort'];?></th>
        <th width="300" align="left">标题</th>
        <th width="300" align="left">更新时间</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['help_list']) && is_array($output['help_list'])){ ?>
      <?php foreach($output['help_list'] as $key => $val){ ?>
      <tr data-id="<?php echo $val['help_id'];?>">
        <td class="sign"><i class="ico-check"></i></td>
        <td class="handle-s"><a class="btn blue" href="index.php?act=store_joinin&op=edit_help&help_id=<?php echo $val['help_id'];?>"><i class="fa fa-pencil-square-o"></i><?php echo $lang['nc_edit'];?></a></td>
        <td><?php echo $val['help_sort'];?></td>
        <td><?php echo $val['help_title'];?></td>
        <td><?php echo date('Y-m-d H:i:s',$val['update_time']);?></td>
        <td></td>
      </tr>
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
$(function(){
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		title: '入驻指南列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false// 不使用列控制
		});
});

</script>