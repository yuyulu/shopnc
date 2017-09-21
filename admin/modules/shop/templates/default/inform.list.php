<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['inform_manage_title'];?></h3>
        <h5><?php echo $lang['inform_manage_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
    </div>
    <ul>
      <li><?php echo $lang['inform_help1'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=inform&op=get_xml&type=<?php echo $_GET['type'];?>',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '举报人', name : 'inform_member_name', width : 70, sortable : false, align: 'left'},
            {display: '举报类型', name : 'inform_subject_type_id', width : 200, sortable : false, align: 'left'},
            {display: '举报主题', name : 'inform_subject_id', width: 200, sortable : false, align : 'left'},
            {display: '举报商品', name : 'inform_goods_name', width : 150, sortable : false, align: 'left'},
            {display: '图片', name : 'inform_pic', width : 100, sortable : false, align : 'center'},
            {display: '举报时间', name : 'inform_datetime', width : 120, sortable : true, align: 'center'},
            <?php if ($_GET['type'] == '') { ?>
            {display: '处理结果', name : 'inform_handle_type', width : 150, sortable : false, align: 'left'},
            {display: '处理信息', name : 'inform_handle_message', width : 150, sortable : false, align: 'left'},
            <?php } ?>
            {display: '涉及商家', name : 'inform_store_name', width : 100, sortable : false, align: 'left'},
            {display: '举报人ID', name : 'inform_member_id', width : 60, sortable : true, align: 'center'},
            {display: '商品ID', name : 'inform_goods_id', width : 60, sortable : true, align: 'center'},
            {display: '商家ID', name : 'inform_store_id', width : 40, sortable : true, align: 'center'}
            ],
        searchitems : [
           {display: '商品名称', name : 'inform_goods_name', isdefault: true},
           {display: '举报人', name : 'inform_member_name'},
           {display: '商家名称', name : 'inform_store_name'},
           {display: '举报类型', name : 'inform_type'},
           {display: '举报主题', name : 'inform_subject'}
           ],
        sortname: "inform_id",
        sortorder: "<?php echo $_GET['type'] == 'waiting' ? 'asc' : 'desc';?>",
        title: '<?php echo $_GET['type'] == 'waiting' ? '待处理的举报列表' : '已处理的举报列表';?>'
    });
});
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
	$.ajax({
        type: "GET",
        dataType: "json",
        url: "index.php?act=consulting&op=delete",
        data: "consult_id="+id,
        success: function(data){
            if (data.state){
                $("#flexigrid").flexReload();
            } else {
            	alert(data.msg);
            }
        }
    });
}
</script> 
