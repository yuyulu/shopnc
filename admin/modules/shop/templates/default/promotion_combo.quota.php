<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>商品列表</h3>
        <h5>商城推荐组合促销活动设置与管理</h5>
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
      <li>商家购买预售商品促销活动套餐列表。</li>
    </ul>
  </div>

  <div id="flexigrid"></div>
</div>

<script>
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=promotion_combo&op=get_quota_xml',
        colModel: [
            {display: '操作', name: 'operation', width: 60, sortable: false, align: 'center', className: 'handle-s'},
            {display: '店铺ID', name: 'store_id', width: 120, sortable: false, align: 'center'},
            {display: '店铺名称', name: 'store_name', width: 200, sortable: false, align: 'left'},
            {display: '开始时间', name: 'cq_starttime', width: 120, sortable: false, align: 'center'},
            {display: '结束时间', name: 'cq_endtime', width: 120, sortable: false, align: 'center'}
        ],
        searchitems: [
            {display: '店铺ID', name: 'store_id'},
            {display: '店铺名称', name: 'store_name'}
        ],
        sortname: "cq_endtime",
        sortorder: "desc",
        title: '预售商品套餐列表'
    });
});

</script>
