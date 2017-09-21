<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>搜索设置</h3>
        <h5>热搜词与默认词设置</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>热门搜索词设置后，将显示在前台搜索框作为默认值 随机出现，最多可设置10个热搜词。</li>
      <li>每个热搜词包括搜索词和显示词两部分，搜索词参于搜索，显示词不参于搜索，只起显示作用。</li>
    </ul>
  </div>
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" align="center" class="handle"><?php echo $lang['nc_handle'];?></th>
          <th width="200" align="left">搜索词</th>
          <th width="200" align="left">显示词</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['search_list']) && is_array($output['search_list'])){ ?>
        <?php foreach($output['search_list'] as $k => $v){ ?>
        <tr class="hover">
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle">
          <a class="btn red" href="index.php?act=search&op=hot_del&id=<?php echo $k;?>" onclick="if(confirm('删除后将不能恢复，确认删除这  1 项吗？')){return true;} else {return false;}"><i class="fa fa-trash-o"></i>删除</a>
          <a class="btn blue" href="index.php?act=search&op=hot_edit&id=<?php echo $k; ?>"><i class="fa fa-pencil-square-o"></i>编辑</a>
          </td>
          <td><?php echo $v['value']; ?></td>
          <td><?php echo $v['name'];?></td>
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
	title: '热门搜索词列表',
	buttons : [
               {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', onpress : fg_operation }
           ]
	});

function fg_operation(name, grid) {
    if (name == 'add') {
        window.location.href = 'index.php?act=search&op=hot_add';
    }
}
</script>