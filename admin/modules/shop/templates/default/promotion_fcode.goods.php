<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>F码商品</h3>
        <h5>商城F码商品促销活动设置与管理</h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
    </div>
    <ul>
      <li>商家发布的F码商品列表。</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>

<script>
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=promotion_fcode&op=get_goods_xml',
        colModel: [
            {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
            {display: '商品SKU', name: 'goods_id', width: 60, sortable: true, align: 'left'},
            {display: '商品名称', name: 'goods_name', width: 400, sortable: true, align: 'left'},
            {display: '店铺ID', name: 'store_id', width: 60, sortable: true, align: 'left'},
            {display: '店铺名称', name: 'store_name', width: 100, sortable: true, align: 'left'}
        ],
        searchitems: [
            {display: '商品名称', name: 'goods_name', isdefault: true}
        ],
        sortname: "goods_id",
        sortorder: "desc",
        title: 'F码商品列表'
    });
});

function fg_del(id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=promotion_fcode&op=del_goods', {id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script>
