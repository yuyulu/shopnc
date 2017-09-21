<?php defined('In33hao') or exit('Access Invalid!');?>

<table class="flex-table">
  <thead>
    <tr>
      <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
      <th width="60" align="center" class="handle-s">操作</th>
      <th width="60" align="center">序号</th>
      <th width="400" align="left">店铺名称</th>
      <th width="150" align="center"><?php echo $output['sort_text'];?></th>
      <th></th>
    </tr>
  </thead>
  <tbody id="datatable">
    <?php if(!empty($output['statlist'])){ ?>
    <?php foreach ((array)$output['statlist'] as $k=>$v){?>
    <tr>
      <td class="sign"></td>
      <td class="handle-s"><span>--</span></td>
      <td><?php echo $v['sort'];?></td>
      <td><?php echo $v['store_name'];?></td>
      <td><?php if($output['stat_field']=='orderamount'){ ?>
        <?php echo ncPriceFormat($v[$output['stat_field']]);?>
        <?php } else {?>
        <?php echo $v[$output['stat_field']];?>
        <?php } ?></td>
      <td></td>
    </tr>
    <?php } ?>
    <?php } else {?>
    <tr>
      <td class="no-data" colspan="100"><i class="fa fa-exclamation-triangle"></i><?php echo $lang['nc_no_record'];?></td>
    </tr>
    <?php }?>
  </tbody>
</table>
<!-- 飙升榜 -->
<table class="flex-table2">
  <thead>
    <tr>
      <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
      <th width="60" align="center" class="handle-s">操作</th>
      <th width="60" align="center">序号</th>
      <th width="400" align="left">店铺名称</th>
      <th width="150" align="center"><?php echo $output['sort_text'];?></th>
      <th width="150" align="center">升降幅度</th>
      <th></th>
    </tr>
  </thead>
  <tbody id="datatable">
    <?php if(!empty($output['soaring_statlist'])){ ?>
    <?php foreach ((array)$output['soaring_statlist'] as $k=>$v){?>
    <tr>
      <td class="sign"><i class="ico-check"></i></td>
      <td class="handle-s"><span>--</span></td>
      <td><?php echo $v['sort'];?></td>
      <td><?php echo $v['store_name'];?></td>
      <td><?php echo $v[$output['stat_field']];?></td>
      <td><?php echo $v['hb'];?>%</td>
      <td></td>
    </tr>
    <?php } ?>
    <?php } else {?>
    <tr>
      <td class="no-data" colspan="100"><i class="fa fa-exclamation-triangle"></i><?php echo $lang['nc_no_record'];?></td>
    </tr>
    <?php }?>
  </tbody>
</table>
<script>
$(function () {
	//同步加载flexigrid表格
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
		title: '店铺热卖TOP榜'// 表格标题
		});
	$('.flex-table2').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
		title: '店铺热卖飙升榜'// 表格标题
		});

});
</script>