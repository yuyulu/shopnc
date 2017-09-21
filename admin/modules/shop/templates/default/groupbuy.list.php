<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['groupbuy_index_manage']; ?></h3>
        <h5><?php echo $lang['groupbuy_index_manage_subhead']; ?></h5>
      </div>
      <ul class="tab-base nc-row">
        <?php foreach ($output['menu'] as $menu) { if ($menu['menu_type'] == 'text') { ?>
        <li><a href="javascript:void(0);" class="current"><?php echo $menu['menu_name']; ?></a></li>
        <?php } else { ?>
        <li><a href="<?php echo $menu['menu_url']; ?>"><?php echo $menu['menu_name']; ?></a></li>
        <?php } } ?>
      </ul>
    </div>
  </div>

  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>管理员可以审核新的抢购活动申请、取消进行中的抢购活动或者删除抢购活动</li>
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
              <dt>抢购名称</dt>
              <dd>
                <input type="text" name="groupbuy_name" class="s-input-txt" placeholder="请输入抢购名称关键字" />
              </dd>
            </dl>
            <dl>
              <dt>商品名称</dt>
              <dd>
                <input type="text" name="goods_name" class="s-input-txt" placeholder="请输入商品名称关键字" />
              </dd>
            </dl>
            <dl>
              <dt>店铺名称</dt>
              <dd>
                <input type="text" name="store_name" class="s-input-txt" placeholder="请输入店铺名称关键字" />
              </dd>
            </dl>
            <dl>
              <dt>抢购类型</dt>
              <dd>
                <select name="is_vr" class="s-select">
                    <option value="">全部</option>
                    <option value="1">虚拟</option>
                    <option value="0">实物</option>
                </select>
              </dd>
            </dl>
            <dl>
              <dt>状态</dt>
              <dd>
                <select name="state" class="s-select">
                    <?php foreach ((array) $output['groupbuy_state_array'] as $sk => $sv) { ?>
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
$(function(){
    var flexUrl = 'index.php?act=groupbuy&op=groupbuy_list_xml';

    $("#flexigrid").flexigrid({
        url: flexUrl,
        colModel: [
            {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
            {display: '抢购类型', name: 'is_vr', width: 60, sortable: false, align: 'left'},
            {display: '抢购名称', name: 'groupbuy_name', width: 400, sortable: false, align: 'left'},
            {display: '商品名称', name: 'goods_name', width: 400, sortable: false, align: 'left'},
            {display: '店铺名称', name: 'store_name', width: 200, sortable: false, align: 'left'},
            {display: '抢购图片', name: 'groupbuy_image', width: 60, sortable: false, align: 'center'},
            {display: '开始时间', name: 'start_time_text', width: 120, sortable: true, align: 'center'},
            {display: '结束时间', name: 'end_time_text', width: 120, sortable: true, align: 'center'},
            {display: '浏览数', name: 'views', width: 60, sortable: true, align: 'center'},
            {display: '已购买', name: 'buy_quantity', width: 60, sortable: true, align: 'center'},
            {display: '推荐', name: 'recommended', width: 60, sortable: false, align: 'center'},
            {display: '抢购状态', name: 'groupbuy_state_text', width: 80, sortable: false, align: 'center'}
        ],
        searchitems: [
            {display: '抢购名称', name: 'groupbuy_name', isdefault: true},
            {display: '商品名称', name: 'goods_name'},
            {display: '店铺名称', name: 'store_name'}
        ],
        sortname: "groupbuy_id",
        sortorder: "desc",
        title: '抢购活动列表'
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

$('a[data-href]').live('click', function() {
    if ($(this).hasClass('confirm-on-click') && !confirm('确定"'+$(this).text()+'"?')) {
        return false;
    }

    $.getJSON($(this).attr('data-href'), function(d) {
        if (d && d.result) {
            $("#flexigrid").flexReload();
        } else {
            alert(d && d.message || '操作失败！');
        }
    });
});

</script>
