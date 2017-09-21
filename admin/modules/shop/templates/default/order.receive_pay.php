<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="javascript:history.back(-1)" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['order_manage'];?></h3>
        <h5><?php echo $lang['order_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>提交后，相同支付单号的未支付订单状态都变为已支付状态</li>
    </ul>
  </div>
  <form method="post" name="form1" id="form1" action="index.php?act=<?php echo $_GET['act'];?>&op=change_state&state_type=receive_pay&order_id=<?php echo intval($_GET['order_id']);?>">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" value="<?php echo getReferer();?>" name="ref_url">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="site_name">订单编号</label>
        </dt>
        <dd class="opt"><?php echo $output['order_info']['order_sn'];?> 
        <?php if ($output['order_info']['order_type'] == 2) echo '[预定]';?>
		<?php if ($output['order_info']['order_type'] == 3) echo '[门店自提]';?>
        </dd>
      </dl>
      <?php if ($_GET['act'] == 'order') { ?>
      <dl class="row">
        <dt class="tit">
          <label for="site_name">支付单号</label>
        </dt>
        <dd class="opt"><?php echo $output['order_info']['pay_sn'];?>
        </dd>
      </dl>
      <?php } ?>
      <dl class="row">
        <dt class="tit">
          <label for="site_name">订单总金额 </label>
        </dt>
        <dd class="opt"><?php echo ncPriceFormat($output['order_info']['order_amount']);?>
        </dd>
      </dl>
      <?php if ($output['order_info']['order_type'] == 2) { ?>
      <dl class="row">
        <dt class="tit">
          <label for="site_name">订单进度 </label>
        </dt>
        <dd class="opt">
        <?php foreach ($output['order_info']['book_list'] as $book_info) { ?>
            <?php echo $book_info['book_step'];?>，
                                应付金额：<?php echo $book_info['book_amount'];?>，
                                支付方式：<?php echo $book_info['book_pay_name'];?>，
                                支付充值卡：<?php echo ncPriceFormat($book_info['book_rcb_amount']);?>，
                                支付预存款：<?php echo ncPriceFormat($book_info['book_pd_amount']);?>，
                                支付交易号：<?php echo $book_info['book_trade_no'];?>，
                                支付时间：
            <?php if ($book_info['book_pay_time']) { ?>
            <?php echo !intval(date('His',$book_info['book_pay_time'])) ? date('Y-m-d',$book_info['book_pay_time']) : date('Y-m-d H:i:s',$book_info['book_pay_time']);?>
            <?php } ?>，
                                备注：<?php echo $book_info['book_state'];?><br/>
        <?php } ?>
        </dd>
      </dl>
      <?php } ?>
      <dl class="row">
        <dt class="tit">
          <label for="site_name">付款时间</label>
        </dt>
        <dd class="opt">
          <input readonly id="payment_time" class="" name="payment_time" value="" type="text" />
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="site_name">付款方式 </label>
        </dt>
        <dd class="opt">
          <select name="payment_code" class="s-select">
            <option value=""><?php echo $lang['nc_please_choose'];?></option>
            <?php foreach($output['payment_list'] as $val) { ?>
            <option value="<?php echo $val['payment_code']; ?>"><?php echo $val['payment_name']; ?></option>
            <?php } ?>
          </select>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="closed_reason">第三方支付平台交易号</label>
        </dt>
        <dd class="opt">
          <input type="text" class="txt2" name="trade_no" id="trade_no" maxlength="40">
          <span class="err"></span>
          <p class="notic"><span class="vatop rowform">支付宝等第三方支付平台交易号</span></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" id="ncsubmit" class="ncap-btn-big ncap-btn-green"><?php echo $lang['nc_submit'];?></a> </div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('#payment_time').datepicker({dateFormat: 'yy-mm-dd',maxDate: '<?php echo date('Y-m-d',TIMESTAMP);?>'});
    $('#ncsubmit').click(function(){
    	if($("#form1").valid()){
        	if (confirm("操作提醒：<?php echo $output['order_info']['order_state'] == ORDER_STATE_CANCEL ? '\n该订单处于关闭状态':'';?>\n该操作不可撤销\n提交前请务必确认是否已收到付款\n继续操作吗?")){
        	}else{
        		return false;
        	}
        	$('#form1').submit();
    	}
    });
	$("#form1").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
        	payment_time : {
                required : true
            },
            payment_code : {
                required : true
            },
            trade_no    :{
                required : true
            }       
        },
        messages : {
        	payment_time : {
                required : '<i class="fa fa-exclamation-circle"></i>请填写付款准确时间'
            },
            payment_code : {
                required : '<i class="fa fa-exclamation-circle"></i>请选择付款方式'
            },
            trade_no : {
                required : '<i class="fa fa-exclamation-circle"></i>请填写第三方支付平台交易号'
            }
        }
	});
});
</script> 