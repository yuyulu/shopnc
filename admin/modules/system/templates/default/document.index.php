<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['document_index_document'];?></h3>
        <h5><?php echo $lang['document_index_document_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['document_index_help1'];?></li>
    </ul>
  </div>
  <table class="flex-table">
    <thead>
      <tr>
        <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
        <th width="60" class="handle-s" align="center"><?php echo $lang['nc_handle'];?></th>
        <th width="300" align="left"><?php echo $lang['document_index_title'];?></th>
        <th width="300" align="left"><?php echo $lang['document_edit_time'];?></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['doc_list']) && is_array($output['doc_list'])){ ?>
      <?php foreach($output['doc_list'] as $k => $v){ ?>
      <tr>
        <td class="sign"><i class="ico-check"></i></td>
        <td class="handle-s"><a class="btn blue" href="index.php?act=document&op=edit&doc_id=<?php echo $v['doc_id']; ?>"><i class="fa fa-pencil-square-o"></i><?php echo $lang['nc_edit'];?></a></td>
        <td><?php echo $v['doc_title']; ?></td>
        <td><?php echo date('Y-m-d H:i:s',$v['doc_time']); ?></td>
        <td></td>
      </tr>
      <?php } ?>
      <?php }else { ?>
      <tr class="no-data">
        <td colspan="100" class="no-data"><i class="fa fa-lightbulb-o"></i><?php echo $lang['nc_no_record'];?></td>
      </tr>
      <?php } ?>
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
		title: '<?php echo $lang['nc_list'];?>',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false// 不使用列控制      
		});
});
</script>