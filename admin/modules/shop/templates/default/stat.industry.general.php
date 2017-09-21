<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_statindustry'];?></h3>
        <h5>平台根据商品分类对行业进行各项分析</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['stat_validorder_explain'];?></li>
      <li>列表展示了搜索类目下子分类的商品数和从昨天开始最近30天该子分类有效订单的销售数据，并可以点击列表上方的“导出数据”将列表数据导出为Excel文件</li>
      <li>默认按照“销售额”降序排列</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
  <div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="ncap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" action="index.php" name="formSearch" id="formSearch">
      <input type="hidden" id="choose_gcid" name="choose_gcid" value="0"/>
      <div id="searchCon" class="content">
        <div class="layout-box">
          <dl>
            <dt>按商品分类筛选</dt>
            <dd id="searchgc_td"> </dd>
          </dl>
        </div>
      </div>
      <div class="bottom"> <a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green">提交查询</a> </div>
    </form>
  </div>
  <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script>
  <script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/statistics.js"></script>
</div>
<script>
function update_flex(){
    var choose_gcid = $("#choose_gcid").val();
    $("#flexigrid").flexigrid({
        url: 'index.php?act=stat_industry&op=get_general_xml&choose_gcid='+choose_gcid,
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '类目名称', name : 'gc_name', width : 150, sortable : false, align: 'center'},
            {display: '平均价格（元）', name : 'priceavg', title : '类目下所有商品的平均单价', width : 120, sortable : true, align: 'center'},
            {display: '有销量商品数', name : 'ordergcount', title : '类目下从昨天开始最近30天有效订单中有销量的商品总数', width : 120, sortable : true, align: 'center'},
            {display: '销量', name : 'ordergnum', title : '类目下从昨天开始最近30天有效订单中商品总售出件数', width : 120, sortable : true, align: 'center'},
            {display: '销售额（元）', name : 'orderamount', title : '类目下从昨天开始最近30天有效订单中商品总销售额', width : 120, sortable : true, align: 'center'},
            {display: '商品总数', name : 'goodscount', title : '类目下所有商品的数量', width: 120, sortable : true, align : 'center'},
            {display: '无销量商品数', name : 'unordergcount', title : '类目下从昨天开始最近30天无销量的商品总数', width : 120, sortable : true, align : 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'excel', bclass : 'csv', title : '导出EXCEL文件', onpress : fg_operation }
        ],
        sortname: "orderamount",
        sortorder: "desc",
        usepager: false,
        rp: 99,
        title: '概况总览'
    });
}
$(function () {
    $('#ncsubmit').click(function(){
        $('.flexigrid').after('<div id="flexigrid"></div>').remove();
    	update_flex();
    });

	$('#searchBarOpen').click();
	//商品分类
	init_gcselect(<?php echo $output['gc_choose_json'];?>,<?php echo $output['gc_json']?>);

	//加载统计列表
	update_flex();
});
function fg_operation(name, bDiv){
    var stat_url = 'index.php?act=stat_industry&op=general_list';
    get_search_excel(stat_url,bDiv);
}
</script>