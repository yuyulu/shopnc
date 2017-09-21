<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['feedback_mange_title'];?></h3>
        <h5><?php echo $lang['feedback_mange_title_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
    </div>
    <ul>
      <li>来自用户的反馈</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>

<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=mb_feedback&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '反馈ID', name : 'id', width : 40, sortable : true, align: 'left'},
            {display: '反馈内容', name : 'content', width : 300, sortable : true, align: 'left'},
            {display: '反馈时间', name : 'ftime', width : 150, sortable : true, align: 'center'},
            {display: '反馈人', name : 'member_name', width : 120, sortable : true, align: 'left'},
            {display: '反馈人ID', name : 'member_id', width : 60, sortable : true, align: 'left'}
            ],
        buttons : [
            {display: '<i class="fa fa-trash"></i>批量删除', name : 'del', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operation }
           ],
        searchitems : [
            {display: '反馈内容', name : 'content'},
            {display: '反馈人', name : 'member_name'},
            {display: '反馈人ID', name : 'member_id'}
            ],
        sortname: "id",
        sortorder: "desc",
        title: '意见反馈列表'
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
        $.getJSON('index.php?act=mb_feedback&op=del', {id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script> 
