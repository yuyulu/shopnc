<?php defined('In33hao') or exit('Access Invalid!');?>
<div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=bill&op=get_bill_info_xml&query_type=<?php echo $_GET['query_type'];?>&ob_id=<?php echo $_GET['ob_id'];?>',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '订单编号', name : 'order_sn', width : 130, sortable : false, align: 'center'}, 
            {display: '订单金额（含运费）', name : 'order_amount', width : 110, sortable : false, align: 'left'},
			{display: '运费', name : 'shipping_fee', width: 60, sortable : false, align : 'left'},                                      
			{display: '未退定金', name : 'deposit_amount', width : 70, sortable : false, align: 'left'},
			{display: '下单日期', name : 'add_time', width : 80, sortable : false, align : 'center'},           
			{display: '取消日期', name : 'cancel_time', width : 80, sortable : false, align: 'center'},
            {display: '买家', name : 'buyer_name', width : 110, sortable : false, align: 'left'},
            {display: '买家ID', name : 'buyer_id', width : 70, sortable : false, align: 'center'},
            {display: '商家', name : 'store_name', width : 130, sortable : false, align: 'left'},
            {display: '商家ID', name : 'store_id', width : 70, sortable : false, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出csv文件,如果不选中行，将导出列表所有数据', onpress : fg_operate}
        ],
        searchitems : [
            {display: '订单编号', name : 'order_sn'}
        ],
        sortname: "book_id",
        sortorder: "desc",
        title: '账单-未退定金的预定订单列表'
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
    window.location.href = $("#flexigrid").flexSimpleSearchQueryString() +'&ob_id=<?php echo $_GET['ob_id'];?>&op=export_book&order_id='+id;
}
</script>
