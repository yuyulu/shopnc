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
  <div class="ncap-form-all ncap-stat-general-single">
    <div class="title">
      <h3>预存款情况一览</h3>
    </div>
    <dl class="row">
      <dd class="opt">
        <ul class="nc-row">
          <li title="存入金额：<?php echo number_format($output['stat_array']['recharge_amount'],2); ?>元">
            <h4>存入金额</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo number_format($output['stat_array']['recharge_amount'],2); ?>"></h2>
            <h6>元</h6>
          </li>
          <li title="消费金额：<?php echo number_format($output['stat_array']['order_amount'],2); ?>元">
            <h4>消费金额</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo number_format($output['stat_array']['order_amount'],2); ?>"></h2>
            <h6>元</h6>
          </li>
          <li title="提现金额：<?php echo number_format($output['stat_array']['cash_amount'],2); ?>元">
            <h4>提现金额</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo number_format($output['stat_array']['cash_amount'],2); ?>"></h2>
            <h6>元</h6>
          </li>
          <li title="总余额：<?php echo number_format($output['usable_amount'],2); ?>元">
            <h4>总余额</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo number_format($output['usable_amount'],2); ?>"></h2>
            <h6>元</h6>
          </li>
          <li title="使用总人数：<?php echo intval($output['user_amount']); ?>人">
            <h4>使用总人数</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo intval($output['user_amount']); ?>"></h2>
            <h6>人</h6>
          </li>
        </ul>
      </dd>
    </dl>
  </div>
  <div id="container" class=" " style="height:400px"></div>
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
            <dt>查询类型</dt>
            <dd>
              <label>
                <select name="pd_type" id="pd_type" class="s-select">
                  <option value="recharge" <?php echo $_GET['pd_type']=='recharge'?'selected':''; ?>>充值</option>
                  <option value="order_pay" <?php echo $_GET['pd_type']=='order_pay'?'selected':''; ?>>消费</option>
                  <option value="cash_pay" <?php echo $_GET['pd_type']=='cash_pay'?'selected':''; ?>>提现</option>
                  <option value="refund" <?php echo $_GET['pd_type']=='refund'?'selected':''; ?>>退款</option>
                </select>
              </label>
            </dd>
          </dl>
          <dl>
            <dt>按时间周期筛选</dt>
            <dd>
              <label>
                <select name="search_type" id="search_type" class="s-select">
                  <option value="day" <?php echo $_GET['search_type']=='day'?'selected':''; ?>>按照天统计</option>
                  <option value="week" <?php echo $_GET['search_type']=='week'?'selected':''; ?>>按照周统计</option>
                  <option value="month" <?php echo $_GET['search_type']=='month'?'selected':''; ?>>按照月统计</option>
                </select>
              </label>
            </dd>
            <dd id="searchtype_day" style="display:none;">
              <label>
                <input class="s-input-txt" type="text" value="<?php echo $output['search_time'];?>" id="search_time" name="search_time">
              </label>
            </dd>
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
              </label>
            </dd>
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
	$('.ncap-stat-general-single').load('index.php?act=stat_trade&op=get_plat_predeposit&'+$("#formSearch").serialize(),
		function(){
			$('.timer').each(count);
     	});
	$('#container').load('index.php?act=stat_trade&op=get_predeposit_highcharts&'+$("#formSearch").serialize());
    $("#flexigrid").flexigrid({
        url: 'index.php?act=stat_trade&op=get_predeposit_xml&'+$("#formSearch").serialize(),
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '会员名称', name : 'lg_member_name', width : 120, sortable : false, align: 'center'},
            {display: '创建时间', name : 'lg_add_time',  width : 150, sortable : true, align: 'center'},
            {display: '可用金额（元）', name : 'lg_av_amount',  width : 100, sortable : true, align: 'center'},
            {display: '冻结金额（元）', name : 'lg_freeze_amount',  width : 100, sortable : true, align: 'center'},
            {display: '管理员名称', name : 'lg_admin_name',  width : 100, sortable : false, align: 'center'},
            {display: '类型', name : 'lg_type',  width : 50, sortable : false, align: 'center'},
            {display: '描述', name : 'lg_desc',  width : 300, sortable : false, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'excel', bclass : 'csv', title : '导出EXCEL文件', onpress : fg_operation }
        ],
        sortname: "lg_add_time",
        sortorder: "desc",
        usepager: true,
        rp: 15,
        title: '预存款统计明细列表'
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

    update_flex();
	$('#ncsubmit').click(function(){
	    $('.flexigrid').after('<div id="flexigrid"></div>').remove();
	    update_flex();
    });
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
});
function fg_operation(name, bDiv){
    var stat_url = 'index.php?act=stat_trade&op=predeposit';
    get_search_excel(stat_url,bDiv);
}
</script>