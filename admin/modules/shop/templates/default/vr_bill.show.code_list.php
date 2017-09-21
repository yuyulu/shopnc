<?php defined('In33hao') or exit('Access Invalid!');?>

<div id="flexigrid"></div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=vr_bill&op=get_bill_info_code_xml&query_type=<?php echo $_GET['query_type'];?>&ob_id=<?php echo $_GET['ob_id'];?>',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '兑换码', name : 'vr_code', width : 130, sortable : false, align: 'center'}, 
            {display: '<?php echo $_GET['query_type'] == 'timeout' ? '过期时间' : '消费时间';?>', name : 'vr_indate', width : 120, sortable : true, align : 'center'},
            {display: '订单号', name : 'order_sn', width : 130, sortable : false, align: 'center'}, 
            {display: '消费金额', name : 'pay_price', width : 110, sortable : false, align: 'left'},
			{display: '佣金金额', name : 'commis_rate', width : 70, sortable : false, align: 'left'},
            {display: '买家', name : 'buyer_name', width : 110, sortable : false, align: 'left'},
            {display: '买家ID', name : 'buyer_id', width : 70, sortable : true, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出csv文件,如果不选中行，将导出列表所有数据', onpress : fg_operate}
        ],
        searchitems : [
            {display: '兑换码', name : 'vr_code'},
            {display: '买家姓名', name : 'buyer_name'}
        ],
        sortname: "rec_id",
        sortorder: "desc",
        title: '账单-兑换码列表'
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
    window.location.href = 'index.php?act=vr_bill&ob_id=<?php echo $_GET['ob_id'];?>&query_type=<?php echo $_GET['query_type'];?>&op=export_order&rec_id='+id;
}
</script>
