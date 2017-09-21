<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>商品管理</h3>
        <h5>商城所有商品索引及管理</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['goods_index_help1'];?></li>
      <li><?php echo $lang['goods_index_help2'];?></li>
      <li>设置项中可以查看商品详细、查看商品SKU。查看商品详细，跳转到商品详细页。查看商品SKU，显示商品的SKU、图片、价格、库存信息。</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
  <?php if ($output['type'] == '') {?>
  <div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="ncap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" name="formSearch" id="formSearch">
      <div id="searchCon" class="content">
        <div class="layout-box">
          <dl>
            <dt>商品名称</dt>
            <dd>
              <label>
                <input type="text" value="" name="goods_name" id="goods_name" class="s-input-txt" placeholder="输入商品全称或关键字">
              </label>
            </dd>
          </dl>
          <dl>
            <dt>SPU</dt>
            <dd>
              <label>
                <input type="text" value="" name="goods_commonid" id="goods_commonid" class="s-input-txt" placeholder="输入商品平台货号">
              </label>
            </dd>
          </dl>
          <dl>
            <dt>所属店铺</dt>
            <dd>
              <label>
                <input type="text" value="" name="store_name" id="store_name" class="s-input-txt" placeholder="输入商品所属店铺名称">
              </label>
            </dd>
          </dl>
          <dl>
            <dt>所属品牌</dt>
            <dd>
              <label>
                <input type="text" value="" name="brand_name" id="brand_name" class="s-input-txt" placeholder="输入商品关联品牌关键字">
              </label>
            </dd>
          </dl>
          <dl>
            <dt>商品分类</dt>
            <dd id="gcategory">
              <input type="hidden" id="cate_id" name="cate_id" value="" class="mls_id" />
              <select class="class-select">
                <option value="0"><?php echo $lang['nc_please_choose'];?></option>
                <?php if(!empty($output['gc_list'])){ ?>
                <?php foreach($output['gc_list'] as $k => $v){ ?>
                <option value="<?php echo $v['gc_id'];?>"><?php echo $v['gc_name'];?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </dd>
          </dl>
          <dl>
            <dt>商品状态</dt>
            <dd>
              <label>
                <select name="goods_state" class="s-select">
                  <option value=""><?php echo $lang['nc_please_choose'];?></option>
                  <option value="1">出售中</option>
                  <option value="0">仓库中</option>
                  <option value="10">违规下架</option>
                </select>
              </label>
            </dd>
          </dl>
          <dl>
            <dt>审核状态</dt>
            <dd>
              <label>
                <select name="goods_verify" class="s-select">
                  <option value=""><?php echo $lang['nc_please_choose'];?></option>
                  <option value="1">通过</option>
                  <option value="0">未通过</option>
                  <option value="10">审核中</option>
                </select>
              </label>
            </dd>
          </dl>
        </div>
      </div>
      <div class="bottom"><a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green mr5">提交查询</a><a href="javascript:void(0);" id="ncreset" class="ncap-btn ncap-btn-orange" title="撤销查询结果，还原列表项所有内容"><i class="fa fa-retweet"></i><?php echo $lang['nc_cancel_search'];?></a></div>
    </form>
  </div>
  <script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
  <script type="text/javascript">
    gcategoryInit('gcategory');
    </script>
  <?php }?>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=goods&op=get_xml&type=<?php echo $output['type'];?>',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: 'SPU', name : 'goods_commonid', width : 60, sortable : true, align: 'center'},
            {display: '商品名称', name : 'goods_name', width : 150, sortable : false, align: 'left'},
            {display: '商品价格(元)', name : 'goods_price', width : 100, sortable : true, align: 'center'},
            {display: '商品状态', name : 'goods_state', width : 60, sortable : true, align: 'center'},
            {display: '审核状态', name : 'goods_verify', width : 60, sortable : false, align: 'center'},
            {display: '商品图片', name : 'goods_image', width : 60, sortable : true, align: 'center'},
            {display: '广告词', name : 'goods_jingle', width : 150, sortable : true, align: 'left'},
            {display: '分类ID', name : 'gc_id', width : 60, sortable : true, align: 'center'},
            {display: '分类名称', name : 'gc_name', width : 180, sortable : true, align: 'center'},
            {display: '店铺ID', name : 'store_id', width : 60, sortable : true, align: 'center'},
            {display: '店铺名称', name : 'store_name', width : 80, sortable : true, align: 'left'},
            {display: '店铺类型', name : 'is_own_shop', width : 80, sortable : true, align: 'center'},
            {display: '品牌ID', name : 'brand_id', width : 60, sortable : true, align: 'center'},
            {display: '品牌名称', name : 'brand_name', width : 80, sortable : true, align: 'left'},
            {display: '发布时间', name : 'goods_addtime', width : 100, sortable : true, align: 'center'},
            {display: '市场价格(元)', name : 'goods_marketprice', width : 100, sortable : true, align: 'center'},
            {display: '成本价格(元)', name : 'goods_costprice', width : 100, sortable : true, align: 'center'},
            {display: '运费(元)', name : 'goods_freight', width : 100, sortable : true, align: 'center'},
            {display: '库存', name : 'goods_storage', width : 100, sortable : false, align: 'center'},
            {display: '虚拟商品', name : 'is_virtual', width : 60, sortable : true, align: 'center', className: 'column-a'},
            {display: '有效期', name : 'virtual_indate', width : 100, sortable : true, align: 'center', className: 'column-a'},
            {display: '允许退款', name : 'virtual_invalid_refund', width : 100, sortable : false, align: 'center', className: 'column-a'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出CVS文件', onpress : fg_operation },
			{display: '<i class="fa fa-plus"></i>评价管理', name : 'pj', bclass : 'pinjia', title : '查看管理评价信息', onpress : fg_operation }
            ],
        searchitems : [
            {display: 'SPU', name : 'goods_commonid'},
            {display: '商品名称', name : 'goods_name'},
            {display: '分类ID', name : 'gc_id'},
            {display: '店铺ID', name : 'store_id'},
            {display: '店铺名称', name : 'store_name'},
            {display: '品牌ID', name : 'brand_id'},
            {display: '品牌名称', name : 'brand_name'}
            ],
        sortname: "goods_commonid",
        sortorder: "desc",
        title: '商品列表'
    });


    // 高级搜索提交
    $('#ncsubmit').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=goods&op=get_xml&'+$("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });

    // 高级搜索重置
    $('#ncreset').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=goods&op=get_xml'}).flexReload();
        $("#formSearch")[0].reset();
    });
});

function fg_operation(name, bDiv) {
    if (name == 'csv') {
        if ($('.trSelected', bDiv).length == 0) {
            if (!confirm('您确定要下载全部数据吗？')) {
                return false;
            }
        }
        var itemids = new Array();
        $('.trSelected', bDiv).each(function(i){
            itemids[i] = $(this).attr('data-id');
        });
        fg_csv(itemids);
    }else if (name == 'pj') { 
	        window.location.href = '<?php echo urladminshop('evaluate','evalgoods_list');?>';
    }
}

function fg_csv(ids) {
    id = ids.join(',');
    window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&op=export_csv&type=<?php echo $output['type'];?>&id=' + id;
}


//商品下架
function fg_lonkup(ids) {
    _uri = "index.php?act=goods&op=goods_lockup&id=" + ids;
    CUR_DIALOG = ajax_form('goods_lockup', '违规下架理由', _uri, 640);
}
//分销设置
function fg_invite(ids) {
    _uri = "index.php?act=goods&op=goods_invite&id=" + ids;
    CUR_DIALOG = ajax_form('goods_invite', '分销设置', _uri, 640);
}
//添加评论
function fg_pinglun(gids) {
    _uri = "index.php?act=goods&op=add&goods_id=" + gids;
    CUR_DIALOG = ajax_form('goods_pinlun', '为此商品添加评论', _uri, 640);
}

function fg_sku(commonid) {
    CUR_DIALOG = ajax_form('login','商品"' + commonid +'"的SKU列表','index.php?act=goods&op=get_goods_sku_list&commonid=' + commonid, 580);
}
// 删除
function fg_del(id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=goods&op=goods_del', {id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
// 商品审核
function fg_verify(ids) {
    _uri = "index.php?act=goods&op=goods_verify&id=" + ids;
    CUR_DIALOG = ajax_form('goods_verify', '审核商品', _uri, 640);
}
</script> 
