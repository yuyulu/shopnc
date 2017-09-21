<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['store_grade'];?></h3>
        <h5><?php echo $lang['store_grade_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="flex-table-search">
    <form method="post" name="formSearch">
      <div class="sDiv">
        <input type="text" value="<?php echo $output['like_sg_name'];?>" name="like_sg_name" id="like_sg_name"  class="qsbox" placeholder="<?php echo $lang['store_grade_name'];?>...">
        <a href="javascript:document.formSearch.submit();" class="btn"><?php echo $lang['nc_search'];?></a></div>
    </form>
  </div>
  </form>
  <form id="form_grade" method='post' name="">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" class="handle" align="center"><?php echo $lang['nc_handle'];?></th>
          <th width="80" align="center"><?php echo $lang['grade_sortname']; ?></th>
          <th width="120" align="center"><?php echo $lang['store_grade_name'];?></th>
          <th width="120" align="center"><?php echo $lang['allow_pubilsh_product_num'];?></th>
          <th width="120" align="center"><?php echo $lang['allow_upload_album_num'];?></th>
          <th width="120" align="center"><?php echo $lang['optional_template_num'];?></th>
          <th width="120" align="center"><?php echo $lang['charges_standard'];?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['grade_list']) && is_array($output['grade_list'])){ ?>
        <?php foreach($output['grade_list'] as $k => $v){ ?>
        <tr>
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle"><?php if($v['sg_id'] == '1'){ ?>
            <a class="btn" href="javascript:void(0);" title="<?php echo $lang['default_store_grade_no_del'];?>"><i class="fa fa-unlock-alt"></i>默认</a>
            <?php }else { ?>
            <a class="btn red" href="javascript:if(confirm('<?php echo $lang['problem_del'];?>'))window.location = 'index.php?act=store_grade&op=store_grade_del&sg_id=<?php echo $v['sg_id'];?>';"><i class="fa fa-trash-o"></i><?php echo $lang['nc_del'];?></a>
            <?php } ?>
            <span class="btn"><em><i class="fa fa-cog"></i><?php echo $lang['nc_set'];?><i class="arrow"></i></em>
            <ul>
              <li><a href="index.php?act=store_grade&op=store_grade_edit&sg_id=<?php echo $v['sg_id'];?>">编辑选项</a></li>
              <li><a href="index.php?act=store_grade&op=store_grade_templates&sg_id=<?php echo $v['sg_id'];?>">选择模板</a></li>
            </ul>
            </span></td>
          <td><?php echo $v['sg_sort'];?></td>
          <td><?php echo $v['sg_name'];?></td>
          <td><?php echo $v['sg_goods_limit'];?></td>
          <td><?php echo $v['sg_album_limit'];?></td>
          <td><?php echo $v['sg_template_number'];?></td>
          <td><?php echo $v['sg_price'];?> 元/年</td>
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
  </form>
</div>
<script>
$(function(){
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		title: '店铺等级列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
        buttons : [
                   {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', onpress : fg_operation }
               ]
		});
});

    function fg_operation(name, grid) {
        if (name == 'add') {
            window.location.href = 'index.php?act=store_grade&op=store_grade_add';
        }
    }
</script> 