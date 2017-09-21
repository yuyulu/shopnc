<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_circle_informnamage'];?></h3>
        <h5><?php echo $lang['nc_circle_informnamage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=circle_inform&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '举报ID', name : 'inform_id', width : 40, sortable : true, align: 'left'},
            {display: '被举报主题', name : 'theme_name', width : 200, sortable : true, align: 'left'},
            {display: '问题描述', name : 'inform_content', width : 150, sortable : true, align: 'left'},
            {display: '举报时间', name : 'inform_time', width : 120, sortable : true, align: 'left'},
            {display: '举报状态', name : 'inform_state', width : 60, sortable : true, align: 'left'},
            {display: '举报人', name : 'member_name', width : 80, sortable : true, align: 'left'},
            {display: '举报人ID', name : 'member_id', width : 40, sortable : true, align: 'left'},
            {display: '所属圈子', name : 'circle_name', width : 100, sortable : true, align: 'left'},
            {display: '圈子ID', name : 'circle_id', width : 40, sortable : true, align: 'left'},
            {display: '处理人名称', name : 'inform_opname', width : 80, sortable : true, align: 'left'},
            {display: '处理人ID', name : 'inform_opid', width : 60, sortable : true, align: 'left'},
            {display: '奖励经验', name : 'inform_opexp', width : 60, sortable : true, align: 'left'},
            {display: '处理结果', name : 'inform_opresult', width : 150, sortable : true, align: 'left'}
            ],
        buttons : [
            {display: '<i class="fa fa-trash"></i>批量删除', name : 'del', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operation }
           ],
        searchitems : [
            {display: '举报ID', name : 'inform_id'},
            {display: '问题描述', name : 'inform_content'},
            {display: '举报人', name : 'member_name'},
            {display: '举报人ID', name : 'member_id'},
            {display: '所属圈子', name : 'circle_name'},
            {display: '圈子ID', name : 'circle_id'},
            {display: '处理人名称', name : 'inform_opname'},
            {display: '处理人ID', name : 'inform_opid'}
            ],
        sortname: "inform_id",
        sortorder: "desc",
        title: '圈子举报列表'
    });
});

function fg_operation(name, bDiv) {
    if (name == 'del') {
        if ($('.trSelected', bDiv).length == 0) {
            showError('请选择要操作的数据项！');
        }
        var itemids = new Array();
        $('.trSelected', bDiv).each(function(i){
            itemids[i] = $(this).attr('data-id');
        });
        fg_del(itemids);
    }
}
function fg_del(ids) {
    if (typeof ids == 'number') {
        var ids = new Array(ids.toString());
    };
    id = ids.join(',');
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=circle_inform&op=inform_del', {id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script>