<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['adv_index_manage'];?></h3>
        <h5><?php echo $lang['adv_index_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['adv_help2'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script>
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=adv&op=get_ap_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '名称', name : 'ap_name', width : 120, sortable : false, align: 'left'}, 
			{display: '类型', name : 'ap_class', width : 50, sortable : true, align : 'center'},           
			{display: '展示方式', name : 'ap_display', width : 140, sortable : true, align: 'left'},
			{display: '宽度/字数', name : 'ap_width', width : 140, sortable : true, align: 'left'},
			{display: '高度', name : 'ap_height', width: 60, sortable : true, align : 'center'},                                           
			{display: '广告数', name : 'ap_count', width: 60, sortable : true, align : 'center'},
            {display: '正在展示', name : 'ap_now_count', width : 140, sortable : false, align: 'left'},
			{display: '是否启用', name : 'payment_time', width: 140, sortable : true, align : 'left'}
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operation },
            {display: '<i class="fa fa-trash"></i>批量删除', name : 'delete', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operation }
            ],
        searchitems : [
            {display: '广告位名称', name : 'ap_name'}
            ],
        sortname: "ap_id",
        sortorder: "desc",
        title: '广告位列表'
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
        url: "index.php?act=adv&op=delete",
        data: "del_id="+id,
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
        window.location.href = 'index.php?act=adv&op=ap_add';
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
//弹出复制代码框
function copyToClipBoard(id)
{
   ajax_form('copy_adv', '<?php echo $lang['ap_get_js'];?>', 'index.php?act=adv&op=ap_copy&id='+id);
}
</script> 
