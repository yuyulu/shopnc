<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['goods_class_index_class'];?></h3>
        <h5><?php echo $lang['goods_class_index_class_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
    </div>
    <ul>
      <li><?php echo $lang['goods_class_tag_prompts_two'];?></li>
      <li><?php echo $lang['goods_class_tag_prompts_three'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<div id="dialog" style="display: none; top: 344px; left: 430px;"><?php echo $lang['goods_class_tag_update_prompt'];?></div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=goods_class&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: 'TAG ID', name : 'gc_tag_id', width : 60, sortable : true, align: 'left'},
            {display: 'TAG名称', name : 'gc_tag_name', width : 160, sortable : true, align: 'left'},
            {display: 'TAG值', name : 'gc_tag_value', width : 160, sortable : true, align: 'left'},
            {display: '一级分类ID', name : 'gc_id_1', width : 150, sortable : true, align: 'left'},
            {display: '二级分类ID', name : 'gc_id_2', width : 150, sortable : true, align: 'left'},
            {display: '三级分类ID', name : 'gc_id_3', width : 150, sortable : true, align: 'left'}
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>更新TAG名称', name : 'update', bclass : 'update', title : '更新TAG名称', onpress : fg_operation },
            {display: '<i class="fa fa-plus"></i>导入/重置TAG值', name : 'reset', bclass : 'reset', title : '导入/重置TAG值', onpress : fg_operation }
        ],
        searchitems : [
            {display: 'TAG值', name : 'gc_tag_value'},
            {display: '一级分类ID', name : 'gc_id_1'},
            {display: '二级分类ID', name : 'gc_id_2'},
            {display: '三级分类ID', name : 'gc_id_3'}
            ],
        sortname: "gc_tag_id",
        sortorder: "dessc",
        title: 'TAG列表'
    });
});

function fg_operation(name, bDiv) {
    if (name == 'update') {
        window.location.href = 'index.php?act=goods_class&op=tag_update';
    }
    if (name == 'reset') {
        window.location.href = 'index.php?act=goods_class&op=tag_reset';
    }
}
function fg_edit(id) {
    CUR_DIALOG = ajax_form('tag_edit', '编辑TAG', 'index.php?act=goods_class&op=tag_edit&id='+id, 640);
}
</script>