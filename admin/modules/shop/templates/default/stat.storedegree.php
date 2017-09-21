<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>店铺统计</h3>
        <h5>平台针对店铺的各项数据统计</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>统计图展示各店铺分类中店铺等级的分布情况</li>
    </ul>
  </div>
  <div class="ncap-stat-chart">
    <?php if ($output['stat_json']){ ?>
    <div id="container" style="height:400px"></div>
    <?php } else {?>
    <div class="no-date"><i class="fa fa-exclamation-triangle"></i><?php echo $lang['nc_no_record'];?></div>
    <?php } ?>
  </div>
  <div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="ncap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" action="index.php" name="formSearch" id="formSearch">
      <input type="hidden" name="act" value="stat_store" />
      <input type="hidden" name="op" value="degree" />
      <div id="searchCon" class="content">
        <div class="layout-box">
          <dl>
            <dt>按店铺分类筛选</dt>
            <dd>
              <label>
              <select name="search_sclass" id="search_sclass" class="s-select">
                <option value="">-请选择-</option>
                <?php foreach ($output['store_class'] as $k => $v){ ?>
                <option value="<?php echo $v['sc_id'];?>" <?php echo $_REQUEST['search_sclass'] == $v['sc_id']?'selected':''; ?>><?php echo $v['sc_name'];?></option>
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
  <script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/highcharts.js"></script>
</div>
<script>
$(function(){
	<?php if ($output['stat_json']){ ?>
	$('#container').highcharts(<?php echo $output['stat_json']; ?>);
	<?php } ?>

	$('#searchBarOpen').click();

	$('#ncsubmit').click(function(){
    	$('#formSearch').submit();
    });
})
</script>