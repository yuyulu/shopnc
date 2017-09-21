<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>消息模板</h3>
        <h5>商城对邮件/手机类消息模板设定</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>系统自动给注册用户发送邮件或手机短信等信息所使用的模板，可根据需求编辑其中内容。</li>
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
          <th width="400" align="left"><?php echo $lang['mailtemplates_index_desc'];?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(is_array($output['templates_list']) && !empty($output['templates_list'])){?>
        <?php foreach($output['templates_list'] as $k => $v){?>
        <tr>
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle-s"><a class="btn blue" href="index.php?act=message&op=email_tpl_edit&code=<?php echo $v['code']; ?>"><i class="fa fa-pencil-square-o"></i><?php echo $lang['nc_edit'];?></a></td>
          <td><?php echo $v['name']; ?></td>
          <td></td>
        </tr>
        <?php } ?>
        <?php } ?>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.edit.js" charset="utf-8"></script> 
<script type="text/javascript">
function go(){
	var url="index.php?act=message&op=email_tpl_ajax";
	document.form1.action = url;
	document.form1.submit();
}
</script> 
<script>
$(function(){
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped: true,// 使用斑马线
		resizable: false,// 不调节大小
		title: '邮件/短信模板列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false// 不使用列控制      
		});
});
</script>