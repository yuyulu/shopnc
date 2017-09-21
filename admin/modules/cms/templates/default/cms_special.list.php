<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_cms_special_manage'];?></h3>
        <h5><?php echo $lang['nc_cms_special_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['cms_special_list_tip1'];?></li>
      <li>专题类型分为资讯和商城，资讯专题将出现在资讯频道内，商城专题出现在商城使用商城统一风格</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script>
function update_flex(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=cms_special&op=cms_special_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '标题', name : 'special_title', width : 250, sortable : false, align: 'center'},
            {display: '类型', name : 'special_type_text', width : 50, sortable : false, align: 'center'},
            {display: '专题封面图', name : 'special_image', width : 150, sortable : false, align: 'center'},
            {display: '状态', name : 'special_state',  width : 160, sortable : false, align: 'left'}
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增专题', name : 'add', bclass : 'add', title : '新增专题', onpress : fg_operation }
        ],
        usepager: true,
        rp: 15,
        title: '专题列表'
    });
}

$(function(){
    update_flex();
});
//删除专题
function fg_operation_del(special_id){
    if (confirm('确定删除？')) {
        window.location.href = 'index.php?act=cms_special&op=cms_special_drop&special_id='+special_id;
    }
}
function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=cms_special&op=cms_special_add';
    }
}
</script>