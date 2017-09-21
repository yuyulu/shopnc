<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="ncap-stat-chart">
  <div class="title">
    <h3>“<?php echo $output['storename'];?>”走势图</h3>
  </div>
  <!-- 下单会员数 -->
  
  <div id="container_membernum" style="height:300px"></div>
  <br>
  
  <!-- 下单量 -->
  
  <div id="container_ordernum" style="height:300px"></div>
  <br>
  
  <!-- 下单金额 -->
  
  <div id="container_orderamount" style="height:300px"></div>
</div>
<script>
$(function () {
	$('#container_membernum').highcharts(<?php echo $output['stat_json']['membernum'];?>);
	$('#container_ordernum').highcharts(<?php echo $output['stat_json']['ordernum'];?>);
	$('#container_orderamount').highcharts(<?php echo $output['stat_json']['orderamount'];?>);
});
</script>