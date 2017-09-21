<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu'); ?>
    </div>
  <table class="ncm-default-table">
    <thead>
      <tr>
        <th class="w10"></th>
        <th class="w150 tl">记录时间</th>
        <th class="w150 tl">金额</th>
        <th class="tl">备注</th>
      </tr>
    </thead>
    <tbody>
      <?php  if (!empty($output['consume_list'])) { ?>
      <?php foreach($output['consume_list'] as $val) { ?>
      <tr class="bd-line">
        <td></td>
        <td class="w150 tl"><?php echo date('Y-m-d H:i:s', $val['consume_time']);?></td>
        <td class="w150 tl"><?php echo '&yen;'.ncPriceFormat($val['consume_amount'])?></td>
        <td class="tl"><?php echo $val['consume_remark'];?></td>
      </tr>
      <?php } ?>
      <?php } else {?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php  if (count($output['consume_list'])>0) { ?>
      <tr>
        <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?></div></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
