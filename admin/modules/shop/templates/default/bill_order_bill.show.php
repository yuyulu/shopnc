<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="<?php echo getReferer();?>" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>结算管理 - 账单明细 </h3>
        <h5>实物商品订单结算索引及商家账单表</h5>
      </div>
    </div>
  </div>
  <?php if (floatval($output['bill_info']['ob_order_book_totals']) > 0) { ?>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>未退定金金额是预定订单中已经被取消，但系统未退定金的总金额</li>
      <li>默认未退定金金额会累加到平台应付金额中</li>
    </ul>
  </div>
  <?php } ?>
  <div class="ncap-form-default">
    <div class="title">
      <h3>店铺 - <?php echo $output['bill_info']['ob_store_name'];?>（ID：<?php echo $output['bill_info']['ob_store_id'];?>） 结算单</h3>
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
      <dd class="opt"><?php echo ncPriceFormat($output['bill_info']['ob_result_totals']);?> = <?php echo ncPriceFormat($output['bill_info']['ob_order_totals']);?> (订单金额) - <?php echo ncPriceFormat($output['bill_info']['ob_commis_totals']);?> (佣金金额) - <?php echo ncPriceFormat($output['bill_info']['ob_order_return_totals']);?> (退单金额) + <?php echo ncPriceFormat($output['bill_info']['ob_commis_return_totals']);?> (退还佣金) - <?php echo ncPriceFormat($output['bill_info']['ob_store_cost_totals']);?> (店铺促销费用)
      <?php if (floatval($output['bill_info']['ob_order_book_totals']) > 0) { ?>
      + <?php echo ncPriceFormat($output['bill_info']['ob_order_book_totals']);?> (未退定金金额)
      <?php } ?>
      <?php if (floatval($output['bill_info']['ob_rpt_amount']) > 0) { ?>
      + <?php echo ncPriceFormat($output['bill_info']['ob_rpt_amount']);?> (平台红包)
      <?php } ?>
      <?php if (floatval($output['bill_info']['ob_rf_rpt_amount']) > 0) { ?>
      - <?php echo ncPriceFormat($output['bill_info']['ob_rf_rpt_amount']);?> (全部退款时应扣除的平台红包)
      <?php } ?>
      </dd>
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
      <a class="ncap-btn-big ncap-btn-green mr10" onclick="if (confirm('审核后将无法撤销，进入下一步付款环节，确认审核吗?')){return true;}else{return false;}" href="index.php?act=bill&op=bill_check&ob_id=<?php echo $_GET['ob_id'];?>">审核</a>
      <?php }elseif ($output['bill_info']['ob_state'] == BILL_STATE_SYSTEM_CHECK){?>
      <a class="ncap-btn-big ncap-btn-blue mr10" href="index.php?act=bill&op=bill_pay&ob_id=<?php echo $_GET['ob_id'];?>"><?php echo $lang['nc_exdport'];?>付款完成</a>
      <?php }elseif ($output['bill_info']['ob_state'] == BILL_STATE_SUCCESS){?>
      <a class="ncap-btn-big" target="_blank" href="index.php?act=bill&op=bill_print&ob_id=<?php echo $_GET['ob_id'];?>">打印</a>
      <?php }?>
    </div>
  </div>
  <div class="homepage-focus" nctype="sellerTplContent">
    <div class="title">
      <ul class="tab-base nc-row">
        <li><a href="index.php?<?php echo $_SERVER['QUERY_STRING'];?>&query_type=order" class="<?php echo ($_GET['query_type'] == '' || $_GET['query_type'] == 'order') ? 'current' : '';?>">订单列表</a></li>
        <li><a href="index.php?<?php echo $_SERVER['QUERY_STRING'];?>&query_type=refund" class="<?php echo $_GET['query_type'] == 'refund' ? 'current' : '';?>">退单列表</a></li>
        <li><a href="index.php?<?php echo $_SERVER['QUERY_STRING'];?>&query_type=cost" class="<?php echo $_GET['query_type'] == 'cost' ? 'current' : '';?>">店铺费用</a></li>
        <?php if (floatval($output['bill_info']['ob_order_book_totals']) > 0) { ?>
        <li><a href="index.php?<?php echo $_SERVER['QUERY_STRING'];?>&query_type=book" class="<?php echo $_GET['query_type'] == 'book' ? 'current' : '';?>">未退定金</a></li>
        <?php } ?>
      </ul>
    </div>
    <?php include template($output['tpl_name']);?>
  </div>
</div>
