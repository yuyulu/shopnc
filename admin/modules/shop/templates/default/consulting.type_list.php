<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['consulting_index_manage'];?></h3>
        <h5><?php echo $lang['consulting_index_manage_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>在商品详细页,提交商品咨询是所需要选择的咨询类型。</li>
      <li>提交咨询时，咨询类型必须选择，请不要全部删除。</li>
    </ul>
  </div>
  <table class="flex-table">
    <thead>
      <tr>
        <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
        <th width="150" align="center" class="handle"><?php echo $lang['nc_handle'];?></th>
        <th width="100" align="center">排序</th>
        <th width="300" align="left">咨询类型名称</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    <?php if(!empty($output['type_list'])){ ?>
      <?php foreach($output['type_list'] as $value){ ?>
      <tr>
        <td class="sign"><i class="ico-check"></i></td>
        <td class="handle"><a class="btn red" href="<?php echo urlAdminShop('consulting', 'type_del', array('ct_id' => $value['ct_id']));?>" onclick="if(confirm('删除后将不能恢复，确认删除这  1 项吗？')){return true;} else {return false;}"><i class="fa fa-trash-o"></i>删除</a> <a class="btn blue" href="<?php echo urlAdminShop('consulting', 'type_edit', array('ct_id' => $value['ct_id']));?>"><i class="fa fa-pencil-square-o"></i><?php echo $lang['nc_edit'];?></a></td>
        <td><?php echo $value['ct_sort'];?></td>
        <td><?php echo $value['ct_name'];?></td>
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
</div>
<script>
	$('.flex-table').flexigrid({	
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped: true,// 使用斑马线
		resizable: false,// 不调节大小
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制 
		title: '咨询类型列表',
		buttons : [
                   {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', onpress : fg_operation }
               ]
		});

    function fg_operation(name, grid) {
        if (name == 'add') {
            window.location.href = 'index.php?act=consulting&op=type_add';
        }
    }
</script> 
