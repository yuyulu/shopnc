<?php defined('In33hao') or exit('Access Invalid!'); ?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>自营店铺</h3>
        <h5>商城自营店铺相关设置与管理</h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>平台在此处统一管理自营店铺，可以新增、编辑、删除平台自营店铺</li>
      <li>可以设置未绑定全部商品类目的平台自营店铺的经营类目</li>
      <li>已经发布商品的自营店铺不能被删除</li>
      <li>删除平台自营店铺将会同时删除店铺的相关图片以及相关商家中心账户，请谨慎操作！</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=ownshop&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '店铺ID', name : 'store_id', width : 40, sortable : true, align: 'center'},
            {display: '店铺名称', name : 'store_name', width : 150, sortable : false, align: 'left'},
            {display: '店主账号', name : 'member_id', width : 120, sortable : true, align: 'left'},
            {display: '商家账号', name : 'seller_name', width : 120, sortable : false, align: 'left'},            
            {display: '当前状态', name : 'store_state', width : 80, sortable : true, align: 'center'},
            {display: '绑定所有类目', name : 'bind_all_gc', width : 120, sortable : false, align : 'left'}
            ],
        buttons : [
			{display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '添加一条新数据到列表', onpress : fg_operation }
        ],
        searchitems : [
            {display: '店铺名称', name : 'store_name', isdefault: true},
            {display: '店主账号', name : 'member_name'},
            {display: '商家账号', name : 'seller_name'}
            ],
        sortname: "store_id",
        sortorder: "asc",
        title: '店铺列表'
    });
});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=ownshop&op=add';
    }
}

function fg_delete(id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=ownshop&op=del', {id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script>
