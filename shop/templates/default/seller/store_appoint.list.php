<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
<table class="ncsc-default-table">
  <thead>
    <tr nc_type="table_header">
      <th class="w30">&nbsp;</th>
      <th>商品SKU</th>
      <th>商品名称</th>
      <th>会员名称</th>
      <th>添加时间</th>
      <th class="w120"><?php echo $lang['nc_handle'];?></th>
    </tr>
    <?php if (!empty($output['goods_list'])) { ?>
    <tr>
      <td class="tc"><input type="checkbox" id="all" class="checkall"/></td>
      <td colspan="20"><label for="all" ><?php echo $lang['nc_select_all'];?></label>
        <a href="javascript:void(0);" class="ncbtn-mini" nc_type="batchbutton" uri="<?php echo urlShop('store_goods_online', 'drop_goods');?>" name="commonid" confirm="<?php echo $lang['nc_ensure_del'];?>"><i class="icon-trash"></i><?php echo $lang['nc_del'];?></a> <a href="javascript:void(0);" class="ncbtn-mini" nc_type="batchbutton" uri="<?php echo urlShop('store_goods_online', 'goods_unshow');?>" name="commonid"><i class="icon-level-down"></i><?php echo $lang['store_goods_index_unshow'];?></a> <a href="javascript:void(0);" class="ncbtn-mini" nctype="batch" data-param="{url:'<?php echo urlShop('store_goods_online', 'edit_jingle');?>', sign:'jingle'}"><i></i>设置广告词</a> <a href="javascript:void(0);" class="ncbtn-mini" nctype="batch" data-param="{url:'<?php echo urlShop('store_goods_online', 'edit_plate');?>', sign:'plate'}"><i></i>设置关联版式</a></td>
    </tr>
    <?php } ?>
  </thead>
  <tbody>
    <?php if (!empty($output['appoint_list'])) { ?>
    <?php foreach ($output['appoint_list'] as $val) { ?>
    <tr>
      <td></td>
      <td><?php echo $val['goods_id'];?></td>
      <td><?php echo $val['goods_name'];?></td>
      <td><?php echo $output['member_list'][$val['member_id']]['member_name'];?></td>
      <td><?php echo date('Y-m-d H:i:s', $val['an_addtime']);?></td>
      <td class="nscs-table-handle"><span><a class="btn-grapefruit" onclick="ajax_get_confirm('您确定要取消预约/到货通知吗？', 'index.php?act=store_appoint&op=del_appoint&id=<?php echo $val['an_id'];?>');" href="javascript:void(0);"><i class="icon-trash"></i><p>删除</p></a></span></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php  if (!empty($output['appoint_list'])) { ?>
    <tr>
      <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?> </div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script> 
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/store_goods_list.js"></script> 
<script>
$(function(){
    //Ajax提示
    $('.tip').poshytip({
        className: 'tip-yellowsimple',
        showTimeout: 1,
        alignTo: 'target',
        alignX: 'center',
        alignY: 'top',
        offsetY: 5,
        allowTipHover: false
    });
    $('a[nctype="batch"]').click(function(){
        if($('.checkitem:checked').length == 0){    //没有选择
        	showDialog('请选择需要操作的记录');
            return false;
        }
        var _items = '';
        $('.checkitem:checked').each(function(){
            _items += $(this).val() + ',';
        });
        _items = _items.substr(0, (_items.length - 1));

        var data_str = '';
        eval('data_str = ' + $(this).attr('data-param'));

        if (data_str.sign == 'jingle') {
            ajax_form('ajax_jingle', '设置广告词', data_str.url + '&commonid=' + _items + '&inajax=1', '480');
        } else if (data_str.sign == 'plate') {
            ajax_form('ajax_plate', '设置关联版式', data_str.url + '&commonid=' + _items + '&inajax=1', '480');
        }
    });
});
</script>
</div>