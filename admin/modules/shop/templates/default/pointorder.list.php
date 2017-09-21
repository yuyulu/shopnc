<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_pointprod'];?></h3>
        <h5>平台会员积分兑换礼品管理</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="index.php?act=pointprod&op=pointprod" ><span><?php echo $lang['admin_pointprod_list_title'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['admin_pointorder_list_title'];?></span></a></li>
      </ul>
    </div>
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
              <dt>兑换单号</dt>
              <dd>
                <input type="text" name="point_ordersn" class="s-input-txt" placeholder="请输入兑换单号" />
              </dd>
            </dl>
            <dl>
              <dt>会员名称</dt>
              <dd>
                <input type="text" name="point_buyername" class="s-input-txt" placeholder="请输入会员名称" />
              </dd>
            </dl>
            <dl>
              <dt>状态</dt>
              <dd>
                <select name="point_orderstate" class="s-select">
                    <option value="">-请选择-</option>
                    <?php foreach ((array) $output['states'] as $k => $v) { ?>
                    <option value="<?php echo $v[0]; ?>" ><?php echo $v[1]; ?></option>
                    <?php } ?>
                </select>
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
    var flexUrl = 'index.php?act=pointprod&op=pointorder_list_xml';

    $("#flexigrid").flexigrid({
        url: flexUrl,
        colModel: [
            {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
            {display: '兑换单号', name: 'point_ordersn', width: 300, sortable: false, align: 'left'},
            {display: '会员名称', name: 'point_buyername', width: 100, sortable: false, align: 'left'},
            {display: '兑换积分', name: 'point_allpoint', width: 80, sortable: false, align: 'center'},
            {display: '兑换时间', name: 'point_addtime_text', width: 120, sortable: false, align: 'center'},
            {display: '状态', name: 'point_orderstatetext', width: 80, sortable: false, align: 'center'}
        ],
        searchitems: [
            {display: '兑换单号', name: 'point_ordersn', isdefault: true},
            {display: '会员名称', name: 'point_buyername'}
        ],
        sortname: "point_orderid",
        sortorder: "desc",
        title: '积分兑换列表'
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

});

$('a.confirm-on-click').live('click', function() {
    return confirm('确定"'+this.innerHTML+'"?');
});
</script>
