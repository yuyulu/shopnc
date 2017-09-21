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
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>统计图展示了时间段内新增店铺数的走势和与前一时间段的对比</li>
      <li>统计表展示了时间段内新增店铺数值和与前一时间段的同比数值，点击每条记录后的“查看”，了解新增店铺的详细信息</li>
      <li>点击列表上方的“导出数据”，将列表数据导出为Excel文件</li>
    </ul>
  </div>
  <div id="container" style="height:400px"></div>
  <table class="flex-table">
    <input type="hidden" id="export_type" name="export_type" data-param='{"url":"<?php echo $output['actionurl'];?>&exporttype=excel"}' value="excel"/>
    <thead>
      <tr>
        <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
        <th width="60" align="center" class="handle-s">操作</th>
        <?php foreach ($output['statlist']['headertitle'] as $v){?>
        <th width="150" align="center"><?php echo $v; ?></th>
        <?php }?>
        <th></th>
      </tr>
    </thead>
    <tbody id="datatable">
      <?php if(!empty($output['statlist']['data']) && is_array($output['statlist']['data'])){ ?>
      <?php foreach ($output['statlist']['data'] as $k => $v){?>
      <tr>
        <td class="sign"><i class="ico-check"></i></td>
        <td class="handle-s"><a href="index.php?act=stat_store&op=showstore&type=newbyday&t=<?php echo $v['seartime'];?>&scid=<?php echo $_REQUEST['search_sclass'];?>" class="btn green"><i class="fa fa-list-alt"></i>查看</a></td>
        <td><?php echo $v['timetext'];?></td>
        <td><?php echo $v['updata'];?></td>
        <td><?php echo $v['currentdata'];?></td>
        <td><?php echo $v['tbrate'];?></td>
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
  <div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="ncap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="stat_store" />
    <input type="hidden" name="op" value="newstore" />
      <div id="searchCon" class="content">
        <div class="layout-box">
          <dl>
            <dt>按店铺分类筛选</dt>
            <dd>
              <label><select name="search_sclass" id="search_sclass" class="s-select">
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
                  <option value="day" <?php echo $_REQUEST['search_type']=='day'?'selected':''; ?>>按照天统计</option>
                  <option value="week" <?php echo $_REQUEST['search_type']=='week'?'selected':''; ?>>按照周统计</option>
                  <option value="month" <?php echo $_REQUEST['search_type']=='month'?'selected':''; ?>>按照月统计</option>
                </select>
              </label>
            </dd>
            <dd id="searchtype_day" style="display:none;">
              <label>
                <input class="txt" type="text" value="<?php echo $output['search_time'];?>" id="search_time" name="search_time">
              </label></dd>
            <dd id="searchtype_week" style="display:none;">
              <label>
              <select name="search_time_year" class="s-select">
              <?php foreach ($output['year_arr'] as $k => $v){?>
              <option value="<?php echo $k;?>" <?php echo $output['current_year'] == $k?'selected':'';?>><?php echo $v; ?>年</option>
              <?php } ?>
              </select>
              </label>
              <label>
              <select name="search_time_month" class="s-select">
                <?php foreach ($output['month_arr'] as $k => $v){?>
                <option value="<?php echo $k;?>" <?php echo $output['current_month'] == $k?'selected':'';?>><?php echo $v; ?>月</option>
                <?php } ?>
              </select>
              </label>
              <label>
              <select name="search_time_week" class="s-select">
                <?php foreach ($output['week_arr'] as $k => $v){?>
                <option value="<?php echo $v['key'];?>" <?php echo $output['current_week'] == $v['key']?'selected':'';?>><?php echo $v['val']; ?></option>
                <?php } ?>
              </select>
              </label></dd>
            <dd id="searchtype_month" style="display:none;">
              <label>
                <select name="search_time_year" class="s-select">
                  <?php foreach ($output['year_arr'] as $k => $v){?>
                  <option value="<?php echo $k;?>" <?php echo $output['current_year'] == $k?'selected':'';?>><?php echo $v; ?>年</option>
                  <?php } ?>
                </select>
              </label>
              <label>
                <select name="search_time_month" class="s-select">
                  <?php foreach ($output['month_arr'] as $k => $v){?>
                  <option value="<?php echo $k;?>" <?php echo $output['current_month'] == $k?'selected':'';?>><?php echo $v; ?>月</option>
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
$(function () {
	//同步加载flexigrid表格
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
		buttons : [
                   {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '导出数据', onpress : fg_operation }
               ]
		});
	//统计数据类型
	var s_type = $("#search_type").val();
	$('#search_time').datepicker({dateFormat: 'yy-mm-dd'});

	show_searchtime();
	$("#search_type").change(function(){
		show_searchtime();
	});

	//更新周数组
	$("[name='search_time_month']").change(function(){
		var year = $("[name='search_time_year']").val();
		var month = $("[name='search_time_month']").val();
		$("[name='search_time_week']").html('');
		$.getJSON('<?php echo ADMIN_SITE_URL?>/index.php?act=common&op=getweekofmonth',{y:year,m:month},function(data){
	        if(data != null){
	        	for(var i = 0; i < data.length; i++) {
	        		$("[name='search_time_week']").append('<option value="'+data[i].key+'">'+data[i].val+'</option>');
			    }
	        }
	    });
	});

	$('#searchBarOpen').click();

	$('select[name="search_time_year"]').change(function(){
		var s_year = $(this).val();
		$('select[name="search_time_year"]').each(function(){
			$(this).val(s_year);
		});
	});
	$('select[name="search_time_month"]').change(function(){
		var s_month = $(this).val();
		$('select[name="search_time_month"]').each(function(){
			$(this).val(s_month);
		});
	});

	$('#container').highcharts(<?php echo $output['stat_json'];?>);

	$('#ncsubmit').click(function(){
    	$('#formSearch').submit();
    });

});
//展示搜索时间框
function show_searchtime(){
	s_type = $("#search_type").val();
	$("[id^='searchtype_']").hide();
	$("#searchtype_"+s_type).show();
}
//flexigrid表格导出图表
function fg_operation(name, bDiv) {
    if (name == 'csv') {
        var item = $("#export_type");
        var type = $(item).val();
        if(type == 'excel'){
        	download_excel(item);
        }
    }
}
</script>