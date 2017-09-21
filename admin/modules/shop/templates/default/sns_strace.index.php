<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['admin_snsstore_manage'];?></h3>
        <h5><?php echo $lang['admin_snsstore_manage_subhead'];?></h5>
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
      <li><?php echo $lang['admin_snstrace_tracelisttip1'];?></li>
      <li><?php echo $lang['admin_snstrace_tracelisttip2'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=sns_strace&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '动态标题', name : 'strace_title', width : 200, sortable : true, align: 'left'},
            {display: '店铺名称', name : 'strace_storename', width : 120, sortable : true, align: 'left'},
            {display: '店铺ID', name : 'strace_storeid', width : 40, sortable : true, align: 'left'},
            {display: '显示', name : 'strace_state', width : 60, sortable : true, align: 'center'},
            {display: '发表时间', name : 'strace_time', width : 120, sortable : true, align: 'center'},
            {display: '转播数量', name : 'strace_spread', width : 60, sortable : true, align: 'left'},
            {display: '评论数量', name : 'strace_comment', width : 60, sortable : true, align: 'left'}
            ],
        buttons : [
            {display: '<i class="fa fa-comments-o"></i>全部评论', name : 'add', bclass : 'add', title : '全部评论', onpress : fg_operation }
        ],
        searchitems : [
            {display: '动态标题', name : 'strace_title'},
            {display: '店铺名称', name : 'strace_storename'},
            {display: '店铺ID', name : 'strace_storeid'}
            ],
        sortname: "strace_time",
        sortorder: "desc",
        title: '店铺动态列表'
    });
});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=sns_strace&op=scomm_list';
    }
}
function fg_del(id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=sns_strace&op=strace_del', {id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script>