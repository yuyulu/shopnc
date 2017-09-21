<?php defined('In33hao') or exit('Access Invalid!');?>
<div id="flexigrid"></div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
    	url: 'index.php?act=bill&op=get_bill_info_xml&query_type=<?php echo $_GET['query_type'];?>&ob_id=<?php echo $_GET['ob_id'];?>',
        colModel : [
            {display: '店铺名称', name : 'store_name', width : 150, sortable : false, align: 'left'},
            {display: '促销名称', name : 'cost_remark', width : 130, sortable : false, align: 'left'},
            {display: '促销费用', name : 'cost_price', width : 110, sortable : false, align: 'left'},
			{display: '申请日期', name : 'cost_time', width : 80, sortable : false, align : 'center'}         
            ],
        sortname: "cost_id",
        sortorder: "desc",
        title: '账单-店铺费用列表'
    });
});
</script>