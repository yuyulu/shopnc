<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_spec_manage'];?></h3>
        <h5><?php echo $lang['nc_spec_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
    </div>
    <ul>
      <li><?php echo $lang['spec_index_prompts_one'];?></li>
      <li><?php echo $lang['spec_index_prompts_two'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=spec&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '规格ID', name : 'sp_id', width : 40, sortable : true, align: 'left'},
            {display: '规格名称', name : 'sp_name', width : 120, sortable : true, align: 'left'},
            {display: '规格排序', name : 'sp_sort', width : 120, sortable : true, align: 'center'},
            {display: '快捷定位ID', name : 'class_id', width : 150, sortable : true, align: 'center'},
            {display: '快捷定位名称', name : 'class_name', width : 120, sortable : true, align: 'left'}
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operation }
        ],
        searchitems : [
            {display: '规格ID', name : 'like_spec_id'},
            {display: '规格名称', name : 'like_spec_name'},
            {display: '快捷定位ID', name : 'class_id'},
            {display: '快捷定位名称', name : 'class_name'}
            ],
        sortname: "sp_id",
        sortorder: "asc",
        title: '规格列表'
    });
});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=spec&op=spec_add';
    }
}
function fg_del(id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=spec&op=spec_del', {id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script>