<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>店铺帮助</h3>
        <h5>商品店铺帮助类型与文章管理</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="index.php?act=help_store&op=help_store"><?php echo '帮助内容';?></a></li>
        <li><a href="JavaScript:void(0);" class="current"><?php echo '帮助类型';?></a></li>
      </ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>系统初始化的类型不能删除</li>
      <li>帮助类型排序显示规则为排序小的在前，新增的在前</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=help_store&op=get_type_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '类型ID', name : 'type_id', width : 40, sortable : true, align: 'left'},
            {display: '类型名称', name : 'type_name', width : 120, sortable : true, align: 'left'},
            {display: '类型排序', name : 'type_sort', width : 80, sortable : true, align: 'left'},
            {display: '是否显示', name : 'help_show', width : 100, sortable : true, align: 'left'}
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operation }
        ],
        searchitems : [
            {display: '类型ID', name : 'type_id'},
            {display: '类型名称', name : 'type_name'}
            ],
        sortname: "type_sort",
        sortorder: "asc",
        title: '店铺帮助类型列表'
    });
});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=help_store&op=add_type';
    }
}

function fg_del(id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=help_store&op=del_type', {id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script>