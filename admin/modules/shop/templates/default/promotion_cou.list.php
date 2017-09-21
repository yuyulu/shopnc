<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <!-- 页面导航 -->
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>加价购</h3>
        <h5>店铺商品加价购促销活动设置及管理</h5>
      </div>
      <ul class="tab-base nc-row">
        <?php   foreach($output['menu'] as $menu) {  if($menu['menu_type'] == 'text') { ?>
        <li><a href="JavaScript:void(0);" class="current"><?php echo $menu['menu_name'];?></a></li>
        <?php }  else { ?>
        <li><a href="<?php echo $menu['menu_url'];?>" ><?php echo $menu['menu_name'];?></a></li>
        <?php  } }  ?>
      </ul>
    </div>
  </div>

  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom">
      <i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
    </div>
    <ul>
      <li>商家发布的加价购列表 </li>
      <li>取消操作不可恢复，请慎重操作 </li>
      <li>点击详细链接查看活动详细信息 </li>
    </ul>
  </div>

  <div id="flexigrid"></div>

    <div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
    <div class="ncap-search-bar">
      <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
      <div class="title">
        <h3>高级搜索</h3>
      </div>
      <form method="get" name="formSearch" id="formSearch">
        <input type="hidden" name="advanced" value="1" />
        <div id="searchCon" class="content">
          <div class="layout-box">
            <dl>
              <dt>活动名称</dt>
              <dd>
                <input type="text" name="name" class="s-input-txt" placeholder="请输入活动名称关键字" />
              </dd>
            </dl>
            <dl>
              <dt>店铺名称</dt>
              <dd>
                <input type="text" name="store_name" class="s-input-txt" placeholder="请输入店铺名称关键字" />
              </dd>
            </dl>
            <dl>
              <dt>状态</dt>
              <dd>
                <select name="state" class="s-select">
                    <?php foreach ((array) $output['couStates'] as $sk => $sv) { ?>
                    <option value="<?php echo $sk; ?>"><?php echo $sv; ?></option>
                    <?php } ?>
                </select>
              </dd>
            </dl>
            <dl>
              <dt>活动时期筛选</dt>
              <dd>
                <label>
                    <input type="text" name="pdate1" data-dp="1" class="s-input-txt" placeholder="结束时间不晚于" />
                </label>
                <label>
                    <input type="text" name="pdate2" data-dp="1" class="s-input-txt" placeholder="开始时间不早于" />
                </label>
              </dd>
            </dl>
          </div>
        </div>
        <div class="bottom">
          <a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green">提交查询</a>
          <a href="javascript:void(0);" id="ncreset" class="ncap-btn ncap-btn-orange" title="撤销查询结果，还原列表项所有内容"><i class="fa fa-retweet"></i><?php echo $lang['nc_cancel_search'];?></a>
        </div>
      </form>
    </div>

</div>

<script>
$(function() {
    var flexUrl = 'index.php?act=promotion_cou&op=cou_list_xml';

    $("#flexigrid").flexigrid({
        url: flexUrl,
        colModel: [
            {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
            {display: '活动名称', name: 'name', width: 350, sortable: false, align: 'left'},
            {display: '店铺名称', name: 'store_name', width: 180, sortable: false, align: 'left'},
            {display: '开始时间', name: 'start_time_text', width: 120, sortable: false, align: 'center'},
            {display: '结束时间', name: 'end_time_text', width: 120, sortable: false, align: 'center'},
            {display: '状态', name: 'state_text', width: 80, sortable: false, align: 'center'}
        ],
        searchitems: [
            {display: '活动名称', name: 'name', isdefault: true},
            {display: '店铺名称', name: 'store_name'}
        ],
        sortname: "id",
        sortorder: "desc",
        title: '加价购活动列表'
    });

    // 高级搜索提交
    $('#ncsubmit').click(function(){
        $("#flexigrid").flexOptions({url: flexUrl + '&' + $("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });

    // 高级搜索重置
    $('#ncreset').click(function(){
        $("#flexigrid").flexOptions({url: flexUrl}).flexReload();
        $("#formSearch")[0].reset();
    });

    $('[data-dp]').datepicker({dateFormat: 'yy-mm-dd'});

});

$('a.confirm-on-click').live('click', function() {
    if (confirm('确定"'+$(this).text()+'"?')) {
        $.getJSON(this.href, function(d) {
            if (d && d.result) {
                $("#flexigrid").flexReload();
            } else {
                alert(d.message || '操作失败');
            }
        });
    }

    return false;
});
</script>
