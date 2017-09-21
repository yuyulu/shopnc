<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_binding_manage'];?></h3>
        <h5><?php echo $lang['nc_binding_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['shareset_list_tip'];?></li>
    </ul>
  </div>
  <table class="flex-table">
    <thead>
      <tr>
        <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
        <th width="60" align="center" class="handle-s"><?php echo $lang['nc_handle'];?></th>
        <th width="150" align="center"><?php echo $lang['shareset_list_appname'];?></th>
        <th width="300" align="left"><?php echo $lang['shareset_list_appurl'];?></th>
        <th width="100" align="center"><?php echo $lang['shareset_list_appstate'];?></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['app_arr']) && is_array($output['app_arr'])){ foreach($output['app_arr'] as $k => $v){ ?>
      <tr>
        <td class="sign"><i class="ico-check"></i></td>
        <td class="handle-s">
          <a class="btn blue" href="index.php?act=sns_sharesetting&op=edit&state=1&key=<?php echo $k; ?>"><i class="fa fa-pencil-square-o"></i> <?php echo $lang['nc_edit']?></a></td>
        <td><?php echo $v['name'];?></td>
        <td><?php echo $v['url'];?></td>
        <td><?php if($v['isuse'] == '1'){ ?>
          <span class="yes"><i class="fa fa-check-circle"></i>启用</span>
          <?php }else { ?>
          <span class="no"><i class="fa fa-ban"></i>关闭</span>
          <?php } ?></td>
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
		title: '站外分享列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false// 不使用列控制      
		});
});
</script>