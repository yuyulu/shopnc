<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['brand_index_brand'];?></h3>
        <h5><?php echo $lang['brand_index_brand_subhead'];?></h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="index.php?act=brand&op=brand"><?php echo $lang['nc_manage'];?></a></li>
        <li><a href="JavaScript:void(0);" class="current"><?php echo $lang['brand_index_to_audit'];?></a></li>
      </ul>
    </div>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=brand&op=get_xml&type=apply',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '品牌ID', name : 'brand_id', width : 40, sortable : true, align: 'center'},
            {display: '品牌名称', name : 'brand_name', width : 150, sortable : false, align: 'left'},
            {display: '首字母', name : 'brand_initial', width : 120, sortable : true, align: 'center'},
            {display: '品牌图片', name : 'brand_pic', width : 120, sortable : false, align: 'left'},
            {display: '品牌排序', name : 'brand_sort', width: 60, sortable : true, align : 'center'},
            {display: '品牌推荐', name : 'brand_recommend', width: 60, sortable : true, align : 'center'},
            {display: '展示形式', name : 'show_type', width : 80, sortable : true, align: 'center'}
            ],
        searchitems : [
            {display: '品牌ID', name : 'brand_id', isdefault: true},
            {display: '品牌名称', name : 'brand_name'},
            {display: '首字母', name : 'brand_initial'}
            ],
        sortname: "brand_id",
        sortorder: "desc",
        title: '品牌列表'
    });
});

function fg_apply(id) {
    if (!confirm('您确定要通过品牌申请吗？')) {
        return false;
    }

    $.getJSON('index.php?act=brand&op=brand_apply_set', {id:id}, function(data){
        if (data.state) {
            $("#flexigrid").flexReload();
        } else {
            showError(data.msg)
        }
    });
}

//删除
function fg_del(id) {
    if(!confirm('删除后将不能恢复，确认删除这项吗？')){
        return false;
    }
    $.getJSON('index.php?act=brand&op=brand_del', {id:id}, function(data){
        if (data.state) {
            $("#flexigrid").flexReload();
        } else {
            showError(data.msg)
        }
    });
}
</script>