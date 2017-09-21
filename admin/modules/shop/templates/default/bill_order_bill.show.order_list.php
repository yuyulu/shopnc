<?php defined('In33hao') or exit('Access Invalid!');?>
<div id="flexigrid"></div>
    <div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
    <div class="ncap-search-bar">
      <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
      <div class="title">
        <h3>高级搜索</h3>
      </div>
      <form method="get" name="formSearch" id="formSearch">
        <div id="searchCon" class="content">
          <div class="layout-box">
            <dl>
              <dt>订单编号</dt>
              <dd>
                <label><input type="text" value="" name="order_sn" id="order_sn" class="s-input-txt"></label>
              </dd>
            </dl>
            <dl>
              <dt>买家账号</dt>
              <dd>
                <label><input type="text" value="" name=buyer_name id="buyer_name" class="s-input-txt"></label>
                <label><input type="checkbox" value="1" name="jq_query">精确</label>
              </dd>
            </dl>
            <dl>
              <dt>成交时间</dt>
              <dd><input readonly id="query_start_date" placeholder="请选择起始时间" name=query_start_date value="" type="text" /></dd>
              <dd>&nbsp;</dd>
              <dd><input readonly id="query_end_date" placeholder="请选择结束时间" name="query_end_date" value="" type="text" /></dd>
            </dl>
          </div>
        </div>
        <div class="bottom">
          <a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green">提交查询</a>
          <a href="javascript:void(0);" id="ncreset" class="ncap-btn ncap-btn-orange" title="撤销查询结果，还原列表项所有内容"><i class="fa fa-retweet"></i><?php echo $lang['nc_cancel_search'];?></a>
        </div>
      </form>
    </div>
<script type="text/javascript">
$(function(){
	$('#query_start_date').datepicker({dateFormat:'yy-mm-dd',minDate: "<?php echo date('Y-m-d',$output['bill_info']['ob_start_date']);?>",maxDate: "<?php echo date('Y-m-d',$output['bill_info']['ob_end_date']);?>"});
    $('#query_end_date').datepicker({dateFormat:'yy-mm-dd',minDate: "<?php echo date('Y-m-d',$output['bill_info']['ob_start_date']);?>",maxDate: "<?php echo date('Y-m-d',$output['bill_info']['ob_end_date']);?>"});
    // 高级搜索提交
    $('#ncsubmit').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=bill&op=get_bill_info_xml&query_type=<?php echo $_GET['query_type'];?>&ob_id=<?php echo $_GET['ob_id'];?>&'+$("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });

    // 高级搜索重置
    $('#ncreset').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=bill&op=get_bill_info_xml&query_type=<?php echo $_GET['query_type'];?>&ob_id=<?php echo $_GET['ob_id'];?>'}).flexReload();
        $("#formSearch")[0].reset();
    });
    $("#flexigrid").flexigrid({
        url: 'index.php?act=bill&op=get_bill_info_xml&query_type=<?php echo $_GET['query_type'];?>&ob_id=<?php echo $_GET['ob_id'];?>',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '订单编号', name : 'order_sn', width : 130, sortable : false, align: 'center'}, 
            {display: '订单金额（含运费）', name : 'order_amount', width : 110, sortable : true, align: 'left'},
			{display: '运费', name : 'shipping_fee', width: 60, sortable : true, align : 'left'},                                      
			{display: '佣金', name : 'commis_amount', width : 70, sortable : true, align: 'left'},
			{display: '平台红包', name : 'rpt_amount', width : 70, sortable : true, align: 'left'},
			{display: '下单日期', name : 'add_time', width : 80, sortable : true, align : 'center'},           
			{display: '成交日期', name : 'finnshed_time', width : 80, sortable : true, align: 'center'},
            {display: '买家', name : 'buyer_name', width : 110, sortable : false, align: 'left'},
            {display: '买家ID', name : 'buyer_id', width : 70, sortable : true, align: 'center'},
            {display: '商家', name : 'store_name', width : 130, sortable : false, align: 'left'},
            {display: '商家ID', name : 'store_id', width : 70, sortable : true, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出csv文件,如果不选中行，将导出列表所有数据', onpress : fg_operate}
        ],
        searchitems : [
       {display: '订单编号', name : 'order_sn', isdefault: true},
       {display: '买家账号', name : 'buyer_name'}
       ],
        sortname: "order_id",
        sortorder: "desc",
        title: '账单-订单列表'
    });
});
function fg_operate(name, grid) {
    if (name == 'csv') {
    	var itemlist = new Array();
        if($('.trSelected',grid).length>0){
            $('.trSelected',grid).each(function(){
            	itemlist.push($(this).attr('data-id'));
            });
        }
        fg_csv(itemlist);
    }
}
function fg_csv(ids) {
    id = ids.join(',');
    window.location.href = $("#flexigrid").flexSimpleSearchQueryString() +'&ob_id=<?php echo $_GET['ob_id'];?>&op=export_order&order_id='+id;
}
</script>
