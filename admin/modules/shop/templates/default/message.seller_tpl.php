<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_message_set'];?></h3>
        <h5><?php echo $lang['nc_message_set_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>平台可给商家提供站内信、手机短信、邮件三种通知方式。平台可以选择开启一种或多种通知方式供商家选择。</li>
      <li>开启强制接收后，商家不能取消该方式通知的接收。</li>
      <li>短消息、邮件需要商家设置正确号码后才能正常接收。</li>
    </ul>
  </div>
  <form name='form1' method='post'>
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="submit_type" id="submit_type" value="" />
    <table class="flex-table">
      <thead>        
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="60" align="center" class="handle-s"><?php echo $lang['nc_handle'];?></th>
          <th width="300" align="left"><?php echo $lang['mailtemplates_index_desc'];?></th>
          <th width="100" align="center">站内信</th>
          <th width="100" align="center">手机短信</th>
          <th width="100" align="center">邮件</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['mstpl_list'])){?>
        <?php foreach($output['mstpl_list'] as $val){?>
        <tr>
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle-s"><a class="btn blue" href="<?php echo urlAdminShop('message', 'seller_tpl_edit', array('code' => $val['smt_code']));?>"><i class="fa fa-pencil-square-o"></i><?php echo $lang['nc_edit'];?></a></td>
          <td><?php echo $val['smt_name']; ?></td>
          <td><?php echo ($val['smt_message_switch']) ? '<span class="on"><i class="fa fa-toggle-on"></i>开启</span>' : '<span class="off"><i class="fa fa-toggle-off"></i>关闭</span>';?></td>
          <td><?php echo ($val['smt_short_switch']) ? '<span class="on"><i class="fa fa-toggle-on"></i>开启</span>' : '<span class="off"><i class="fa fa-toggle-off"></i>关闭</span>';?></td>
          <td><?php echo ($val['smt_mail_switch']) ? '<span class="on"><i class="fa fa-toggle-on"></i>开启</span>' : '<span class="off"><i class="fa fa-toggle-off"></i>关闭</span>';?></td>          
          <td></td>
        </tr>
        <?php } ?>
        <?php } ?>
      </tbody>
    </table>
  </form>
</div>
<script>
$(function(){
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped: true,// 使用斑马线
		resizable: false,// 不调节大小
		title: '商家消息模板列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false// 不使用列控制      
		});
});
</script> 