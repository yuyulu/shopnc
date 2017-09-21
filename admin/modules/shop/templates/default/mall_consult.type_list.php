<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>平台客服</h3>
        <h5>商城对用户咨询类型设定与处理</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="<?php echo urlAdminShop('mall_consult', 'index');?>">平台客服咨询列表</a></li>
        <li><a href="JavaScript:void(0);" class="current">平台咨询类型</a></li>
      </ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>买家联系平台客服时所需要选择的类型。</li>
      <li>提交咨询时，咨询类型必须选择，请不要全部删除。</li>
    </ul>
  </div>
  <form method="post" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" align="center" class="handle">操作</th>
          <th width="100">排序</th>
          <th width="200" align="left">咨询类型名称</th>
          <th width="200" align="left">咨询类型备注</th>
          <th></th>
        </tr>
      </thead>
      <?php if(!empty($output['type_list'])){ ?>
      <?php foreach($output['type_list'] as $value){ ?>
      <tbody>
        <tr>
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle"><a class="btn red" href="<?php echo urlAdminShop('mall_consult', 'type_del', array('mct_id' => $value['mct_id']));?>" onclick="if(confirm('删除后将不能恢复，确认删除这  1 项吗？')){return true;} else {return false;}"><i class="fa fa-trash-o"></i>删除</a><a class="btn blue" href="<?php echo urlAdminShop('mall_consult', 'type_edit', array('mct_id' => $value['mct_id']));?>"><i class="fa fa-pencil-square-o"></i>编辑</a></td>
          <td><?php echo $value['mct_sort'];?></td>
          <td><?php echo $value['mct_name'];?></td>
          <td><?php echo $value['mct_introduce'];?></td>
          <td></td>
        </tr>
      <?php }?>
      <?php }else{?>
        <tr>
          <td class="no-data" colspan="100"><i class="fa fa-exclamation-triangle"></i><?php echo $lang['nc_no_record'];?></td>
        </tr>
      <?php }?>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript">
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped: true,// 使用斑马线
		resizable: false,// 不调节大小
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制 
		title: '平台咨询类型列表',// 表格标题     
		buttons : [
                   {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', onpress : fg_operation }
               ]
		});

    function fg_operation(name, grid) {
        if (name == 'add') {
            window.location.href = 'index.php?act=mall_consult&op=type_add';
        }
    }
</script> 
