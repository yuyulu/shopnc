<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>首页推荐词设置</h3>
        <h5>首页猜你喜欢的链接推荐设置</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>首页推荐词设置后，将显示在首页的大幻灯下。</li>
      <li>设置的推荐词必须添加链接方可正常使用。</li>
    </ul>
  </div>
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" align="center" class="handle"><?php echo $lang['nc_handle'];?></th>
          <th width="200" align="left">推荐词名称</th>
          <th width="200" align="left">推荐词链接</th>
          <th width="200" align="left">是否高亮</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['rc_list']) && is_array($output['rc_list'])){ ?>
        <?php foreach($output['rc_list'] as $k => $v){ ?>
        <tr class="hover">
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle">
          <a class="btn red" href="index.php?act=hao&op=rc_del&id=<?php echo $k;?>" onclick="if(confirm('删除后将不能恢复，确认删除这  1 项吗？')){return true;} else {return false;}"><i class="fa fa-trash-o"></i>删除</a>
          <a class="btn blue" mff="sqde" href="index.php?act=hao&op=rc_edit&id=<?php echo $k; ?>"><i class="fa fa-pencil-square-o"></i>编辑</a>
          </td>
          <td><?php echo $v['value']; ?></td>
          <td><?php echo $v['name'];?></td>
          <td><?php echo $v['is_blod']==2? '<span class="no"><i class="fa fa-ban"></i>否</span>' : '<span class="yes"><i class="fa fa-check-circle"></i>是</span>';?></td>
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
$('.flex-table').flexigrid({	
	height:'auto',// 高度自动
	usepager: false,// 不翻页
	striped: true,// 使用斑马线
	resizable: false,// 不调节大小
	reload: false,// 不使用刷新
	columnControl: false,// 不使用列控制 
	title: '首页推荐词列表',
	buttons : [
               {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', onpress : fg_operation }
           ]
	});

function fg_operation(name, grid) {
    if (name == 'add') {
        window.location.href = 'index.php?act=hao&op=rc_add';
    }
}
</script>