<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="javascript:history.back(-1)" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>虚拟订单退款 - 处理退款“退单编号：<?php echo $output['refund']['refund_sn']; ?>”</h3>
        <h5>虚拟类商品订单退款申请及审核处理</h5>
      </div>
    </div>
  </div>
  <form id="post_form" method="post" action="index.php?act=vr_refund&op=edit&refund_id=<?php echo $output['refund']['refund_id']; ?>">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <div class="title">
        <h3>买家退款申请</h3>
      </div>
      <dl class="row">
        <dt class="tit">申请时间</dt>
        <dd class="opt"><?php echo date('Y-m-d H:i:s',$output['refund']['add_time']); ?> </dd>
      </dl>
      <dl class="row">
        <dt class="tit">商品名称</dt>
        <dd class="opt"><a href="<?php echo urlShop('goods','index',array('goods_id'=> $output['refund']['goods_id']));?>" target="_blank"><?php echo $output['refund']['goods_name']; ?></a> </dd>
      </dl>
      <dl class="row">
        <dt class="tit">兑换码</dt>
        <dd class="opt">
          <?php if (is_array($output['code_array']) && !empty($output['code_array'])) { ?>
          <?php foreach ($output['code_array'] as $key => $val) { ?>
          <?php echo $val;?><br />
          <?php } ?>
          <?php } ?>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['refund_order_refund'];?></dt>
        <dd class="opt"><?php echo ncPriceFormat($output['refund']['refund_amount']); ?> 
            <span id="pay_amount">
            <?php if ($output['detail_array']['pay_time'] > 0) { ?>
            (已完成在线退款金额 <?php echo ncPriceFormat($output['detail_array']['pay_amount']); ?>)
            <?php } ?>
            </span>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">退款说明</dt>
        <dd class="opt"><?php echo $output['refund']['buyer_message']; ?> </dd>
      </dl>
      <div class="title">
        <h3>订单支付信息</h3>
      </div>
      <dl class="row">
        <dt class="tit">支付方式</dt>
        <dd class="opt"><?php echo orderPaymentName($output['order']['payment_code']);?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">订单总额</dt>
        <dd class="opt"><?php echo ncPriceFormat($output['order']['order_amount']);?></dd>
      </dl>
      <?php if ($output['order']['refund_amount'] > 0) { ?>
      <dl class="row">
        <dt class="tit">已退款金额</dt>
        <dd class="opt"><?php echo ncPriceFormat($output['order']['refund_amount']);?></dd>
      </dl>
      <?php } ?>
      <?php if ($output['order']['pay_amount'] > 0) { ?>
      <dl class="row">
        <dt class="tit">在线支付金额</dt>
        <dd class="opt"><?php echo ncPriceFormat($output['order']['pay_amount']);?></dd>
      </dl>
          <?php if ($output['detail_array']['pay_time'] == 0 && $output['order']['pay_amount'] > $output['order']['refund_amount']) { ?>
          <?php if (in_array($output['detail_array']['refund_code'],array('wxpay','wx_jsapi','wx_saoma'))) { ?>
          <dl class="row">
            <dt class="tit"></dt>
            <dd class="opt"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="wxpayBtn">确认微信退款</a></dd>
          </dl>
          <?php } ?>
          <?php if ($output['detail_array']['refund_code'] == 'alipay') { ?>
          <dl class="row">
            <dt class="tit"></dt>
            <dd class="opt">
                <a href="<?php echo ADMIN_SITE_URL;?>/index.php?act=vr_refund&op=alipay&refund_id=<?php echo $output['refund']['refund_id']; ?>" target="_blank">支付宝退款</a>  
                <a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="alipayBtn">退款查询</a>
            </dd>
          </dl>
          <?php } ?>
          <?php } ?>
      <?php } ?>
      <div class="title">
        <h3>平台退款审核</h3>
      </div>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>是否同意</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="state1" class="cb-enable" title="<?php echo $lang['nc_yes'];?>"><?php echo $lang['nc_yes'];?></label>
            <label for="state0" class="cb-disable" title="<?php echo $lang['nc_no'];?>"><?php echo $lang['nc_no'];?></label>
            <input id="state1" name="admin_state"  value="2" type="radio">
            <input id="state0" name="admin_state" value="3" type="radio">
          </div>
          <span class="err"></span>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['refund_message'];?></label>
        </dt>
        <dd class="opt">
          <textarea id="admin_message" name="admin_message" class="tarea"></textarea>
          <span class="err"></span> 
          <p class="notic">系统默认退款到“站内余额”，如果“在线退款”到原支付账号，建议在备注里说明，方便核对。</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a> </div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/refund.js"></script>
<script type="text/javascript">
$(function(){
    $('.nyroModal').nyroModal();
	$("#submitBtn").click(function(){
        if($("#post_form").valid()){
            if(confirm('提交后将不能恢复，确认吗？')) $("#post_form").submit();
    	}
	});
	$("#wxpayBtn").click(function(){
	    var ajaxurl = '<?php echo ADMIN_SITE_URL;?>/index.php?act=vr_refund&op=wxpay&refund_id=<?php echo $output['refund']['refund_id']; ?>';
		show_msg(ajaxurl);
	});
	$("#alipayBtn").click(function(){
	    var ajaxurl = '<?php echo ADMIN_SITE_URL;?>/index.php?act=vr_refund&op=get_detail&refund_id=<?php echo $output['refund']['refund_id']; ?>';
		show_msg(ajaxurl);
	});
    $('#post_form').validate({
		errorPlacement: function(error, element){
			var error_td = element.parentsUntil('dl').children('span.err');
            error_td.append(error);
        },
        rules : {
            admin_state : {
                required   : true
            },
            admin_message : {
                required   : true
            }
        },
        messages : {
            admin_state : {
                required : '<i class="fa fa-exclamation-circle"></i>请选择是否同意退款'
            },
            admin_message  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['refund_message_null'];?>'
            }
        }
    });
});
</script>