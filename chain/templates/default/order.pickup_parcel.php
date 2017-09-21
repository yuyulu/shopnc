<?php defined('In33hao') or exit('Access Invalid!');?>
<style>
.eject_con .error { font-size: 14px; min-height: 27px; padding: 0px; }
</style>
<div class="eject_con">
  <form method="post" action="<?php echo CHAIN_SITE_URL?>/index.php?act=order&op=pickup_parcel" id="pickup_parcel_form">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="order_id" value="<?php echo $_GET['order_id'];?>">
    <div class="content">
      <div class="order-handle">
        <div class="title">
          <h3>提货验证</h3>
          <?php if ($_GET['payment_code'] == 'chain') { ?>
          <div class="no-pay"><i class="icon-quote-left"></i>该笔尚未付款，需支付<strong><?php echo ncPriceFormat($output['order_info']['order_amount']);?>元</strong>,门店收款后再进行提货验证。<i class="icon-quote-right"></i> </div>
          <?php } ?>
        </div>
        <label>
          <input class="text w200 vm" type="text" maxlength="6" name="pickup_code" placeholder="请输入买家提供的验证码" autocomplete="off">
          <span></span>
          <input type="submit" class="btn" value="提交"/>
        </label>
        <p>该验证码为商城订单生成时，自动发送给收货人手机及买家订单详情中的提供的“6位验证码”。</p>
      </div>
      <div class="order-info">
        <ul class="tabs-nav ">
          <li class="tabs-selected"><a href="javascript:void(0);">收货人信息</a></li>
          <?php if ($output['order_info']['extend_order_common']['invoice_info']) { ?>
          <li class=""><a href="javascript:void(0);">发票信息</a></li>
          <?php } ?>
          <li class=""><a href="javascript:void(0);">订单商品</a></li>
        </ul>
        <div class="tabs-panel">
          <dl>
            <dt>买家姓名：</dt>
            <dd><?php echo $output['order_info']['extend_order_common']['reciver_name'];?></dd>
          </dl>
          <dl>
            <dt>联系电话：</dt>
            <dd><?php echo @$output['order_info']['extend_order_common']['reciver_info']['phone'];?></dd>
          </dl>
          <dl>
            <dt>自提店地址：</dt>
            <dd><?php echo @$output['order_info']['extend_order_common']['reciver_info']['address'];?></dd>
          </dl>
          <?php if ($output['order_info']['extend_order_common']['order_message']) { ?>
          <dl>
            <dt>买家留言：</dt>
            <dd><?php echo $output['order_info']['extend_order_common']['order_message']; ?></dd>
          </dl>
          <?php } ?>
        </div>
        <?php if ($output['order_info']['extend_order_common']['invoice_info']) { ?>
        <div class="tabs-panel tabs-hide">
          <?php foreach ((array)$output['order_info']['extend_order_common']['invoice_info'] as $key => $value){?>
          <dl>
            <dt>发票<?php echo $key;?>：</dt>
            <dd><?php echo $value;?></dd>
          </dl>
          <?php } ?>
        </div>
        <?php } ?>
        <div class="tabs-panel tabs-hide">
          <table class="ncsc-default-table">
            <thead>
              <tr>
                <th class="w10"></th>
                <th colspan="2">商品</th>
                <th class="w150">成交价（元）</th>
                <th class="w120">数量</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($output['order_info']['extend_order_goods'] as $goods_info) { ?>
              <tr>
                <td></td>
                <td class="w60"><img src="<?php echo $goods_info['image_url'] ?>" style="width: 40px; height: 40px;"/></td>
                <td class="tl"><div class="goods-name"> <?php echo $goods_info['goods_name'];?></div>
                  <div class="goods-spec"><?php echo $goods_info['goods_spec'];?></div>
                  <div class="goods-type"><?php echo $goods_info['goods_type'] == 5 ? '赠品' : ''?></div></td>
                <td><em class="goods-price"><?php echo ncPriceFormat($goods_info['goods_price']); ?></em></td>
                <td><?php echo $goods_info['goods_num'];?></td>
              </tr>
              <?php } ?>
            <?php $pinfo = $output['order_info']['extend_order_common']['promotion_info'];?>
            <?php if(!empty($pinfo)){ ?>
            <?php $pinfo = unserialize($pinfo);?>
              <tr>
                <td colspan="20" class="tl">店铺优惠活动：
              <?php if($pinfo == false){ ?>
              <?php echo $output['order_info']['extend_order_common']['promotion_info'];?>
              <?php }elseif (is_array($pinfo)){ ?>
              <?php foreach ($pinfo as $v) {?>
              <dl class="nc-store-sales"><dt><?php echo $v[0];?></dt><dd><?php echo $v[1];?></dd></dl>
              <?php }?>
              <?php }?>
				</td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </form>
</div>
<script>
$(function(){
	setTimeout("takeCount()", 1000);
    //首页Tab标签卡滑门切换
    $(".tabs-nav > li > a").bind('mouseover', (function(e) {
    	if (e.target == this) {
    		var tabs = $(this).parent().parent().children("li");
    		var panels = $(this).parent().parent().parent().children(".tabs-panel");
    		var index = $.inArray(this, $(this).parent().parent().find("a"));
    		if (panels.eq(index)[0]) {
    			tabs.removeClass("tabs-selected").eq(index).addClass("tabs-selected");
    			panels.addClass("tabs-hide").eq(index).removeClass("tabs-hide");
    		}
    	}
    }));

    //input焦点时隐藏/显示填写内容提示信息
    $('#pickup_parcel_form').validate({
        errorPlacement: function(error, element){
            element.next().append(error);
        },
        submitHandler:function(form){
            ajaxpost('pickup_parcel_form', '', '', 'onerror');
        },
        rules : {
            pickup_code : {
                required : true,
                digits : true,
                rangelength : [6,6]
            }
        },
        messages : {
            pickup_code : {
                required : '请输入提货码',
                digits : '请输入正确的提货码',
                rangelength : '请输入正确的提货码'
            }
        }
    });
});
</script>