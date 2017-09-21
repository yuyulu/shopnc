<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_microshop_adv_manage'];?></h3>
        <h5><?php echo $lang['nc_microshop_adv_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['microshop_adv_tip1'];?></li>
    </ul>
  </div>
  <div class="flex-table-search">
    <form method="get" name="formSearch">
      <input type="hidden" value="adv" name="act">
      <input type="hidden" value="adv_manage" name="op">
      <div class="sDiv">
        <select name="adv_type" class="select">
          <option value="">全部类型</option>
          <?php if(!empty($output['adv_type_list']) && is_array($output['adv_type_list'])) {?>
          <?php foreach($output['adv_type_list'] as $key=>$value) {?>
          <option value="<?php echo $key;?>" <?php if($key==$_GET['adv_type']) {echo 'selected';}?>><?php echo $value;?></option>
          <?php } ?>
          <?php } ?>
        </select>
        <input type="text" value="<?php echo $_GET['adv_name'];?>" name="adv_name" class="qsbox" placeholder="搜索相关数据...">
        <a href="javascript:document.formSearch.submit();" class="btn"><?php echo $lang['nc_search'];?></a></div>
    </form>
  </div>
  <form id="list_form" method='post'>
    <input id="adv_id" name="adv_id" type="hidden" />
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" align="center" class="handle"><?php echo $lang['nc_handle'];?></th>
          <th width="60" align="center"><?php echo $lang['nc_sort'];?></th>
          <th width="60" align="center">广告图片</th>
          <th width="300"><?php echo $lang['microshop_adv_name'];?></th>
          <th width="300">广告链接</th>
          <th width="150" align="center"><?php echo $lang['microshop_adv_type'];?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <?php foreach($output['list'] as $val){ ?>
        <tr data-id="<?php echo $val['adv_id']; ?>">
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle"><a href="index.php?act=adv&op=adv_drop&adv_id=<?php echo $val['adv_id'];?>" class="btn red confirm-del"><i class="fa fa-trash-o"></i><?php echo $lang['nc_del'];?></a><a href="index.php?act=adv&op=adv_edit&adv_id=<?php echo $val['adv_id'];?>" class="btn blue"><i class="fa fa-pencil-square-o"></i><?php echo $lang['nc_edit'];?></a></td>
          <td class="sort"><span nc_type="adv_sort" column_id="<?php echo $val['adv_id'];?>" title="<?php echo $lang['nc_editable'];?>" class="editable "><?php echo $val['adv_sort'];?></span>
          <td><a href="javascript:;" class="pic-thumb-tip" onmouseout="toolTip()" onmouseover="toolTip('<img src=\'<?php echo UPLOAD_SITE_URL.DS.ATTACH_MICROSHOP.DS.'adv'.DS. $val['adv_image']; ?>\'>')"> <i class='fa fa-picture-o'></i> </a></td>
          <td><?php echo $val['adv_name'];?></td>
          <td><?php echo $val['adv_url']; ?></td>
          <td><?php echo $output['adv_type_list'][$val['adv_type']];?></td>
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
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript">
$(function(){
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		title: '广告列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
        buttons : [
                   {display: '<i class="fa fa-plus"></i>新增广告', name : 'add', bclass : 'add', title : '新增广告', onpress : fg_operation },
				   {display: '<i class="fa fa-trash"></i>批量删除', name : 'del', bclass : 'del', title : '将选定行数据批量删除', onpress : function() {
                    var ids = [];
                    $('.trSelected[data-id]').each(function() {
                        ids.push($(this).attr('data-id'));
                    });
                    if (ids.length < 1 || !confirm('确定删除?')) {
                        return false;
                    }
                    location.href = 'index.php?act=adv&op=adv_drop&adv_id=__IDS__'.replace('__IDS__', ids.join(','));
                    } }
               ]
		});

    //行内ajax编辑
    $('span[nc_type="adv_sort"]').inline_edit({act: 'adv',op: 'adv_sort_update'});

    $('a.confirm-del').live('click', function() {
        if (!confirm('确定删除？')) {
            return false;
        }
    });

});
function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=adv&op=adv_add';
    }
}
</script>