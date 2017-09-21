<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>营销分析</h3>
        <h5>平台针对营销促销情况的各项数据统计</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['stat_validorder_explain'];?></li>
      <li>走势图展现了各种促销活动时间段内有效订单的下单量、下单总金额、下单商品数走势对比</li>
      <li>饼图则展现了各种促销活动时间段内有效订单的下单量、下单总金额、下单商品数占总数的比例</li>
      <li>统计列表则清晰的展现了各种促销活动的下单量、下单总金额、下单商品数的总数值</li>
    </ul>
  </div>
  <div class="ncap-form-all ncap-stat-general-single">
    <div class="title">
      <h3>促销情况一览</h3>
    </div>
    <dl class="row">
      <dd class="opt">
        <ul class="nc-row">
          <li title="下单量：<?php echo intval($output['statcount']['ordernum']);?>元">
            <h4>下单量</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo intval($output['statcount']['ordernum']);?>"></h2>
            <h6>笔</h6>
          </li>
          <li title="下单商品数：<?php echo intval($output['statcount']['goodsnum']);?>元">
            <h4>下单商品数</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo intval($output['statcount']['goodsnum']);?>"></h2>
            <h6></h6>
          </li>
          <li title="下单金额：<?php echo ncPriceFormat($output['statcount']['orderamount']);?>元">
            <h4>下单金额</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo ncPriceFormat($output['statcount']['orderamount']);?>"></h2>
            <h6>元</h6>
          </li>
        </ul>
      </dd>
    </dl>
  </div>
  <div id="stat_tabs">
    <ul class="tab-base nc-row">
      <li><a href="#ordernum_div" nc_type="showlinelabels" data-param='{"type":"ordernum"}'>下单量</a></li>
      <li><a href="#goodsnum_div" nc_type="showlinelabels" data-param='{"type":"goodsnum"}'>下单商品数</a></li>
      <li><a href="#orderamount_div" nc_type="showlinelabels" data-param='{"type":"orderamount"}'>下单金额</a></li>
    </ul>

    <!-- 下单量 -->
    <div id="ordernum_div"></div>
    <!-- 下单商品件数 -->
    <div id="goodsnum_div"></div>
    <!-- 下单金额 -->
    <div id="orderamount_div"></div>
  </div>

  <!-- pie stat start -->
  <div style="max-height:400px">
    <div id="statpie_ordernum" class="w18pre" style="float:left; padding-left:50px;"></div>
    <div id="statpie_goodsnum" class="w18pre" style="float:left; padding-left:50px;"></div>
    <div id="statpie_orderamount" class="w18pre" style="float:left; padding-left:50px;"></div>
  </div>
  <div class="clear"></div>
  <!-- pie stat end -->

  <!-- stat list start -->
  <table class="flex-table">
    <thead>
      <tr>
        <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
        <th width="60" align="center" class="handle-s">操作</th>
        <th align="center" width="150">促销类型</th>
        <th align="center" width="150">下单量</th>
        <th align="center" width="150">下单商品数</th>
        <th align="center" width="150">下单金额（元）</th>
        <th></th>
      </tr>
    <tbody id="datatable">
      <?php if(!empty($output['statlist'])){ ?>
      <?php foreach($output['statlist'] as $k => $v){ ?>
      <tr class="hover member">
        <td class="sign"></td>
        <td class="handle-s"><span>--</span></td>
        <td><?php echo $v['goodstype_text'];?></td>
        <td><?php echo $v['ordernum']; ?></td>
        <td><?php echo $v['goodsnum']; ?></td>
        <td><?php echo $v['orderamount']; ?></td>
        <td></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td class="no-data" colspan="100"><i class="fa fa-exclamation-triangle"></i><?php echo $lang['nc_no_record'];?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <!-- stat list end -->
  <div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="ncap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" action="index.php" name="formSearch" id="formSearch">
      <input type="hidden" name="act" value="stat_marketing" />
      <input type="hidden" name="op" value="promotion" />
      <div id="searchCon" class="content">
        <div class="layout-box">
          <dl>
            <dt>按时间周期筛选</dt>
            <dd>
              <label>
                <select name="search_type" id="search_type" class="class-select">
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
$(function() {
	//同步加载flexigrid表格
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
		title: '促销统计列表'
		});

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

	//linelabels
	getLineLabels('ordernum');
	$("[nc_type='showlinelabels']").click(function(){
    	var data_str = $(this).attr('data-param');
		eval('data_str = '+data_str);
		getLineLabels(data_str.type);
    });

	$('#ncsubmit').click(function(){
    	$('#formSearch').submit();
    });

	//pie
	$('#statpie_ordernum').highcharts(<?php echo $output['stat_json']['ordernum'];?>);
	$('#statpie_goodsnum').highcharts(<?php echo $output['stat_json']['goodsnum'];?>);
	$('#statpie_orderamount').highcharts(<?php echo $output['stat_json']['orderamount'];?>);
});
//展示搜索时间框
function show_searchtime(){
	s_type = $("#search_type").val();
	$("[id^='searchtype_']").hide();
	$("#searchtype_"+s_type).show();
}
//load linelabels
function getLineLabels(stattype){
	var search_type = $("#search_type").val();
	if(!$("#"+stattype+'_div').html()){
		$("#"+stattype+'_div').load('index.php?act=stat_marketing&op=promotiontrend&search_type='+search_type+'&stattype='+stattype+'&t=<?php echo $output['searchtime'];?>');
	}
}
</script>