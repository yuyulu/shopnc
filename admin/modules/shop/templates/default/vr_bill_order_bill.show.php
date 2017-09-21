<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="<?php echo getReferer();?>" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>结算管理 - 账单明细</h3>
        <h5>虚拟商品订单结算索引及商家账单表</h5>
      </div>
    </div>
  </div>
  <div class="ncap-form-default">
    <div class="title">
      <h3>店铺 - <?php echo $output['bill_info']['ob_store_name'];?>（ID：<?php echo $output['bill_info']['ob_store_id'];?>） <?php echo $output['bill_info']['os_month'];?> 期 结算单&emsp;</h3>
    </div>
    <dl class="row">
      <dt class="tit"><?php echo $lang['order_time_from'];?>结算单号</dt>
      <dd class="opt"><?php echo $output['bill_info']['ob_id'];?>&emsp;<?php echo $output['bill_info']['ob_no'] ? '(原结算单号：'.$output['bill_info']['ob_no'].')' : null;?></dd>
    </dl>
    <dl class="row">
      <dt class="tit">起止日期</dt>
      <dd class="opt"><?php echo date('Y-m-d',$output['bill_info']['ob_start_date']);?> &nbsp;至&nbsp; <?php echo date('Y-m-d',$output['bill_info']['ob_end_date']);?></dd>
    </dl>
    <dl class="row">
      <dt class="tit">出账日期</dt>
      <dd class="opt"><?php echo date('Y-m-d',$output['bill_info']['ob_create_date']);?></dd>
    </dl>
    <dl class="row">
      <dt class="tit">平台应付金额</dt>
      <dd class="opt"><?php echo ncPriceFormat($output['bill_info']['ob_result_totals']);?> = <?php echo ncPriceFormat($output['bill_info']['ob_order_totals']);?> (消费金额) - <?php echo ncPriceFormat($output['bill_info']['ob_commis_totals']);?> (佣金金额)</dd>
    </dl>
    <dl class="row">
      <dt class="tit">结算状态</dt>
      <dd class="opt"><?php echo billState($output['bill_info']['ob_state']);?>
        <?php if ($output['bill_info']['ob_state'] == BILL_STATE_SUCCESS){?>
        &emsp;结算日期<?php echo $lang['nc_colon'];?><?php echo date('Y-m-d',$output['bill_info']['ob_pay_date']);?>，结算备注<?php echo $lang['nc_colon'];?><?php echo $output['bill_info']['ob_pay_content'];?>
        <?php }?>
      </dd>
    </dl>
    <div class="bot">
      <?php if ($output['bill_info']['ob_state'] == BILL_STATE_STORE_COFIRM){?>
      <a class="ncap-btn-big ncap-btn-green mr10" onclick="if (confirm('审核后将无法撤销，进入下一步付款环节，确认审核吗?')){return true;}else{return false;}" href="index.php?act=vr_bill&op=bill_check&ob_id=<?php echo $_GET['ob_id'];?>">审核</a>
      <?php }elseif ($output['bill_info']['ob_state'] == BILL_STATE_SYSTEM_CHECK){?>
      <a class="ncap-btn-big ncap-btn-blue mr10" href="index.php?act=vr_bill&op=bill_pay&ob_id=<?php echo $_GET['ob_id'];?>">付款完成</a>
      <?php }elseif ($output['bill_info']['ob_state'] == BILL_STATE_SUCCESS){?>
      <a class="ncap-btn-big ncap-btn-green" target="_blank" href="index.php?act=vr_bill&op=bill_print&ob_id=<?php echo $_GET['ob_id'];?>">打印</a>
      <?php }?>
    </div>
  </div>
  <div class="homepage-focus" nctype="sellerTplContent">
    <div class="title">
      <ul class="tab-base nc-row">
        <li><a class="<?php echo $_GET['query_type'] == '' ? 'current' : '';?>" href="index.php?act=vr_bill&op=show_bill&ob_id=<?php echo $_GET['ob_id'];?>&query_type=">已使用</a></li>
        <li><a class="<?php echo $_GET['query_type'] == 'timeout' ? 'current' : '';?>" href="index.php?act=vr_bill&op=show_bill&ob_id=<?php echo $_GET['ob_id'];?>&query_type=timeout">已过期</a></li>
      </ul>
    </div>
    <?php include template($output['tpl_name']);?>
  </div>
</div>
<script type="text/javascript">
$(function(){
    $('#ncsubmit').click(function(){
    	$('input[name="op"]').val('show_bill');$('#formSearch').submit();
    });
});
</script> 
