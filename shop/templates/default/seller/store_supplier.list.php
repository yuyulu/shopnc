<?php defined('In33hao') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script> 
<script type="text/javascript">
$(document).ready(function(){
    $('#add_time_from').datepicker();
    $('#add_time_to').datepicker();
});
</script>
<div class="tabmenu">
    <?php include template('layout/submenu');?>
    <a href="javascript:void(0)" class="ncbtn ncbtn-mint" nc_type="dialog" dialog_title="新增供货商" dialog_id="my_goods_brand_apply" dialog_width="480" uri="index.php?act=store_supplier&op=sup_add">新增供货商</a>
</div>
<div class="alert alert-block mt10">
  <ul>
    <li>供货商信息可与商品关联，商品发布/编辑时可选择供货商，商品列表支持跟据供货商快速查找。</li>
  </ul>
</div>
<table class="search-form">
  <form method="get">
    <input type="hidden" name="act" value="store_supplier">
    <input type="hidden" name="op" value="sup_list">
    <tr>
      <td>&nbsp;</td>
      <th class="w150">供货商名称</th>
      <td class="w160"><input type="text" class="text" name="sup_name" value="<?php echo $_GET['sup_name']; ?>"/></td>
      <td class="w70 tc"><label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>" /></label></td>
    </tr>
  </form>
</table>
<table class="ncsc-default-table">
  <thead>
    <tr>
    <th class="w20">&nbsp;</th>
      <th class="tl">供货商</th>
      <th class="tl">联系人</th>
      <th class="tl">联系电话</th>
      <th class="tl">备注</th>
      <th class="w100">操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if(!empty($output['sp_list'])){ ?>
    <?php foreach($output['sp_list'] as $value){?>
    <tr class="bd-line">
      <td></td>
      <td class="tl"><?php echo $value['sup_name'];?></td>
      <td class="tl"><?php echo $value['sup_man'];?></td>
      <td class="tl"><?php echo $value['sup_phone'];?></td>
      <td class="tl"><?php echo $value['sup_desc'];?></td>
      <td class="nscs-table-handle">
     <span><a href="javascript:void(0)" class="btn-bluejeans" nc_type="dialog" dialog_title="编辑供货商" dialog_id="sup_edit" dialog_width="480" uri="index.php?act=store_supplier&op=sup_add&sup_id=<?php echo $value['sup_id']; ?>"><i class="icon-edit"></i><p><?php echo $lang['nc_edit'];?></p></a></span>
        <span><a href="javascript:void(0)" class="btn-grapefruit" onclick="ajax_get_confirm('<?php echo $lang['nc_ensure_del'];?>', 'index.php?act=store_supplier&op=sup_del&sup_id=<?php echo $value['sup_id']; ?>');"><i class="icon-trash"></i><p><?php echo $lang['nc_del'];?></p></a></span></td>
    </tr>
    <?php }?>
    <?php }else{?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php }?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
  </tfoot>
</table>
