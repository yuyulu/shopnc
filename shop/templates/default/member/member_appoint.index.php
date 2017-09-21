<?php defined('In33hao') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <table class="ncm-default-table order">
    <thead>
      <tr>
        <th class="w10"></th>
        <th class="w100" >商品SKU</th>
        <th>商品名称</th>
        <th class="w200">添加时间</th>
        <th class="w60">操作</th>
      </tr>
    </thead>
    <?php if (!empty($output['appoint_list'])) { ?>
    <?php foreach ($output['appoint_list'] as $val) { ?>
    <tbody>
      <tr>
        <td></td>
        <td><?php echo $val['goods_id'];?></td>
        <td><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['goods_id']));?>" target="_blank"><?php echo $val['goods_name'];?></a></td>
        <td><?php echo date('Y-m-d H:i:s', $val['an_addtime']);?></td>
        <td class="ncm-table-handle"><span><a class="btn-grapefruit" onclick="ajax_get_confirm('您确定要取消预约/到货通知吗？', 'index.php?act=member_appoint&op=del_appoint&id=<?php echo $val['an_id'];?>');" href="javascript:void(0);"><i class="icon-trash"></i><p>删&nbsp;除</p></a></span></td>
      </tr>
    </tbody>
    <?php }?>
    <?php } else { ?>
    <tbody>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
    </tbody>
    <?php } ?>
    <?php if($output['appoint_list']) { ?>
    <tfoot>
      <tr>
        <td colspan="19"><div class="pagination"> <?php echo $output['show_page']; ?> </div></td>
      </tr>
    </tfoot>
    <?php } ?>
  </table>
</div>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/sns.js" ></script>
<script type="text/javascript">
$(function(){
    $('#query_start_date').datepicker({dateFormat: 'yy-mm-dd'});
    $('#query_end_date').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>
