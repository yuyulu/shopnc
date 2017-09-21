<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<table class="ncsc-default-table">
  <thead>
    <tr>
      <th class="w30"></th>
      <th class="tl">标题</th>
      <th class="w200">发布时间</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['msg_list'])) { ?>
    <?php foreach($output['msg_list'] as $v) { ?>
    <tr class="bd-line">
      <td class="tc"></td>
      <td class="tl">
        <a target="_blank" <?php if($v['article_url']!=''){?>target="_blank"<?php }?> href="<?php if($v['article_url']!='') echo $v['article_url'];else echo urlMember('article', 'show', array('article_id'=>$v['article_id']));?>"><?php echo $v['article_title']?></a>
      </td>
      <td><?php echo date("Y-m-d H:i:s",$v['article_time']); ?></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php if (!empty($output['msg_list'])) { ?>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
<script>
$(function(){
    $('a[nc_type="dialog"]').click(function(){
        $(this).parents('tr:first').children('.tl').removeClass('fb dark');
    });
});
</script>