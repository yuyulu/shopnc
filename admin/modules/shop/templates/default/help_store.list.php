<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>店铺帮助</h3>
        <h5>商品店铺帮助类型与文章管理</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="JavaScript:void(0);" class="current"><?php echo '帮助内容';?></a></li>
        <li><a href="index.php?act=help_store&op=help_type"><?php echo '帮助类型';?></a></li>
      </ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>帮助内容排序显示规则为排序小的在前，新增内容的在前</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=help_store&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '帮助ID', name : 'help_id', width : 40, sortable : true, align: 'left'},
            {display: '排序', name : 'help_sort', width : 40, sortable : true, align: 'left'},
            {display: '帮助标题', name : 'help_title', width : 150, sortable : true, align: 'left'},
            {display: '帮助类型', name : 'type_id', width : 120, sortable : true, align: 'left'},
            {display: '更新时间', name : 'update_time', width : 120, sortable : true, align: 'left'}
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operation }
        ],
        searchitems : [
            {display: '帮助标题', name : 'help_title'}
            ],
        sortname: "help_sort",
        sortorder: "asc",
        title: '店铺帮助列表'
    });
});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=help_store&op=add_help';
    }
}
function fg_del(id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=help_store&op=del_help', {id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script>