<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_pay_method'];?></h3>
        <h5><?php echo $lang['nc_pay_method_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['payment_help1'];?></li>
    </ul>
  </div>
  <table class="flex-table">
    <thead>
      <tr>
        <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
        <th width="60" align="center" class="handle-s"><?php echo $lang['nc_handle'];?></th>
        <th width="200" align="left"><?php echo $lang['payment_index_name'];?></th>
        <th width="80" align="center">当前状态</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['payment_list']) && is_array($output['payment_list'])){ foreach($output['payment_list'] as $k => $v){ ?>
      <tr>
        <td class="sign"><i class="ico-check"></i></td>
        <td class="handle-s"><a class="btn purple" href="index.php?act=payment&op=edit&payment_id=<?php echo $v['payment_id']; ?>"><i class="fa fa-cog"></i><?php echo $lang['nc_set']?></a></td>
        <td><?php echo $v['payment_name'];?></td>
        <td><?php echo $v['payment_state']==1 ? '<span class="yes"><i class="fa fa-check-circle"></i>'.$lang['payment_index_enable_ing'].'</span>' : '<span class="no"><i class="fa fa-ban"></i>'.$lang['payment_index_disable_ing'].'</span>';?></td>        
        <td></td>
      </tr>
      <?php } } ?>
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
		title: '商城支付方式列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false// 不使用列控制      
		});
});
</script> 
