<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_goods_evaluate']; ?></h3>
        <h5><?php echo $lang['nc_goods_evaluate_subhead']; ?></h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['admin_evalstore_help1'];?></li>
      <li><?php echo $lang['admin_evalstore_help2'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<form id="submit_form" action="<?php echo urlAdminShop('evaluate', 'evalstore_del');?>" method="post">
  <input id="seval_id" name="seval_id" type="hidden">
</form>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/jquery.raty.min.js"></script> 
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=evaluate&op=get_store_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '评价人', name : 'seval_membername', width : 70, sortable : true, align: 'left'},
            {display: '描述相符', name : 'seval_desccredit', width : 90, sortable : false, align: 'center'},
            {display: '服务态度', name : 'seval_servicecredit', width : 90, sortable : false, align: 'center'},
            {display: '发货速度', name : 'seval_deliverycredit', width : 90, sortable : false, align: 'center'},
            {display: '被评商家', name : 'seval_storename', width : 150, sortable : true, align: 'left'},
            {display: '评价时间', name : 'seval_id', width : 80, sortable : true, align: 'center'},
            {display: '订单编号', name : 'seval_orderno', width : 120, sortable : true, align: 'center'},
            {display: '评价人ID', name : 'seval_memberid', width : 60, sortable : true, align: 'center'},
            {display: '商家ID', name : 'seval_storeid', width : 40, sortable : true, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-trash"></i>批量删除', name : 'delete', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operate }
        ],
        searchitems : [
            {display: '评价人', name : 'seval_membername'},
            {display: '被评商家', name : 'seval_storename'}
        ],
        sortname: "seval_id",
        sortorder: "desc",
        title: '店铺评分列表',
        onSuccess : function(){
            $('.raty').raty({
                path: "<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/img",
                readOnly: true,
                score: function() {
                  return $(this).attr('data-score');
                }
            });
        }
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
        url: "index.php?act=evaluate&op=evalstore_del",
        data: "seval_id="+id,
        success: function(data){
            if (data.state){
                $("#flexigrid").flexReload();
            } else {
            	alert(data.msg);
            }
        }
    });
}
    $(document).ready(function(){
        $('#stime').datepicker({dateFormat: 'yy-mm-dd'});
        $('#etime').datepicker({dateFormat: 'yy-mm-dd'});

        $('.raty').raty({
            path: "<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/img",
            readOnly: true,
            score: function() {
              return $(this).attr('data-score');
            }
        });

        $('[nctype="btn_del"]').on('click', function() {
            if(confirm("<?php echo $lang['nc_ensure_del'];?>")) {
                var seval_id = $(this).attr('data-seval-id');
                $('#seval_id').val(seval_id);
                $('#submit_form').submit();
            }
        });
    });
</script> 
