<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['rec_position'];?></h3>
        <h5><?php echo $lang['rec_position_subhead'];?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
    </div>
    <ul>
      <li><?php echo $lang['rec_ps_help1'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script>
function copyToClipBoard(id){
    if(window.clipboardData)
    { 
        // the IE-manier
        window.clipboardData.clearData();
        window.clipboardData.setData("Text", "<\?php echo rec("+id+");?>");
        alert("<?php echo $lang['rec_ps_clip_succ'];?>!");
    }
    else if(navigator.userAgent.indexOf("Opera") != -1)
    {
        window.location = "<\?php echo rec("+id+");?>";
        alert("<?php echo $lang['rec_ps_clip_succ'];?>!");
    }
    else
    {
        ajax_form('copy_rec', '<?php echo $lang['rec_ps_code'];?>', 'index.php?act=rec_position&op=rec_code&rec_id='+id);
    }
}
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=rec_position&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '标题', name : 'title', width : 200, sortable : false, align: 'left'}, 
			{display: '类型', name : 'pic_type', width : 120, sortable : false, align : 'left'},           
			{display: '内容', name : 'content', width : 200, sortable : false, align: 'left'},
			{display: '跳转地址', name : 'url', width : 180, sortable : false, align: 'left'},
			{display: '是否弹出', name : 'target', width: 60, sortable : false, align : 'center'}
            ],
            buttons : [
            {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operation },
            {display: '<i class="fa fa-trash"></i>批量删除', name : 'delete', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operation }
            ],
        searchitems : [
            {display: '全部', name : ''},
            {display: '图片', name : '1'},
            {display: '文字', name : '0'}
            ],
        sortname: "rec_id",
        sortorder: "desc",
        title: '推荐位列表',
        onSuccess : function(){
        	$('a[nctype="jscode"]').click(function(){
        		copyToClipBoard($(this).attr('rec_id'));return ;		
        	});
     	}
    });
});
function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=rec_position&op=rec_add';
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
        url: 'index.php?act=rec_position&op=rec_del',
        data: "rec_id="+id,
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
