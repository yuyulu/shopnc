<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page"> 
  <!-- 页面导航 -->
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>物流自提服务站</h3>
        <h5>商城对线下物流自提点的设定集管理</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="<?php echo urlAdminShop('delivery', 'index');?>"><?php echo $lang['nc_manage'];?></a></li>
        <li><a href="<?php echo urlAdminShop('delivery', 'index', array('sign' => 'verify'));?>">等待审核</a></li>
        <li><a href="javascript:void(0);" class="current">设置</a></li>
      </ul>
    </div>
  </div>
  <form id="setting_form" method="post" action="<?php echo urlAdminShop('delivery', 'save_setting');?>">
    <input type="hidden" id="form_submit" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit"><label for="promotion_booth_price"><em>*</em>物流自提服务站是否开启</label></dt>
        
        <dd class="opt">
          <div class="onoff"><label for="site_status1" class="cb-enable <?php if($output['list_setting']['delivery_isuse'] == '1'){ ?>selected<?php } ?>" ><span>开启</span></label>
            <label for="site_status0" class="cb-disable <?php if($output['list_setting']['delivery_isuse'] == '0'){ ?>selected<?php } ?>" ><span>关闭</span></label>
            <input id="site_status1" name="delivery_isuse" <?php if($output['list_setting']['delivery_isuse'] == '1'){ ?>checked="checked"<?php } ?>  value="1" type="radio">
            <input id="site_status0" name="delivery_isuse" <?php if($output['list_setting']['delivery_isuse'] == '0'){ ?>checked="checked"<?php } ?> value="<?php echo $output['dlyp_info']['dlyp_state'];?>" type="radio"></div>
          <p class="notic">现在去设置物流自提服务站使用的快递公司，<a onclick="window.parent.openItem('shop|express');" href="JavaScript:void(0);" class="ncap-btn"><i class="fa fa-truck"></i>
快递公司</a></p>
        </dd></dl>
        
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div></div>
  </form>
</div>
<script>
$(function(){
    $("#submitBtn").click(function(){
        $("#setting_form").submit();
    });
});
</script>