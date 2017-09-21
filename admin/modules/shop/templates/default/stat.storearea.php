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
      <li>统计图展示了店铺分类下到某个时间为止（默认为当前时间）开店数量在各省级地区的分布情况</li>
      <li>统计地图将根据各个区域的开店数量统计数据等级依次显示不同的颜色</li>
    </ul>
  </div>
  <div id="container_storenum" style="height:600px; width:90%; margin: 0 auto;">
    <div class="stat-map-color">高&nbsp;&nbsp;<span style="background-color: #fd0b07;">&nbsp;</span><span style="background-color: #ff9191;">&nbsp;</span><span style="background-color: #f7ba17;">&nbsp;</span><span style="background-color: #fef406;">&nbsp;</span><span style="background-color: #25aae2;">&nbsp;</span>&nbsp;&nbsp;低
      <p>备注：按照排名由高到低显示：排名第1、2、3名为第一阶梯；排名第4、5、6名为第二阶梯；排名第7、8、9为第三阶梯；排名第10、11、12为第四阶梯；其余为第五阶梯。</p>
    </div>
  </div>

  <!-- 统计列表 -->
  <table class="flex-table">
    <thead>
      <tr>
        <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
        <th width="60" align="center" class="handle-s">操作</th>
        <th width="60" align="center">序号</th>
        <th width="150" align="center">省份</th>
        <th width="150" align="center">该地区店铺数量</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['statlist'])){ ?>
      <?php foreach($output['statlist'] as $k => $v){?>
      <tr>
        <td class="sign"><i class="ico-check"></i></td>
        <td class="handle-s"><a href="<?php echo $output['actionurl']."&provid={$v['province_id']}";?>" class="btn green"><i class="fa fa-list-alt"></i>查看</a></td>
        <td><?php echo $v['sort'];?></td>
        <td><?php echo $v['provincename'];?></td>
        <td><?php echo $v['storenum'];?></td>
        <td></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td class="no-data" colspan="100"><i class="fa fa-exclamation-triangle"></i><?php echo $lang['nc_no_record'];?></td>
      </tr>
      <?php }?>
    </tbody>
  </table>
  <div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="ncap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" action="index.php" name="formSearch" id="formSearch">
      <input type="hidden" name="act" value="stat_store" />
      <input type="hidden" name="op" value="storearea" />
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
          <dl>
            <dt>截止时间</dt>
            <dd>
              <label>
                <input class="s-input-txt" type="text" value="<?php echo ($t = $_GET['search_time'])?$t:@date('Y-m-d',time());?>" id="search_time" name="search_time">
              </label>
            </dd>
          </dl>
        </div>
      </div>
      <div class="bottom"> <a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green">提交查询</a> </div>
    </form>
  </div>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/statistics.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_RESOURCE_URL;?>/js/map/jquery.vector-map.css"/>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/map/jquery.vector-map.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/map/china-zh.js"></script>
<script>
$(function () {

	//同步加载flexigrid表格
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
		});

	$('#search_time').datepicker({dateFormat: 'yy-mm-dd'});

	$('#searchBarOpen').click();
	$('#ncsubmit').click(function(){
    	$('#formSearch').submit();
    });
    //地图
	getMap(<?php echo $output['stat_json']; ?>,'container_storenum');
});
</script>