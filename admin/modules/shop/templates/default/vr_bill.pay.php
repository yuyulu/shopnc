<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="javascript:history.back(-1)" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>结算管理 - 账单付款</h3>
        <h5>虚拟商品订单结算索引及商家账单表</h5>
      </div>
    </div>
  </div>
  <form method="post" name="form1" id="form1" action="index.php?act=vr_bill&op=bill_pay&ob_id=<?php echo $_GET['ob_id'];?>">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>账单编号</label>
        </dt>
        <dd class="opt">
          <?php echo $_GET['ob_id'];?>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>付款日期</label>
        </dt>
        <dd class="opt">
          <input readonly id="pay_date" class="" name="pay_date" value="" type="text" />
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>付款备注</label>
        </dt>
        <dd class="opt">
          <textarea name="pay_content" rows="6" class="tarea" id="pay_content"></textarea>
          <span class="err"></span>
          <p class="notic">请输入汇款单号、支付方式等付款凭证</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" id="ncsubmit" class="ncap-btn-big ncap-btn-green"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('#pay_date').datepicker({dateFormat:'yy-mm-dd',maxDate: '<?php echo date('Y-m-d',TIMESTAMP);?>'});
    $('#ncsubmit').click(function(){
    	if ($('#pay_date').val() == '') return false;
    	if (confirm("操作提醒：\n该操作不可撤销\n提交前请务必确认店铺是否已收到付款\n继续操作吗?")){
    	}else{
    		return false;
    	}
    	$('#form1').submit();
    });
});
</script> 