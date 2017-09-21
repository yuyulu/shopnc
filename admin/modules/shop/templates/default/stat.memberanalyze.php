<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>会员统计</h3>
        <h5>平台针对会员的各项数据统计</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['stat_validorder_explain'];?></li>
      <li>列表展示了时间段内所有会员有效订单的订单数量、下单商品数量和订单总金额统计数据，并可以点击列表上方的“导出数据”，将列表数据导出为Excel文件</li>
    </ul>
  </div>
  <div id="stat_tabs" class="  ui-tabs">
    <ul class="tab-base nc-row">
      <li><a href="#ordernum_div">下单量</a></li>
      <li><a href="#goodsnum_div">下单商品件数</a></li>
      <li><a href="#orderamount_div">下单金额</a></li>
    </ul>

    <!-- 下单量 -->
    <div id="ordernum_div">
      <div id="container_ordernum" style="min-height:400px; width: 100% !important;"></div>
      <div id="list_ordernum" class="m20"></div>
    </div>

    <!-- 下单商品件数 -->
    <div id="goodsnum_div">
      <div id="container_goodsnum" style="min-height:400px; width: 100% !important;"></div>
      <div id="list_goodsnum"></div>
    </div>

    <!-- 下单金额 -->
    <div id="orderamount_div">
      <div id="container_orderamount" style="min-height:400px; width: 100% !important;"></div>
      <div id="list_orderamount"></div>
    </div>
  </div>
  <div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="ncap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" action="index.php" name="formSearch" id="formSearch">
      <input type="hidden" name="act" value="stat_member" />
      <input type="hidden" name="op" value="analyze" />
      <div id="searchCon" class="content">
        <div class="layout-box">
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
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/statistics.js"></script>
<script>

$(function() {
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

	$('#container_ordernum').highcharts(<?php echo $output['statordernum_json'];?>);
	$('#container_goodsnum').highcharts(<?php echo $output['statgoodsnum_json'];?>);
	$('#container_orderamount').highcharts(<?php echo $output['statorderamount_json'];?>);

	//加载详细列表
    $("#list_ordernum").flexigrid({
        url: 'index.php?act=stat_member&op=get_analyzeinfo_xml&type=ordernum&t=<?php echo $output['searchtime'];?>',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '序号', name : 'number', width : 100, sortable : false, align: 'center'},
            {display: '会员名称', name : 'statm_membername',  width : 150, sortable : false, align: 'center'},
            {display: '下单量', name : 'ordernum',  width : 150, sortable : false, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'excel', bclass : 'csv', title : '导出EXCEL文件', onpress : fg_operation_ordernum }
        ],
        usepager: true,
        rp: 15,
        title: '下单量明细列表'
    });
    $("#list_orderamount").flexigrid({
        url: 'index.php?act=stat_member&op=get_analyzeinfo_xml&type=orderamount&t=<?php echo $output['searchtime'];?>',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '序号', name : 'number', width : 100, sortable : false, align: 'center'},
            {display: '会员名称', name : 'statm_membername',  width : 150, sortable : false, align: 'center'},
            {display: '下单金额', name : 'goodsnum',  width : 150, sortable : false, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'excel', bclass : 'csv', title : '导出EXCEL文件', onpress : fg_operation_orderamount }
        ],
        usepager: true,
        rp: 15,
        title: '下单金额明细列表'
    });
    $("#list_goodsnum").flexigrid({
        url: 'index.php?act=stat_member&op=get_analyzeinfo_xml&type=goodsnum&t=<?php echo $output['searchtime'];?>',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '序号', name : 'number', width : 100, sortable : false, align: 'center'},
            {display: '会员名称', name : 'statm_membername',  width : 150, sortable : false, align: 'center'},
            {display: '商品件数', name : 'orderamount',  width : 150, sortable : false, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'excel', bclass : 'csv', title : '导出EXCEL文件', onpress : fg_operation_goodsnum }
        ],
        usepager: true,
        rp: 15,
        title: '下单商品件数明细列表'
    });

	$('#searchBarOpen').click();
	$('#ncsubmit').click(function(){
    	$('#formSearch').submit();
    });
});
function fg_operation_ordernum(name, bDiv){
    fg_operation_excel('ordernum',bDiv);
}
function fg_operation_orderamount(name, bDiv){
    fg_operation_excel('orderamount',bDiv);
}
function fg_operation_goodsnum(name, bDiv){
    fg_operation_excel('goodsnum',bDiv);
}
//Flexigrid导出
function fg_operation_excel(stat_type,obj){
    var stat_url = 'index.php?act=stat_member&op=analyzeinfo&exporttype=excel&t=<?php echo $output['searchtime'];?>&type='+stat_type;
    get_excel(stat_url,obj);
}
//展示搜索时间框
function show_searchtime(){
	s_type = $("#search_type").val();
	$("[id^='searchtype_']").hide();
	$("#searchtype_"+s_type).show();
}

</script>