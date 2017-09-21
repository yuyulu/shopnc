<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=adv" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['adv_index_manage'];?> - 管理“<?php echo $output['ap_name'];?>”内的广告</h3>
        <h5><?php echo $lang['adv_index_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div id="flexigrid"></div>
</div>
<script>
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=adv&op=get_adv_xml&ap_id=<?php echo $_GET['ap_id'];?>',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '广告名称', name : 'adv_title', width : 150, sortable : false, align: 'left'}, 
			{display: '所在广告位', name : 'ap_id', width : 100, sortable : true, align : 'left'},           
			{display: '类型', name : 'ap_class', width : 120, sortable : false, align: 'left'},
			{display: '开始时间', name : 'adv_start_date', width : 100, sortable : true, align: 'center'},
			{display: '结束时间', name : 'adv_end_date', width: 100, sortable : true, align : 'center'}                                          
			],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operation },
            {display: '<i class="fa fa-trash"></i>批量删除', name : 'delete', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operation }
            ],
        searchitems : [
            {display: '广告名称', name : 'adv_title'}
            ],
        sortname: "adv_id",
        sortorder: "desc",
        title: '广告列表'
    });
});
function fg_delete(id){
	if (typeof id == 'number') {
    	var id = new Array(id.toString());
	};
	if(confirm('删除后将不能恢复，确认删除这 ' + id.length + ' 项吗？')){
		id = id.join(',');
    } else {
        return false;
    }
	$.ajax({
        type: "GET",
        dataType: "json",
        url: "index.php?act=adv&op=adv_delete",
        data: "ap_id=<?php echo $_GET['ap_id'];?>&del_id="+id,
        success: function(data){
            if (data.state){
                $("#flexigrid").flexReload();
            } else {
            	alert(data.msg);
            }
        }
    });
}
function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=adv&op=adv_add&ap_id=<?php echo $_GET['ap_id'];?>';
    }else if (name == 'delete') { 
        if($('.trSelected',bDiv).length>0){
            var items = $('.trSelected',bDiv);
            var itemlist = new Array();
            $('.trSelected',bDiv).each(function(){
            	itemlist.push($(this).attr('data-id'));
            });
            fg_delete(itemlist);
        }
    }
}
</script>