<?php defined('In33hao') or exit('Access Invalid!');?>
  <table class="ncsc-default-table">
    <thead>
        <tr>
            <th class="w250">代金券编码</th>
            <?php if($output['t_info']['voucher_t_gettype'] == 2){ ?>
            <th class="w250">卡密</th>
            <?php } ?>
            <th class="">使用状态</th>
            <th class="">所属会员</th>
            <th class="">领取时间</th>
        </tr>
    </thead>
    <tbody>
    <?php if (count($output['voucher_list'])>0) { ?>
        <?php foreach($output['voucher_list'] as $val) { ?>
            <tr class="bd-line">
                <td><?php echo $val['voucher_code'];?></td>
                <?php if($output['t_info']['voucher_t_gettype'] == 2){ ?>
                <td><?php echo $val['voucher_pwd'];?></td>
                <?php } ?>
                <td><?php echo $val['voucher_state_text'];?></td>
                <td><?php echo $val['voucher_owner_name']?$val['voucher_owner_name']:"<font style='color: #5BB75B;'>未领取</font>";?></td>
                <td><?php echo $val['voucher_active_date'];?></td>
            </tr>
        <?php }?>
    <?php } else { ?>
        <tr>
            <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
        </tr>
    <?php } ?>
    </tbody>
    <tfoot>
      <?php  if (count($output['voucher_list'])>0) { ?>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>

<script type="text/javascript">
$(document).ready(function(){
    $('#voucher_list_div').find('.demo').ajaxContent({
        event:'click', //mouseover
        loaderType:"img",
        loadingMsg:"<?php echo SHOP_TEMPLATES_URL;?>/images/transparent.gif",
        target:'#voucher_list_div'
    });

    <?php if (count($output['voucher_list'])>0) { ?>
    $("#voucher_exportbtn").show();
    <?php } ?>
});
</script>
