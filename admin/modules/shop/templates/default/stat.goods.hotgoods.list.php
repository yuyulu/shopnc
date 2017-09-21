<?php defined('In33hao') or exit('Access Invalid!');?>

<div id="container_<?php echo $output['stat_field'];?>" style="height:400px"></div>
<table class="flex-table">
  <thead>
    <tr>
      <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
      <th width="60" align="center" class="handle-s">操作</th>
      <th align="center" width="60">序号</th>
      <th align="left" width="400">商品名称</th>
      <th align="center" width="100"><?php echo $output['sort_text'];?></th>
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
      <td><a href='<?php echo urlShop('goods', 'index', array('goods_id' => $v['goods_id']));?>' target="_blank"><?php echo $v['goods_name'];?></a></td>
      <?php if($output['stat_field']=='orderamount'){ ?>
      <td><?php echo ncPriceFormat($v[$output['stat_field']]);?></td>
      <?php } else {?>
      <td><?php echo $v[$output['stat_field']];?></td>
      <?php } ?>
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
		});

	$('#container_<?php echo $output['stat_field'];?>').highcharts(<?php echo $output['stat_json'];?>);
});
</script>