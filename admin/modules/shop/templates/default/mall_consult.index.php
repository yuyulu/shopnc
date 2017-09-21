<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>平台客服</h3>
        <h5>商城对用户咨询类型设定与处理</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="JavaScript:void(0);" class="current">平台客服咨询列表</a></li>
        <li><a href="<?php echo urlAdminShop('mall_consult', 'type_list');?>">平台咨询类型</a></li>
      </ul>
    </div>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=mall_consult&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '咨询内容', name : 'mc_content', width : 250, sortable : false, align: 'left'}, 
			{display: '咨询人', name : 'member_name', width : 150, sortable : false, align : 'left'},
			{display: '咨询人ID', name : 'member_id', width : 80, sortable : true, align : 'center'},         
			{display: '咨询时间', name : 'mc_addtime', width: 140, sortable : true, align : 'center'}, 
            {display: '回复状态', name : 'is_reply', width : 80, sortable : true, align: 'center'}          
            ],
        buttons : [
            {display: '<i class="fa fa-trash"></i>批量删除', name : 'delete', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operate }
        ],
        searchitems : [
           {display : '咨询人',name : 'member_name'},
           {display : '咨询内容',name : 'mc_content'}
           ],
        sortname: "mc_id",
        sortorder: "desc",
        title: '平台客服咨询列表'
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
function fg_operate(name, grid) {
    if (name == 'delete') {
        if($('.trSelected',grid).length>0){
            var itemlist = new Array();
			$('.trSelected',grid).each(function(){
				itemlist.push($(this).attr('data-id'));
			});
            fg_delete(itemlist);
        } else {
            return false;
        }
    }
}

function fg_delete(id) {
	if (typeof id == 'number') {
    	var id = new Array(id.toString());
	};
	if(confirm('删除后将不能恢复，确认删除这 ' + id.length + ' 项吗？')){
		id = id.join(',');
	} else {
        return false;
    }
	window.location.href ='index.php?act=mall_consult&op=del_consult&del_id='+id;
}
</script> 
