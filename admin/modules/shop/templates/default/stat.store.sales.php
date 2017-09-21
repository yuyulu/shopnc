<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>店铺统计</h3>
        <h5>平台针对店铺的各项数据统计</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
    </div>
    <ul>
      <li><?php echo $lang['stat_validorder_explain'];?></li>
            <li>列表展示了店铺在搜索时间段内的有效订单总金额、订单量和下单会员数，并可以点击列表上方的“导出数据”将列表数据导出为Excel文件</li>
            <li>默认按照“下单会员数”降序排列</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
  <div id="stattrends" class="  ui-tabs"></div>
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
                <input class="s-input-txt" type="text" value="<?php echo $_GET['search_sname'];?>" id="search_sname" name="search_sname" placeholder="请输入店铺名称" />
              </label>
            </dd>
          </dl>
          <dl>
            <dt>按店铺分类筛选</dt>
            <dd>
              <label>
                <select name="search_sclass" id="search_sclass" class="s-select">
                  <option value="">-请选择-</option>
                  <?php foreach ($output['store_class'] as $k => $v){ ?>
                  <option value="<?php echo $v['sc_id'];?>" <?php echo $_REQUEST['search_sclass'] == $v['sc_id']?'selected':''; ?>><?php echo $v['sc_name'];?></option>
                  <?php } ?>
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
              </label></dd>
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
              </label></dd>
            <dd id="searchtype_month" style="display:none;">
              <label><select name="searchmonth_year" class="s-select">
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
              </label></dd>
          </dl>
        </div>
      </div>
      <div class="bottom">
        <a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green">提交查询</a>
      </div>
    </form>
  </div>
  <script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/highcharts.js"></script>
  <script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/statistics.js"></script>
</div>
<script>
//展示搜索时间框
function show_searchtime(){
	var s_type = $("#search_type").val();
	$("[id^='searchtype_']").hide();
	$("#searchtype_"+s_type).show();
}
//加载统计地图
function getTrends(storeid){
	var s_type = $("#search_type").val();
	$('#stattrends').load('index.php?act=stat_store&op=storesales_trends&storeid='+storeid+'&search_type='+s_type+'&'+$("#formSearch").serialize());
}
function update_flex(){
    $('#stattrends').html('');
    $("#flexigrid").flexigrid({
        url: 'index.php?act=stat_store&op=get_storesales_xml&'+$("#formSearch").serialize(),
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '店铺名称', name : 'store_name', width : 150, sortable : false, align: 'center'},
            {display: '下单会员数', name : 'membernum',  width : 120, sortable : true, align: 'center'},
            {display: '下单量', name : 'ordernum',  width : 120, sortable : true, align: 'center'},
            {display: '下单金额', name : 'orderamount',  width : 120, sortable : true, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'excel', bclass : 'csv', title : '导出EXCEL文件', onpress : fg_operation }
        ],
        sortname: "membernum",
        sortorder: "desc",
        usepager: true,
        rp: 15,
        title: '销售统计'
    });
}
$(function () {
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
	update_flex();
	$('#ncsubmit').click(function(){
	    $('.flexigrid').after('<div id="flexigrid"></div>').remove();
	    update_flex();
    });
    //店铺销售走势
    $("[nc_type='showtrends']").live('click',function(){
    	var data_str = $(this).attr('data-param');
		eval('data_str = '+data_str);
		getTrends(data_str.storeid);
    });
});
function fg_operation(name, bDiv){
    var stat_url = 'index.php?act=stat_store&op=storesales_list';
    get_search_excel(stat_url,bDiv);
}
</script>