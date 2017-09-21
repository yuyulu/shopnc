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
      <li>列表一为时间段内进行中的抢购活动息</li>
      <li>列表二为时间段内抢购活动生成的有效订单记录，默认按照“下单商品数”降序排列</li>
    </ul>
  </div>
  <div id="glist" class=" "></div>
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
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/statistics.js"></script>
<script>
//展示搜索时间框
function show_searchtime(){
	s_type = $("#search_type").val();
	$("[id^='searchtype_']").hide();
	$("#searchtype_"+s_type).show();
}
function update_flex(){
	//加载统计列表
    $("#glist").flexigrid({
        url: 'index.php?act=stat_marketing&op=get_grouplist_xml&'+$("#formSearch").serialize(),
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '抢购名称', name : 'groupbuy_name', width : 150, sortable : false, align: 'left'},
            {display: '开始时间', name : 'start_time', width : 80, sortable : true, align: 'left'},
            {display: '结束时间', name : 'end_time', width : 80, sortable : true, align: 'left'},
            {display: '抢购状态', name : 'groupbuy_state_text',  width : 60, sortable : true, align: 'center'},
            {display: '商品名称', name : 'goods_name', width : 150, sortable : false, align: 'left'},
            {display: '原价', name : 'goods_price',  width : 60, sortable : true, align: 'center'},
            {display: '折扣', name : 'groupbuy_rebate', width : 50, sortable : false, align: 'center'},
            {display: '抢购价', name : 'groupbuy_price',  width : 60, sortable : true, align: 'center'},
            {display: '浏览次数', name : 'views',  width : 60, sortable : true, align: 'center'},
            {display: '下单量', name : 'ordernum',  width : 50, sortable : false, align: 'center'},
            {display: '购买量', name : 'goodsnum',  width : 50, sortable : false, align: 'center'},
            {display: '总金额', name : 'goodsamount',  width : 60, sortable : false, align: 'center'},
            {display: '下单转化率', name : 'orderrate',  width : 80, sortable : false, align: 'center'}
            ],
        searchitems : [
            {display: '抢购名称', name : 'groupbuy_name'},
            {display: '商品名称', name : 'goods_name'}
            ],
        sortname: "start_time",
        sortorder: "asc",
        usepager: true,
        rp: 15,
        title: '抢购商品'
    });
    $("#flexigrid").flexigrid({
        url: 'index.php?act=stat_marketing&op=get_groupgoods_xml&'+$("#formSearch").serialize(),
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '商品名称', name : 'goods_name', width : 250, sortable : false, align: 'center'},
            {display: '下单商品数', name : 'goodsnum',  width : 100, sortable : true, align: 'center'},
            {display: '下单金额', name : 'goodsamount', width : 120, sortable : true, align: 'center'},
            {display: '取消商品数', name : 'cancelgoodsnum',  width : 100, sortable : true, align: 'center'},
            {display: '取消金额', name : 'cancelgoodsamount',  width : 120, sortable : true, align: 'center'},
            {display: '完成商品数', name : 'finishgoodsnum',  width : 100, sortable : true, align: 'center'},
            {display: '完成金额', name : 'finishgoodsamount',  width : 120, sortable : true, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'excel', bclass : 'csv', title : '导出EXCEL文件', onpress : fg_operation }
        ],
        sortname: "goodsnum",
        sortorder: "desc",
        usepager: true,
        rp: 15,
        title: '抢购统计'
    });
}

$(function () {
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

	update_flex();

	$('#ncsubmit').click(function(){
	    $("#glist").flexOptions({url: 'index.php?act=stat_marketing&op=get_grouplist_xml&'+$("#formSearch").serialize(),query:'',qtype:''}).flexReload();
	    $("#flexigrid").flexOptions({url: 'index.php?act=stat_marketing&op=get_groupgoods_xml&'+$("#formSearch").serialize(),query:'',qtype:''}).flexReload();
	    update_flex();
    });
});
function fg_operation(name, bDiv){
    var stat_url = 'index.php?act=stat_marketing&op=groupgoods';
    get_search_excel(stat_url,bDiv);
}
</script>