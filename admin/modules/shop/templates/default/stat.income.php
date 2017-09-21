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
      <h3>销售收入情况一览</h3>
    </div>
    <dl class="row">
      <dd class="opt">
        <ul class="nc-row">
          <li title="收款金额：<?php echo number_format($output['plat_data']['oot'],2); ?>元">
            <h4>收款金额</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo number_format($output['plat_data']['oot'],2); ?>"></h2>
            <h6>元</h6>
          </li>
          <li title="退款金额：<?php echo number_format($output['plat_data']['oort'],2); ?>元">
            <h4>退款金额</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo number_format($output['plat_data']['oort'],2); ?>"></h2>
            <h6>元</h6>
          </li>
          <li title="实收金额：<?php echo number_format($output['plat_data']['oot']-$output['plat_data']['oort'],2); ?>元">
            <h4>实收金额</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo number_format($output['plat_data']['oot']-$output['plat_data']['oort'],2); ?>"></h2>
            <h6>元</h6>
          </li>
          <li title="佣金总额：<?php echo number_format($output['plat_data']['oct'],2); ?>元">
            <h4>佣金总额</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo number_format($output['plat_data']['oct'],2); ?>"></h2>
            <h6>元</h6>
          </li>
           <li title="店铺费用：<?php echo number_format($output['plat_data']['osct'],2); ?>元">
            <h4>佣金总额</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo number_format($output['plat_data']['osct'],2); ?>"></h2>
            <h6>元</h6>
          </li>
          <li title="总收入：<?php echo number_format($output['plat_data']['ort'],2); ?>元">
            <h4>总收入</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo number_format($output['plat_data']['ort'],2); ?>"></h2>
            <h6>元</h6>
          </li>
        </ul>
      </dd>
    </dl>
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
                    <input id="store_name" name="store_name" value="" type="text" class="s-input-txt" />
                </dd>
            </dl>
            <dl>
                <dt>日期筛选</dt>
                <dd>
                    <label>
                        <input readonly id="query_start_date" placeholder="请选择起始时间" name="query_start_date" value="" type="text" class="s-input-txt" />
                    </label>
                    <label>
                        <input readonly id="query_end_date" placeholder="请选择结束时间" name="query_end_date" value="" type="text" class="s-input-txt" />
                    </label>
                </dd>
            </dl>
        </div>
      </div>
      <div class="bottom"> <a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green mr5">提交查询</a><a href="javascript:void(0);" id="ncreset" class="ncap-btn ncap-btn-orange" title="撤销查询结果，还原列表项所有内容"><i class="fa fa-retweet"></i><?php echo $lang['nc_cancel_search'];?></a></div>
    </form>
  </div>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/jquery.numberAnimation.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/statistics.js"></script>
<script>
function update_flex(){
	$('.ncap-stat-general-single').load('index.php?act=stat_trade&op=get_plat_income&'+$("#formSearch").serialize(),
		function(){
			$('.timer').each(count);
     	});

    $("#flexigrid").flexigrid({
        url: 'index.php?act=stat_trade&op=get_income_xml&'+$("#formSearch").serialize(),
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '店铺名称', name : 'ob_store_name', width : 200, sortable : false, align: 'center'},
            //{display: '商家账号', name : 'member_name',  width : 120, sortable : false, align: 'center'},
            {display: '订单金额', name : 'ob_order_totals',  width : 120, sortable : true, align: 'center'},
            {display: '收取佣金', name : 'ob_commis_totals',  width : 80, sortable : true, align: 'center'},
            {display: '退单金额', name : 'ob_order_return_totals',  width : 100, sortable : true, align: 'center'},
            {display: '退回佣金', name : 'ob_commis_return_totals',  width : 60, sortable : true, align: 'center'},
            {display: '店铺费用', name : 'ob_store_cost_totals',  width : 120, sortable : true, align: 'center'},
            {display: '结算金额', name : 'ob_result_totals',  width : 120, sortable : true, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'excel', bclass : 'csv', title : '导出EXCEL文件', onpress : fg_operation }
        ],
        sortname: "ob_id",
        sortorder: "desc",
        usepager: true,
        rp: 15,
        title: '销售收入明细列表'
    });
}
$(function () {
    //绑定时间控件
    $('#query_start_date').datepicker();
    $('#query_end_date').datepicker();

	update_flex();
	$('#ncsubmit').click(function(){
	    $('.flexigrid').after('<div id="flexigrid"></div>').remove();
	    update_flex();
    });

    // 高级搜索重置
    $('#ncreset').click(function(){
        $('.flexigrid').after('<div id="flexigrid"></div>').remove();
        update_flex();
    });

	$('#searchBarOpen').click();
});
function fg_operation(name, bDiv){
    var stat_url = 'index.php?act=stat_trade&op=income';
    get_search_excel(stat_url,bDiv);
}
</script>