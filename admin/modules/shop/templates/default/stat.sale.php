<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>销量分析</h3>
        <h5>平台针对销售量的各项数据统计</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['stat_validorder_explain'];?></li>
      <li>统计图展示了符合搜索条件的有效订单中的下单总金额和下单数量在时间段内的走势情况及与前一个时间段的趋势对比</li>
      <li>统计表显示了符合搜索条件的全部有效订单记录并可以点击“导出数据”将订单记录导出为Excel文件</li>
    </ul>
  </div>
  <div class="ncap-form-all ncap-stat-general-single">
    <div class="title">
      <h3>订单情况一览</h3>
    </div>
    <dl class="row">
      <dd class="opt">
        <ul class="nc-row">
          <li title="总销售额：<?php echo ($t = $output['statcount_arr']['orderamount'])?$t:'0.00'; ?>元">
            <h4>总销售额</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo ($t = $output['statcount_arr']['orderamount'])?$t:'0.00'; ?>"></h2>
            <h6>元</h6>
          </li>
          <li title="总订单量：<?php echo intval($output['statcount_arr']['ordernum']); ?>元">
            <h4>总订单量</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo intval($output['statcount_arr']['ordernum']); ?>"></h2>
            <h6>笔</h6>
          </li>
        </ul>
      </dd>
    </dl>
  </div>
  <div id="stat_tabs" class="  ui-tabs" style="min-height:500px">
    <ul class="tab-base nc-row">
      <li><a href="#orderamount_div" nc_type="showdata" data-param='{"type":"orderamount"}'>下单金额</a></li>
      <li><a href="#ordernum_div" nc_type="showdata" data-param='{"type":"ordernum"}'>下单量</a></li>
    </ul>

    <!-- 下单金额 -->
    <div id="orderamount_div" class="" style="text-align:center;"></div>
    <!-- 下单量 -->
    <div id="ordernum_div" class="" style="text-align:center;"></div>
  </div>
  <div id="flexigrid"></div>
  <div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="ncap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" action="index.php" name="formSearch" id="formSearch">
      <div id="searchCon" class="content">
        <div class="layout-box">
          <dl>
            <dt>店铺名称</dt>
            <dd>
              <label>
                <input type="text" class="s-input-txt" name="store_name" id="store_name" value="<?php echo $_GET['store_name'];?>" placeholder="请输入店铺名称"/>
              </label>
            </dd>
          </dl>
          <dl>
            <dt>按订单状态筛选</dt>
            <dd>
              <label>
                <select name="order_type" id="order_type" class="s-select">
                  <option value="" <?php echo $_REQUEST['order_type']==''?'selected':''; ?>>-请选择-</option>
                  <option value="<?php echo ORDER_STATE_NEW; ?>" <?php echo $_REQUEST['order_type']!='' && $_REQUEST['order_type']==ORDER_STATE_NEW?'selected':''; ?>>待付款</option>
                  <option value="<?php echo ORDER_STATE_PAY; ?>" <?php echo $_REQUEST['order_type']!='' && $_REQUEST['order_type']==ORDER_STATE_PAY?'selected':''; ?>>待发货</option>
                  <option value="<?php echo ORDER_STATE_SEND; ?>" <?php echo $_REQUEST['order_type']!='' && $_REQUEST['order_type']==ORDER_STATE_SEND?'selected':''; ?>>待收货</option>
                  <option value="<?php echo ORDER_STATE_SUCCESS; ?>" <?php echo $_REQUEST['order_type']!='' && $_REQUEST['order_type']==ORDER_STATE_SUCCESS?'selected':''; ?>>交易完成</option>
                  <option value="<?php echo ORDER_STATE_CANCEL; ?>" <?php echo $_REQUEST['order_type']!='' && $_REQUEST['order_type']==ORDER_STATE_CANCEL?'selected':''; ?>>已取消</option>
                </select>
              </label>
            </dd>
          </dl>
          <dl>
            <dt>按时间周期筛选</dt>
            <dd>
              <label>
                <select name="search_type" id="search_type" class="s-select">
                  <option value="day" <?php echo $output['search_arr']['search_type']=='day'?'selected':''; ?>>按照天统计</option>
                  <option value="week" <?php echo $output['search_arr']['search_type']=='week'?'selected':''; ?>>按照周统计</option>
                  <option value="month" <?php echo $output['search_arr']['search_type']=='month'?'selected':''; ?>>按照月统计</option>
                </select>
              </label>
            </dd>
            <dd id="searchtype_day" style="display:none;">
              <label>
                <input class="s-input-txt" type="text" value="<?php echo @date('Y-m-d',$output['search_arr']['day']['search_time']);?>" id="search_time" name="search_time">
              </label>
            </dd>
            <dd id="searchtype_week" style="display:none;">
              <label>
                <select name="searchweek_year" class="s-select">
                  <?php foreach ($output['year_arr'] as $k => $v){?>
                  <option value="<?php echo $k;?>" <?php echo $output['search_arr']['week']['current_year'] == $k?'selected':'';?>><?php echo $v; ?>年</option>
                  <?php } ?>
                </select>
              </label>
              <label>
                <select name="searchweek_month" class="s-select">
                  <?php foreach ($output['month_arr'] as $k => $v){?>
                  <option value="<?php echo $k;?>" <?php echo $output['search_arr']['week']['current_month'] == $k?'selected':'';?>><?php echo $v; ?>月</option>
                  <?php } ?>
                </select>
              </label>
              <label>
                <select name="searchweek_week" class="s-select">
                  <?php foreach ($output['week_arr'] as $k => $v){?>
                  <option value="<?php echo $v['key'];?>" <?php echo $output['search_arr']['week']['current_week'] == $v['key']?'selected':'';?>><?php echo $v['val']; ?></option>
                  <?php } ?>
                </select>
              </label>
            </dd>
            <dd id="searchtype_month" style="display:none;">
              <label>
                <select name="searchmonth_year" class="s-select">
                  <?php foreach ($output['year_arr'] as $k => $v){?>
                  <option value="<?php echo $k;?>" <?php echo $output['search_arr']['month']['current_year'] == $k?'selected':'';?>><?php echo $v; ?>年</option>
                  <?php } ?>
                </select>
              </label>
              <label>
                <select name="searchmonth_month" class="s-select">
                  <?php foreach ($output['month_arr'] as $k => $v){?>
                  <option value="<?php echo $k;?>" <?php echo $output['search_arr']['month']['current_month'] == $k?'selected':'';?>><?php echo $v; ?>月</option>
                  <?php } ?>
                </select>
              </label>
            </dd>
          </dl>
        </div>
      </div>
      <div class="bottom"> <a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green">提交查询</a> </div>
    </form>
  </div>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/jquery.numberAnimation.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/statistics.js"></script>
<script>
//展示搜索时间框
function show_searchtime(){
	s_type = $("#search_type").val();
	$("[id^='searchtype_']").hide();
	$("#searchtype_"+s_type).show();
}

function update_flex(){
	$('.ncap-stat-general-single').load('index.php?act=stat_trade&op=get_plat_sale&'+$("#formSearch").serialize(),
		function(){
			$('.timer').each(count);
     	});
    $("#flexigrid").flexigrid({
        url: 'index.php?act=stat_trade&op=get_sale_xml&'+$("#formSearch").serialize(),
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '订单号', name : 'order_sn', width : 150, sortable : false, align: 'center'},
            {display: '买家', name : 'buyer_name',  width : 120, sortable : false, align: 'center'},
            {display: '店铺名称', name : 'store_name',  width : 150, sortable : false, align: 'center'},
            {display: '下单时间', name : 'order_add_time',  width : 150, sortable : true, align: 'center'},
            {display: '订单总额', name : 'order_amount',  width : 120, sortable : true, align: 'center'},
            {display: '订单状态', name : 'order_state',  width : 120, sortable : false, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'excel', bclass : 'csv', title : '导出EXCEL文件', onpress : fg_operation }
        ],
        sortname: "order_id",
        sortorder: "desc",
        usepager: true,
        rp: 15,
        title: '订单统计明细列表'
    });
}
$(function () {
	//切换登录卡
	$('#stat_tabs').tabs();

	//统计数据类型
	var s_type = $("#search_type").val();
	$('#search_time').datepicker({dateFormat: 'yy-mm-dd'});

	show_searchtime();
	$("#search_type").change(function(){
		show_searchtime();
	});

	//更新周数组
	$("[name='searchweek_month']").change(function(){
		var year = $("[name='searchweek_year']").val();
		var month = $("[name='searchweek_month']").val();
		$("[name='searchweek_week']").html('');
		$.getJSON('<?php echo ADMIN_SITE_URL?>/index.php?act=common&op=getweekofmonth',{y:year,m:month},function(data){
	        if(data != null){
	        	for(var i = 0; i < data.length; i++) {
	        		$("[name='searchweek_week']").append('<option value="'+data[i].key+'">'+data[i].val+'</option>');
			    }
	        }
	    });
	});

	$('#searchBarOpen').click();

	$('#ncsubmit').click(function(){
    	$("li[aria-selected='true'] a").trigger("click");
	    $('.flexigrid').after('<div id="flexigrid"></div>').remove();
	    update_flex();
    });

  	//加载统计数据
    getStatdata('orderamount');
    update_flex();
    $("[nc_type='showdata']").click(function(){
    	var data_str = $(this).attr('data-param');
		eval('data_str = '+data_str);
		getStatdata(data_str.type);
    });
});
//加载统计地图
function getStatdata(type){
	$('#'+type+'_div').load('index.php?act=stat_trade&op=sale_trend&type='+type+'&'+$("#formSearch").serialize());
}
function fg_operation(name, bDiv){
    var stat_url = 'index.php?act=stat_trade&op=sale';
    get_search_excel(stat_url,bDiv);
}
</script>