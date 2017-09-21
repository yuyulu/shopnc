<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_store_manage'];?></h3>
        <h5><?php echo $lang['nc_store_manage_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
    </div>
    <ul>
      <li>此处可以对商家分销申请进行查看/审核/删除 操作。</li>
      <li>审核后，该商家就可以进行分销商品的设置。</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=store&op=get_distribution_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '申请ID', name : 'distri_id', width : 40, sortable : true, align: 'center'},
            {display: '店铺ID', name : 'distri_store_id', width: 60, sortable : true, align : 'center'},                        
            {display: '店铺名称', name : 'distri_store_name', width : 120, sortable : false, align: 'center'},
			{display: '店主名称', name : 'distri_seller_name', width : 120, sortable : false, align: 'center'},
            {display: '申请状态', name : 'distri_state', width : 100, sortable : true, align: 'center'},
            {display: '申请时间', name : 'distri_create_time', width : 100, sortable : true, align: 'center'},
            ],
        searchitems : [
            {display: '店铺ID', name : 'distri_store_id', isdefault: true},
            {display: '店铺名称', name : 'distri_store_name', isdefault: true}
            ],
        sortname: "distri_id",
        sortorder: "desc",
        title: '店铺分销申请列表'
    });
});

function distribution_check(id) {
    if(confirm('审核后，该店铺就可以发布分销产品\n确认审核吗？')){
        $.getJSON('index.php?act=store&op=distribution_check', {id:id}, function(data){
            if (data.state) {
                showSucc(data.msg)
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
function distribution_del(id, store_id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=store&op=distribution_del', {id:id, store_id:store_id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script> 