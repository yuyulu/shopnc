<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>运营设置</h3>
        <h5>各个运营模块的相关设置</h5>
      </div>
    </div>
  </div>
  <form method="post" name="settingForm" id="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">消费者保障服务</dt>
        <dd class="opt">
          <div class="onoff">
            <label for="contract_allow_1" class="cb-enable <?php if($output['list_setting']['contract_allow'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><?php echo $lang['open'];?></label>
            <label for="contract_allow_0" class="cb-disable <?php if($output['list_setting']['contract_allow'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><?php echo $lang['close'];?></label>
            <input id="contract_allow_1" name="contract_allow" <?php if($output['list_setting']['contract_allow'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="contract_allow_0" name="contract_allow" <?php if($output['list_setting']['contract_allow'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic">消费者保障服务开启后，店铺可以申请加入保障服务，为消费者提供商品筛选依据</p>
        </dd>
      </dl>
      <!-- 促销开启 -->
      <dl class="row">
        <dt class="tit">
          <label>物流自提服务站</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="delivery_isuse1" class="cb-enable <?php if($output['list_setting']['delivery_isuse'] == '1'){ ?>selected<?php } ?>" ><span>开启</span></label>
            <label for="delivery_isuse0" class="cb-disable <?php if($output['list_setting']['delivery_isuse'] == '0'){ ?>selected<?php } ?>" ><span>关闭</span></label>
            <input id="delivery_isuse1" name="delivery_isuse" <?php if($output['list_setting']['delivery_isuse'] == '1'){ ?>checked="checked"<?php } ?>  value="1" type="radio">
            <input id="delivery_isuse0" name="delivery_isuse" <?php if($output['list_setting']['delivery_isuse'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio"></div>
          <p class="notic">现在去设置物流自提服务站使用的快递公司，<a onclick="window.parent.openItem('shop|express');" href="JavaScript:void(0);" class="ncap-btn"><i class="fa fa-truck"></i>
              快递公司</a></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
$(function(){$("#submitBtn").click(function(){
    if($("#settingForm").valid()){
      $("#settingForm").submit();
	}
	});
});
</script>
