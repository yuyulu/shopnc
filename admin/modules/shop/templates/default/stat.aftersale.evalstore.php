<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>售后分析</h3>
        <h5>平台针对订单售后服务的各项数据统计</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>统计列表为符合条件店铺的动态评分信息列表，并可以点击列表上方的“导出数据”将列表数据导出为Excel文件</li>
      <li>默认按照“描述相符度”降序排列</li>
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
      <div id="searchCon" class="content">
        <div class="layout-box">
          <dl>
            <dt>店铺名称</dt>
            <dd>
              <label>
                <input class="s-input-txt" type="text" value="<?php echo $output['search_arr']['storename'];?>" id="storename" name="storename" placeholder="请输入店铺名称" />
              </label>
            </dd>
          </dl>
          <dl>
            <dt>按店铺分类筛选</dt>
            <dd>
              <label>
              <select name="store_class" id="store_class" class="s-select">
                <option value="">-请选择-</option>
                <?php if(is_array($output['class_list'])){ ?>
                <?php foreach($output['class_list'] as $k => $v){ ?>
                <option <?php if(intval($_GET['store_class']) == $v['sc_id']){ ?>selected="selected"<?php } ?> value="<?php echo $v['sc_id']; ?>"><?php echo $v['sc_name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
              </label>
            </dd>
          </dl>
        </div>
      </div>
      <div class="bottom"> <a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green">提交查询</a> </div>
    </form>
  </div>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/statistics.js"></script>
<script>
function update_flex(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=stat_aftersale&op=get_evalstore_xml&'+$("#formSearch").serialize(),
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '店铺名称', name : 'seval_storename', width : 150, sortable : false, align: 'center'},
            {display: '描述相符度', name : 'avgdesccredit',  width : 120, sortable : true, align: 'center'},
            {display: '服务态度', name : 'avgservicecredit',  width : 120, sortable : true, align: 'center'},
            {display: '发货速度', name : 'avgdeliverycredit',  width : 120, sortable : true, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'excel', bclass : 'csv', title : '导出EXCEL文件', onpress : fg_operation }
        ],
        sortname: "avgdesccredit",
        sortorder: "desc",
        usepager: true,
        rp: 15,
        title: '店铺动态评分'
    });
}
$(function () {
	update_flex();
	$('#ncsubmit').click(function(){
	    $('.flexigrid').after('<div id="flexigrid"></div>').remove();
	    update_flex();
    });

	$('#searchBarOpen').click();
});
function fg_operation(name, bDiv){
    var stat_url = 'index.php?act=stat_aftersale&op=evalstore';
    get_search_excel(stat_url,bDiv);
}
</script>