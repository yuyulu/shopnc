<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_type_manage'];?></h3>
        <h5><?php echo $lang['nc_type_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['type_index_prompts_one'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=type&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '类型ID', name : 'type_id', width : 40, sortable : true, align: 'left'},
            {display: '类型名称', name : 'type_name', width : 120, sortable : true, align: 'left'},
            {display: '类型排序', name : 'type_sort', width : 120, sortable : true, align: 'center'},
            {display: '快捷定位ID', name : 'class_id', width : 150, sortable : true, align: 'center'},
            {display: '快捷定位名称', name : 'class_name', width : 120, sortable : true, align: 'left'}
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operation }
        ],
        searchitems : [
            {display: '类型ID', name : 'like_type_id'},
            {display: '类型名称', name : 'like_type_name'},
            {display: '快捷定位ID', name : 'class_id'},
            {display: '快捷定位名称', name : 'class_name'}
            ],
        sortname: "type_id",
        sortorder: "asc",
        title: '类型列表'
    });
});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=type&op=type_add';
    }
}
function fg_del(id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=type&op=type_del', {id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script>