<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_statgeneral'];?></h3>
        <h5>商城统计最新情报及相关设置</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['stat_validorder_explain'];?></li>
    </ul>
  </div>
  <div class="ncap-form-all ncap-stat-general">
    <div class="title">
      <h3><?php echo @date('Y-m-d',$output['stat_time']);?>最新情报</h3>
    </div>
    <dl class="row">
      <dd class="opt">
        <ul class="nc-row">
          <li title="下单金额：<?php echo $output['statnew_arr']['orderamount'];?>元">
            <h4>下单金额</h4>
            <h6>有效订单的总金额(元)</h6>
            <h2 class="timer" id="count-number"  data-to="<?php echo $output['statnew_arr']['orderamount'];?>" data-speed="1500"></h2>
          </li>
          <li title="下单会员数：<?php echo $output['statnew_arr']['ordermembernum'];?>">
            <h4>下单会员数</h4>
            <h6>有效订单的下单会员总数</h6>
            <h2 class="timer" id="count-number"  data-to="<?php echo $output['statnew_arr']['ordermembernum'];?>" data-speed="1500"></h2>
          </li>
          <li title="下单量：<?php echo $output['statnew_arr']['ordernum'];?>">
            <h4>下单量</h4>
            <h6>有效订单的总数量</h6>
            <h2 class="timer" id="count-number"  data-to="<?php echo $output['statnew_arr']['ordernum'];?>" data-speed="1500"></h2>
          </li>
          <li title="下单商品数：<?php echo $output['statnew_arr']['ordergoodsnum'];?>">
            <h4>下单商品数</h4>
            <h6>有效订单包含的商品总数量</h6>
            <h2 class="timer" id="count-number"  data-to="<?php echo $output['statnew_arr']['ordergoodsnum'];?>" data-speed="1500"></h2>
          </li>
          <li title="平均价格：<?php echo $output['statnew_arr']['priceavg'];?>元">
            <h4>平均价格</h4>
            <h6>有效订单包含商品的平均单价（元）</h6>
            <h2 class="timer" id="count-number"  data-to="<?php echo $output['statnew_arr']['priceavg'];?>" data-speed="1500"></h2>
          </li>
          <li title="平均客单价：<?php echo $output['statnew_arr']['orderavg'];?>元">
            <h4>平均客单价</h4>
            <h6>有效订单的平均每单的金额（元）</h6>
            <h2 class="timer" id="count-number"  data-to="<?php echo $output['statnew_arr']['orderavg'];?>" data-speed="1500"></h2>
          </li>
          <li title="新增会员：<?php echo $output['statnew_arr']['newmember'];?>">
            <h4>新增会员</h4>
            <h6>期间内新注册会员总数</h6>
            <h2 class="timer" id="count-number"  data-to="<?php echo $output['statnew_arr']['newmember'];?>" data-speed="1500"></h2>
          </li>
          <li title="会员数量：<?php echo $output['statnew_arr']['membernum'];?>">
            <h4>会员数量</h4>
            <h6>平台所有会员的数量</h6>
            <h2 class="timer" id="count-number"  data-to="<?php echo $output['statnew_arr']['membernum'];?>" data-speed="1500"></h2>
          </li>
          <li title="新增店铺：<?php echo $output['statnew_arr']['newstore'];?>">
            <h4>新增店铺</h4>
            <h6>期间内新注册店铺总数</h6>
            <h2 class="timer" id="count-number"  data-to="<?php echo $output['statnew_arr']['newstore'];?>" data-speed="1500"></h2>
          </li>
          <li title="店铺数量：<?php echo $output['statnew_arr']['storenum'];?>">
            <h4>店铺数量</h4>
            <h6>平台所有店铺的数量</h6>
            <h2 class="timer" id="count-number"  data-to="<?php echo $output['statnew_arr']['storenum'];?>" data-speed="1500"></h2>
          </li>
          <li title="新增商品：<?php echo $output['statnew_arr']['newgoods'];?>">
            <h4>新增商品</h4>
            <h6>期间内新增商品总数</h6>
            <h2 class="timer" id="count-number"  data-to="<?php echo $output['statnew_arr']['newgoods'];?>" data-speed="1500"></h2>
          </li>
          <li title="商品数量：<?php echo $output['statnew_arr']['goodsnum'];?>">
            <h4>商品数量</h4>
            <h6>平台所有商品的数量</h6>
            <h2 class="timer" id="count-number"  data-to="<?php echo $output['statnew_arr']['goodsnum'];?>" data-speed="1500" ></h2>
          </li>
        </ul>
    </dl>
  </div>
  <div class="ncap-stat-chart">
    <div class="title">
      <h3><?php echo @date('Y-m-d',$output['stat_time']);?>销售走势</h3>
    </div>
    <div id="container" class=" " style="height:400px"></div>
  </div>
  <div style="width:49%; margin-right:1%; float: left;">
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="60" align="center" class="handle-s">操作</th>
          <th width="60" align="center">序号</th>
          <th width="120" align="left">店铺名称</th>
          <th width="60" align="center">下单金额</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach((array)$output['storetop30_arr'] as $k=>$v){ ?>
        <tr>
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle-s"><span>--</span></td>
          <td><?php echo $k+1;?></td>
          <td><?php echo $v['store_name'];?></td>
          <td><?php echo ncPriceFormat($v['orderamount']);?></td>
          <td></td>
        </tr>
        <?php } ?>
        <?php if(empty($output['storetop30_arr'])){ ?>
        <tr>
          <td class="no-data" colspan="100"><i class="fa fa-exclamation-triangle"></i><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <div style="width:50%; float: left;">
    <table class="flex-table2">
      <thead>
        <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
        <th width="60" align="center" class="handle-s">操作</th>
        <th width="60" align="center">序号</th>
        <th width="250" align="left">商品名称</th>
        <th width="60" align="center">销量</th>
        <th></th>
          </thead>
      <tbody>
        <?php foreach((array)$output['goodstop30_arr'] as $k=>$v){ ?>
        <tr>
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle-s"><span>--</span></td>
          <td><?php echo $k+1;?></td>
          <td><a href='<?php echo urlShop('goods', 'index', array('goods_id' => $v['goods_id']));?>' target="_blank"><?php echo $v['goods_name'];?></a></td>
          <td><?php echo $v['ordergoodsnum'];?></td>
          <td></td>
        </tr>
        <?php } ?>
        <?php if(empty($output['goodstop30_arr'])){ ?>
        <tr>
          <td class="no-data" colspan="100"><i class="fa fa-exclamation-triangle"></i><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/jquery.numberAnimation.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/statistics.js"></script>
<script>
$(function () {
	//同步加载flexigrid表格
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
		title:'7日内店铺销售TOP30'
		});
	$('.flex-table2').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
		title:'7日内商品销售TOP30'
		});

	$('#container').highcharts(<?php echo $output['stattoday_json'];?>);
});
</script>